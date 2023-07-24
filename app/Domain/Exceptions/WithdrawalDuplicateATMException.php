<?php

namespace App\Domain\Exceptions;

use Exception;

class WithdrawalDuplicateATMException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}