<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDTO implements RequestDTOInterface
{
    #[Assert\NotBlank]
    public string $login;

    #[Assert\NotBlank]
    public string $password;
}
