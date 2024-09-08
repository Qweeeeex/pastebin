<?php

namespace App\Security\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginIncorrectException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            statusCode: Response::HTTP_UNAUTHORIZED,
            message: 'Пользователь с таким логином не существует. Попробуй еще раз',
            code: 1301
        );
    }
}
