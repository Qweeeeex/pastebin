<?php

namespace App\Repository\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PasteNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Пасты с таким ID не сущесвтует, либо срок годности истек', Response::HTTP_NOT_FOUND);
    }
}