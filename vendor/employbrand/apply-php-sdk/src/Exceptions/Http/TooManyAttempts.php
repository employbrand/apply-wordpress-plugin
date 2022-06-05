<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class TooManyAttempts extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Too many attempts.")
    {
        parent::__construct($message);
    }
}
