<?php

namespace App\Exceptions;

use Exception;

class WordNotFoundException extends Exception
{
    public function __construct()
    {
        $message = "Word not found";
        parent::__construct($message);
    }
}