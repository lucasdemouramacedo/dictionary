<?php

namespace App\Exceptions;

use Exception;

class SearchException extends Exception
{
    public function __construct(string $modelName)
    {
            $message = "Error searching for {$modelName}.";
        parent::__construct($message);
    }
}
