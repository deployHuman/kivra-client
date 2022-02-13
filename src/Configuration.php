<?php

namespace DeployHuman\kivra;

use DateInterval;
use DateTime;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\ErrorHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Registry;

class Configuration
{
    protected bool $ForceRefreshToken = false;
    protected string $accessToken = '';
    protected string $Client_id = '';
    protected string $Client_secret = '';
    protected string $apiVersion = 'v2';
    protected string $BaseUrl = 'https://sender.api.kivra.com';
    protected string $userAgent = 'DeployHuman/Kivra-PHP-Client/1.0.0';
    protected string $tempFolderPath;
    protected string $storage_Default_name = 'kivra_auth';
    protected string $storage_name;
    protected array $storage;
    protected bool $debug = false;
    protected logger $logstack;
    protected bool $ConnectDirectly = true;
    protected string $logpath = __DIR__ . './log/';

    public function __construct(string $storagename = null, bool $ConnectDirectly = true)
    {
        $this->setStorageName($storagename);
        $this->initateStorage();
        $this->tempFolderPath = sys_get_temp_dir();
        $this->ConnectDirectly = $ConnectDirectly;
    }

    private function setGlobalLogger(Logger $logger = null)
    {
        if ($logger == null) {
            $logger = new Logger('API');
            $logger->pushHandler(new StreamHandler($this->getLogPath() . '/api.log', Logger::DEBUG));
            $logger->pushHandler(new FirePHPHandler());
        }
        Registry::addLogger($logger);
        ErrorHandler::register($logger);
    }


    public function getLogger(): Logger
    {
        return $this->logstack;
    }

    public function setLogger(Logger $logstack): self
    {
        $this->logstack = $logstack;
        $this->setGlobalLogger($logstack);
        return $this;
    }

    public function setConnectDirectly(bool $ConnectDirectly): self
    {
        $this->ConnectDirectly = $ConnectDirectly;
        return $this;
    }

    public function getDebugHandler(): HandlerStack
    {
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $this->logstack,
                new MessageFormatter('{uri} - {code} -  request Headers: {req_headers} - Response Headers {res_headers}')
            )
        );
        return $stack;
    }

    public function setLogPath(string $path): self
    {
        $this->logpath = $path;
        return $this;
    }

    public function getLogPath(): bool
    {
        return $this->logpath;
    }


    public function getConnectDirectly(): bool
    {
        return $this->ConnectDirectly;
    }

    public function setClient_id(string $Client_id): self
    {
        $this->Client_id = $Client_id;
        return $this;
    }

    public function getClient_id(): string
    {
        return $this->Client_id;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function getAPIversion(): string
    {
        if (!isset($this->apiVersion)) {
            return 'v2';
        }
        return $this->apiVersion;
    }

    public function setClient_secret(string $Client_secret): self
    {
        $this->Client_secret = $Client_secret;
        return $this;
    }

    public function getClient_secret(): string
    {
        return $this->Client_secret;
    }

    public function SetBaseUrl(string $BaseUrl): self
    {
        $this->BaseUrl = $BaseUrl;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->BaseUrl;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent ?? $this->userAgent;
        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function getForceRefreshToken(): bool
    {
        return $this->ForceRefreshToken;
    }

    public function setForceRefreshToken(bool $ForceRefreshToken): self
    {
        $this->ForceRefreshToken = $ForceRefreshToken ?? false;
        return $this;
    }

    public function setStorageName(string $ArrayName = null): self
    {
        $this->storage_name = $ArrayName ?? $this->storage_Default_name;
        return $this;
    }

    public function saveToStorage(array $params): self
    {
        $this->storage[$this->storage_name] = array_merge($this->storage[$this->storage_name], $params);
        return $this;
    }

    public function unsetFromStorage(array $UnsetKeys): self
    {
        foreach ($UnsetKeys as $key) {
            unset($this->storage[$this->storage_name][$key]);
        }
        return $this;
    }

    public function getStorageName(): string
    {
        return $this->storage_name;
    }

    public function getStorage(): array
    {
        if (!isset($this->storage[$this->storage_name])) {
            $this->initateStorage();
        }
        return $this->storage[$this->storage_name];
    }

    private function isDebug(): bool
    {
        if (isset($this->debug) && $this->debug === true) {
            return true;
        }
        return false;
    }

    public function initateStorage(): bool|Exception
    {
        if (isset($this->storage)) {
            if (isset($this->storage[$this->storage_name])) {
                return true;
            }
        }
        if (session_status() !== PHP_SESSION_ACTIVE && !$this->isDebug()) {
            throw new Exception('Invalid AUTH storage. Use session_start() before instantiating Kivra');
        }
        if ($this->storage_name == null) $this->storage_name = clone $this->storage_Default_name;
        $this->storage[$this->storage_name] = [];
        if ($this->isDebug()) {
            return true;
        }
        if (!array_key_exists($this->storage_name, $_SESSION) || !is_array($_SESSION[$this->storage_name])) {
            $_SESSION[$this->storage_name] = [];
            $this->storage = &$_SESSION[$this->storage_name];
        }
        return true;
    }

    public function isClientAuthSet(): bool
    {
        if (empty($this->Client_id) || empty($this->Client_secret) || empty($this->BaseUrl)) {
            return false;
        }
        return true;
    }


    public function saveNewAccessToken(array $authBody)
    {
        $this->saveToStorage(
            [
                'expires_in' => $authBody['expires_in'],
                'access_token' => $authBody['access_token'],
                'scope' => $authBody['scope'],
                'scope_array' => explode(' ', $authBody['scope']),
                'token_type' => $authBody['token_type'],
                'expires_at' => (new DateTime())->add(new DateInterval('PT' . $authBody['expires_in'] . 'S')),
            ]
        );
        $this->setStorageExtraParams();
    }


    private function setStorageExtraParams()
    {
        $this->saveToStorage(
            [
                'baseurl' => $this->getBaseUrl(),
                'user_agent' => $this->getUserAgent(),
                'debug' => $this->getDebug(),
                'apiversion' => $this->getAPIversion()
            ]
        );
    }

    public function hasScope(string $Scope): bool
    {
        return true;
        //Todo Fix this using regex or something
        $scopeMethod = substr($Scope, 0, strpos($Scope, ':'));
        $fromright = substr($Scope, strpos($Scope, ':'), strlen($Scope) - strpos($Scope, ':') -  strpos(strrev($Scope), '.'));
        $scopeUri = substr($Scope, strpos($Scope, ':') + 1,);

        $scopeArray = $this->getStorage()['scope_array'];
        foreach ($scopeArray as $key => $value) {
            $pos = strpos($value, ':');
            if ($pos === false) {
                //no : found
                if ($value == $Scope) {
                    return true;
                }
            } else {
                //: found
                $scope = substr($value, 0, $pos);
                if ($scope == $Scope) {
                    return true;
                }
            }
        }
        if (in_array($Scope, $scopeArray)) {
            return true;
        }
        return false;
    }

    public function resetAccesToken()
    {
        $this->unsetFromStorage(['access_token', 'expires_at', 'scope', 'token_type', 'expires_in']);
    }
}
