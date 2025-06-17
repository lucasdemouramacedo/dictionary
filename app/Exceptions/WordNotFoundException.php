<?php

namespace App\Exceptions;

use Exception;

class WordNotFoundException extends Exception
{
    public function __construct()
    {
        $message = "Palavra não encontrada";
        parent::__construct($message);
    }
}