<?php

namespace App\Exceptions;

use Exception;

class InvalidLoginInfoException extends Exception
{
    public function __construct()
    {
        $message = "Email ou senha inválidos.";
        parent::__construct($message);
    }
}
