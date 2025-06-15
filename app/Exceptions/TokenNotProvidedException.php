<?php

namespace App\Exceptions;

use Exception;

class TokenNotProvidedException extends Exception
{
    public function __construct()
    {
        $message = "Token not provided";
        parent::__construct($message);
    }
}
