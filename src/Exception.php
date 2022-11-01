<?php

namespace DeployHuman\kivra;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Registry;

class Exception extends \Exception
{
    public function __construct($message = '', string $loggername = null)
    {
        if ($loggername == null || ! Registry::hasLogger($loggername)) {
            $logger = new Logger(__CLASS__);
            $loggername = $logger->getName();
            $logger->pushHandler(new StreamHandler(__DIR__.DIRECTORY_SEPARATOR.'apiError.log', Logger::DEBUG));
            Registry::addLogger($logger, $loggername, true);
        }
        Registry::getInstance($loggername)->error('Exception thrown:'.$message);
        parent::__construct($message);
    }
}
