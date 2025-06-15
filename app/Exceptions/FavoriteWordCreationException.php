<?php

namespace App\Exceptions;

use Exception;

class FavoriteWordCreationException extends Exception
{
    public function __construct()
    {
        $message = "Error favoriting the word.";
        parent::__construct($message);
    }
}
