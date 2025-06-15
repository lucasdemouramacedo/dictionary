<?php

namespace App\Exceptions;

use Exception;

class InvalidLoginInfoException extends Exception
{
    public function __construct()
    {
        $message = "Invalid email or password.";
        parent::__construct($message);
    }
}
