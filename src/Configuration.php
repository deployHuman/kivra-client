<?php

namespace DeployHuman\kivra;

use DateInterval;
use DateTime;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class Configuration
{
    protected bool $ForceRefreshToken = false;
    protected string $Client_id = '';
    protected string $Client_secret = '';
    protected string $BaseUrl = 'https://sender.api.kivra.com';
    protected string $userAgent = 'DeployHuman/Kivra-PHP-Client/1.0.0';
    protected string $storage_Default_name = 'kivra_auth';
    protected string $storage_name = 'kivra_auth';
    protected array $storage;
    protected bool $debug = false;
    protected logger $logstack;
    protected bool $ConnectDirectly = true;
    protected string $logpath = __DIR__ . '/../log/';
    protected bool $Storage_Is_Session = false;


    public function __construct(bool $StorageInSession = true, bool $ConnectDirectly = true)
    {
        $this->setStorageIsSession($StorageInSession);
        $this->ConnectDirectly = $ConnectDirectly;
    }

    /**
     * Making sure there is a Logger set.
     *
     * @return void
     */
    private function checkLogstack(): void
    {
        if (empty($this->logstack)) {
            $logger = new Logger(__CLASS__);
            $logger->pushHandler(new RotatingFileHandler($this->getLogPath() . DIRECTORY_SEPARATOR . 'api.log', 10, Level::Debug));
            $this->logstack = $logger;
        }
    }


    public function getLogger(): Logger
    {
        $this->checkLogstack();
        return $this->logstack;
    }

    public function setLogger(Logger $logstack): self
    {
        $this->logstack = $logstack;
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
                $this->getLogger(),
                new MessageFormatter('{code}:{method}:{uri}'),
                $this->getDebug() ? 'debug' : 'warning'
            )
        );
        return $stack;
    }

    public function setLogPath(string $path): self
    {
        $this->logpath = $path;
        return $this;
    }

    public function getLogPath(): string
    {
        if (!realpath($this->logpath)) {
            mkdir($this->logpath);
        }

        return realpath($this->logpath);
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
        return $this->Client_id ?? '';
    }

    public function setClient_secret(string $Client_secret): self
    {
        $this->Client_secret = $Client_secret;
        return $this;
    }

    public function getClient_secret(): string
    {
        return $this->Client_secret ?? '';
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
        return $this->userAgent ?? 'DeployHuman/Kivra-PHP-Client/1.0.0';
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

    public function saveToStorage(array $asocArray): self
    {
        $this->initateStorage();
        if ($this->getStorageIsSession()) {
            if (function_exists('session')) {
                session($asocArray);
            } else {
                $_SESSION[$this->storage_name] = array_merge($_SESSION[$this->storage_name], $asocArray);
            }
        } else {
            $this->storage[$this->storage_name] = array_merge($this->storage[$this->storage_name], $asocArray);
        }
        return $this;
    }

    public function unsetFromStorage(array $UnsetKeys): self
    {
        foreach ($UnsetKeys as $key) {
            if ($this->getStorageIsSession()) {
                if (function_exists('session')) {
                    session()->forget($key);
                } else {
                    unset($_SESSION[$this->storage_name][$key]);
                }
            } else {
                unset($this->storage[$this->storage_name][$key]);
            }
        }
        return $this;
    }

    public function getStorageName(): string
    {
        return $this->storage_name;
    }

    public function getStorage(): array
    {
        $this->initateStorage();
        if ($this->getStorageIsSession()) {
            if (function_exists('session')) {
                return session()->all();
            } else {
                return $_SESSION[$this->storage_name] ?? [];
            }
        }
        return $this->storage[$this->storage_name] ?? [];
    }

    public function getStorageIsSession(): bool
    {
        return $this->Storage_Is_Session ?? false;
    }

    public function setStorageIsSession(bool $UseSession = true): self
    {
        $this->Storage_Is_Session = $UseSession;
        return $this;
    }

    public function initateStorage(): bool
    {
        if (!isset($this->storage_name)) $this->storage_name = $this->storage_Default_name;

        if ($this->getStorageIsSession()) {
            if (function_exists('session')) {
                return true;
            }
            if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
                session_start();
            }

            if (!isset($_SESSION[$this->storage_name])) {
                $_SESSION[$this->storage_name] = [];
            }
            return true;
        }

        if (!$this->getStorageIsSession()) {
            if (!isset($this->storage[$this->storage_name])) {
                $this->storage[$this->storage_name] = [];
            }
            return true;
        }

        return false;
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
        $this->saveToStorage($this->getSettingsArray());
    }

    public function getSettingsArray(): array
    {
        return [
            'Client_id' => $this->getClient_id(),
            'Client_secret' => $this->getClient_secret(),
            'BaseUrl' => $this->getBaseUrl(),
            'UserAgent' => $this->getUserAgent(),
            'debug' => $this->getDebug(),
            'ForceRefreshToken' => $this->getForceRefreshToken(),
            'StorageName' => $this->getStorageName(),
            'StorageIsSession' => $this->getStorageIsSession(),
            'LoggerName' => $this->getLogger()->getName(),
        ];
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
