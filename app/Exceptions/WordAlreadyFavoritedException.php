<?php

namespace App\Exceptions;

use Exception;

class WordAlreadyFavoritedException extends Exception
{
    public function __construct()
    {
        $message = "This word has already been favorited.";
        parent::__construct($message);
    }
}
