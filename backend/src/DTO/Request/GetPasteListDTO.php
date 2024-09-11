<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints\Type;

class GetPasteListDTO implements RequestDTOInterface
{
    #[Type('int')]
    public ?int $page = 1;

    #[Type('int')]
    public ?int $limit = 10;
}