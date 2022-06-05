<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class InternalServerError extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Internal server error at Employbrand.")
    {
        parent::__construct($message);
    }
}
