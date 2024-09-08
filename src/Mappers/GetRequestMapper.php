<?php

namespace App\Mappers;

use App\Resolvers\GetRequestResolver;
use Attribute;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * @Annotation
 */
#[Attribute]
class GetRequestMapper extends MapRequestPayload
{
    public function __construct(
        array|string $acceptFormat = null,
        ?array $serializationContext = [],
        GroupSequence|array|string $validationGroups = null
    ) {
        parent::__construct($acceptFormat, $serializationContext, $validationGroups, GetRequestResolver::class);
    }
}
