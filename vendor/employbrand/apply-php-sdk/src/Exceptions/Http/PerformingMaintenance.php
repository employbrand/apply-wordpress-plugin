<?php

namespace EmploybrandApply\Exceptions\Http;

use Exception;


class PerformingMaintenance extends Exception
{

    /**
     * @param string $message
     */
    public function __construct(string $message = "Employbrand is performing maintance.")
    {
        parent::__construct($message);
    }
}
