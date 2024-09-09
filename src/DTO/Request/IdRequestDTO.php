<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class IdRequestDTO implements RequestDTOInterface
{
    #[Assert\NotBlank, Assert\Length(max: 10, maxMessage: 'ID не может быть длиннее 10 символов')]
    public string $id;
}