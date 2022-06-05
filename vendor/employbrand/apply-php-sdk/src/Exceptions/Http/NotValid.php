<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class NotValid extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Request invalid.")
    {
        parent::__construct($message);
    }
}
