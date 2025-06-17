<?php

namespace App\Exceptions;

use Exception;

class UserCreationException extends Exception
{
    public function __construct()
    {
        $message = "Erro ao criar usuário.";
        parent::__construct($message);
    }
}
