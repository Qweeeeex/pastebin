<?php

namespace App\Resolvers;

use App\DTO\Request\RequestDTOInterface;
use App\Resolvers\Exceptions\ConstraintViolationException;
use App\Resolvers\NameConverters\SnakeCaseToCamelCaseConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequestResolver implements ValueResolverInterface
{
    private const string ROUTE_PARAMS_KEY = '_route_params';

    abstract public function checkRequestMethod(Request $request): void;

    abstract public function getParamsFromRequest(Request $request): array;

    private Serializer $serializer;

    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $propertyInfoExtractor = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor],
            [$phpDocExtractor],
            [$reflectionExtractor],
            [$reflectionExtractor]
        );
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(
                    nameConverter: new SnakeCaseToCamelCaseConverter(),
                    propertyTypeExtractor: $propertyInfoExtractor
                ),
                new ArrayDenormalizer(),
            ],
            []
        );
    }

    /**
     * @throws \ReflectionException
     * @throws ConstraintViolationException
     * @throws \Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dtoClass = $argument->getType();
        $this->checkClass($dtoClass);
        $this->checkRequestMethod($request);
        $params = $this->getParamsFromRequest($request);

        try {
            $dto = $this->serializer->denormalize(
                $params,
                $dtoClass,
                'array',
                [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true]
            );
        } catch (PartialDenormalizationException $e) {
            $constraintViolationList = new ConstraintViolationList();
            $reflectionClass = new \ReflectionClass($argument->getType());
            foreach ($reflectionClass->getProperties() as $reflectionProperty) {
                if (!$reflectionProperty->isInitialized($e->getData())) {
                    $propertyType = $reflectionProperty->getType();
                    if (str_contains($propertyType, '?')) {
                        $message = 'Неверный тип параметра. Ожидался '.ltrim($reflectionProperty->getType(), '?').' или null';
                    } else {
                        $message = 'Неверный тип параметра. Ожидался '.$reflectionProperty->getType();
                    }

                    $constraintViolationList->add(new ConstraintViolation($message, '', [], null, $reflectionProperty->getName(), null));
                }
            }
            throw new ConstraintViolationException($constraintViolationList);
        }

        $validationErrors = $this->validator->validate($dto);
        if ($validationErrors->count() > 0) {
            throw new ConstraintViolationException($validationErrors);
        }

        return [$dto];
    }

    /**
     * @throws \Exception
     */
    private function checkClass(string $dtoClass): void
    {
        if (!is_a($dtoClass, RequestDTOInterface::class, true)) {
            throw new \Exception('Неподдерживаемый DTO, DTO должно реализовывать интерфейс RequestDTOInterface!');
        }
    }

    public function getRequestRouteParams(Request $request): array
    {
        return $request->attributes->get(self::ROUTE_PARAMS_KEY);
    }

    public function strictConvert(array $data): array
    {
        $data = array_map(fn ($value) => is_array($value) ? $this->emptyValuesFilter($value) : $value, $data);
        $data = $this->emptyValuesFilter($data);

        if (empty($data)) {
            return [];
        }
        $json = json_encode($data, JSON_NUMERIC_CHECK);
        $json = str_replace(['"false"', '"true"'], ['false', 'true'], $json);

        return json_decode($json, true);
    }

    private function emptyValuesFilter(array $values): array
    {
        return array_filter($values, function ($value) {
            return is_numeric($value) || false === is_null($value);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
