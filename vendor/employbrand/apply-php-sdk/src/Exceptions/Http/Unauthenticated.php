<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class Unauthenticated extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Authentication failed.")
    {
        parent::__construct($message);
    }
}
