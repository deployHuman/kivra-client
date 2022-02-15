<?php

namespace DeployHuman\kivra;


class Exception extends \Exception
{

    public function __construct($message = "", $code = 0, $responseHeaders = [], $responseBody = null)
    {
        parent::__construct($message, $code);
    }
}
