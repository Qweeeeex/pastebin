<?php

namespace App\Security\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExpiredTokenException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            statusCode: Response::HTTP_UNAUTHORIZED,
            message: 'Токен устарел',
            code: 1001,
        );
    }
}
