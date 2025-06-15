<?php

namespace App\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    public function __construct()
    {
        $message = "Invalid token";
        parent::__construct($message);
    }
}
