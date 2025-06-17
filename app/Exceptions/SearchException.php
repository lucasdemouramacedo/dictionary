<?php

namespace App\Exceptions;

use Exception;

class SearchException extends Exception
{
    public function __construct(string $modelName)
    {
            $message = "Erro ao buscar por {$modelName}.";
        parent::__construct($message);
    }
}
