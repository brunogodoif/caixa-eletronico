<?php

namespace App\Domain\Exceptions;

use Exception;

class UnavailableATMException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}