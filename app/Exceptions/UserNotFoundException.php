<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        $message = "Usuário não encontrado.";
        parent::__construct($message);
    }
}