<?php

namespace App\Modules\Security\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownTokenType extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            statusCode: Response::HTTP_INTERNAL_SERVER_ERROR,
            message: 'Неизвестный тип токена',
            code: 1003,
        );
    }
}
