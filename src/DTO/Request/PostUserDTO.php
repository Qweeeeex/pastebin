<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PostUserDTO implements RequestDTOInterface
{
    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'Логин не может быть длиннее 255 символов')]
    public string $login;

    #[Assert\NotBlank, Assert\Length(min: 8, minMessage: 'Пароль должен быть длиннее 8 символов')]
    public string $password;
}