<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class NotFound extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Resource not found.")
    {
        parent::__construct($message);
    }
}
