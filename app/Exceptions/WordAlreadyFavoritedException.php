<?php

namespace App\Exceptions;

use Exception;

class WordAlreadyFavoritedException extends Exception
{
    public function __construct()
    {
        $message = "Essa palavra já está nas favoritas.";
        parent::__construct($message);
    }
}
