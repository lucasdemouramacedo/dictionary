<?php

namespace App\Exceptions;

use Exception;

class UserCreationException extends Exception
{
    public function __construct()
    {
        $message = "Error creating user";
        parent::__construct($message);
    }
}
