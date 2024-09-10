<?php

namespace App\Services\Exceptions\Paste;

class UserUnauthorizedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Неавторизованный пользователь мне может создавать приватные пасты');
    }
}
