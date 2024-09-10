<?php

namespace App\Listeners;

use App\Resolvers\Exceptions\ConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelExceptionListener implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        if ('dev' !== $_ENV['APP_ENV']) {
            return [
                KernelEvents::EXCEPTION => ['onKernelException', 2],
            ];
        }

        return [];
    }

    /**
     * @throws \Exception
     */
    public function onKernelException(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        $response = match ($throwable::class) {
            ConstraintViolationException::class => $this->getResponseValidationError($throwable),
            default => $this->getResponseDefault($throwable),
        };

        $exceptionEvent->setResponse($response);
    }

    private function getResponseDefault(\Throwable $throwable): JsonResponse
    {
        $code = array_key_exists($throwable->getCode(), Response::$statusTexts)
            ? $throwable->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $statusCode = $code;

        if ($throwable instanceof HttpException) {
            $statusCode = $throwable->getStatusCode();
            $code = $throwable->getCode() ?: $statusCode;
        }

        $response = [
            'error' => [
                'message' => $throwable->getMessage(),
                'code' => $code,
            ],
        ];

        return new JsonResponse($response, $statusCode);
    }

    private function getResponseValidationError(\Throwable $throwable): JsonResponse
    {
        if (!$throwable instanceof ConstraintViolationException) {
            throw new \Exception('Неправильная функция преобразования ошибки');
        }

        $errors = $throwable->getConstraintViolationList();
        $messageArray = ['message' => 'validation failed'];
        foreach ($errors as $error) {
            $path = $error->getPropertyPath();
            if ('' === $path) {
                $messageArray['request_params'][] = $error->getMessage();
            } else {
                $messageArray['params'][$path][] = $error->getMessage();
            }
        }
        $messageArray = ['error' => $messageArray];

        return new JsonResponse($messageArray, Response::HTTP_BAD_REQUEST);
    }
}
