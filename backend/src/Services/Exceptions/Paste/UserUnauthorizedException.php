<?php

namespace App\Services\Exceptions\Paste;

class UserUnauthorizedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Неавторизованный пользователь не может создавать или получать приватные пасты');
    }
}
