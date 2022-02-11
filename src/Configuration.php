<?php

namespace DeployHuman\kivra;

use DateInterval;
use DateTime;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Configuration
{
    protected bool $ForceRefreshToken = false;
    protected string $accessToken = '';
    protected string $Client_id = '';
    protected string $Client_secret = '';
    protected string $apiVersion = 'v2';
    protected string $BaseUrl = 'https://sender.api.kivra.com';
    protected string $userAgent = 'DeployHuman/Kivra-PHP-Client/1.0.0';
    protected string $debugFile = 'php://output';
    protected string $tempFolderPath;
    protected string $storage_Default_name = 'kivra_auth';
    protected string $storage_name;
    protected bool $debug = false;
    protected bool $ErrorPrintOut = false;
    protected bool $ConnectDirectly = true;
    protected string $logpath = './';

    public function __construct(string $storagename = null, bool $ConnectDirectly = true)
    {
        $this->setStorageName($storagename);
        $this->initateStorage();
        $this->tempFolderPath = sys_get_temp_dir();
        $this->ConnectDirectly = $ConnectDirectly;
    }

    public function setConnectDirectly(bool $ConnectDirectly): self
    {
        $this->ConnectDirectly = $ConnectDirectly;
        return $this;
    }

    public function getDebugHandler(): HandlerStack
    {
        $log = new Logger('API');
        $log->pushHandler(new StreamHandler($this->logpath));
        $stack = HandlerStack::create();

        $stack->push(
            Middleware::log(
                $log,
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

    public function setErrorPrintOut(bool $ErrorPrintOut): self
    {
        $this->ErrorPrintOut = $ErrorPrintOut;
        return $this;
    }

    public function getErrorPrintOut(): bool
    {
        return $this->ErrorPrintOut;
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
        $_SESSION[$this->storage_name] = array_merge($_SESSION[$this->storage_name], $params);
        return $this;
    }

    public function unsetFromStorage(array $UnsetKeys): self
    {
        foreach ($UnsetKeys as $key) {
            unset($_SESSION[$this->storage_name][$key]);
        }
        return $this;
    }

    public function getStorageName(): string
    {
        return $this->storage_name;
    }

    public function getStorage(): array
    {
        if (!isset($_SESSION[$this->storage_name])) {
            $this->initateStorage();
        }
        return $_SESSION[$this->storage_name];
    }

    public function initateStorage(): bool|Exception
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new Exception('Invalid AUTH storage. Use session_start() before instantiating Kivra');
        }
        if ($this->storage_name == null) {
            $this->storage_name = clone $this->storage_Default_name;
        }
        if (!array_key_exists($this->storage_name, $_SESSION) || !is_array($_SESSION[$this->storage_name])) {
            $_SESSION[$this->storage_name] = [];
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
