<?php

namespace App\Repository\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Пользователь не найден', Response::HTTP_NOT_FOUND);
    }
}