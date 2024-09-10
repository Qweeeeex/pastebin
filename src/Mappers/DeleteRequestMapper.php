<?php

namespace App\Mappers;

use App\Resolvers\DeleteRequestResolver;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * @Annotation
 */
#[\Attribute]
class DeleteRequestMapper extends MapRequestPayload
{
    public function __construct(
        array|string|null $acceptFormat = null,
        ?array $serializationContext = [],
        GroupSequence|array|string|null $validationGroups = null,
    ) {
        parent::__construct($acceptFormat, $serializationContext, $validationGroups, DeleteRequestResolver::class);
    }
}
