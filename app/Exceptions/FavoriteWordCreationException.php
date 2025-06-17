<?php

namespace App\Exceptions;

use Exception;

class FavoriteWordCreationException extends Exception
{
    public function __construct()
    {
        $message = "Erro ao favoritar palavra.";
        parent::__construct($message);
    }
}
