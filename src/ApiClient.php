<?php

namespace DeployHuman\kivra;

use DateTime;
use DeployHuman\kivra\Api\Authentication;
use DeployHuman\kivra\Api\TenantContent;
use DeployHuman\kivra\Api\TenantManagement;
use DeployHuman\kivra\Enum\ApiMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Monolog\Registry;

class ApiClient
{
    protected Client $client;

    protected Configuration $config;

    public function __construct(null|Configuration &$config = null)
    {
        if (! isset($this->config)) {
            $this->config = $config ?? new Configuration();
        }
        Registry::addLogger($this->config->getLogger(), $this->config->getLogger()->getName(), true);

        $this->client = new Client([
            'base_uri' => $this->config->getBaseUrl(),
            'handler' => $this->config->getDebugHandler(),
            'user_agent' => $this->config->getUserAgent(),
            'http_errors' => true,
        ]);

        if (get_parent_class($this) !== false) {
            return;
        }
        $this->config->saveToStorage($this->config->getSettingsArray());

        if (! $this->config->isClientAuthSet()) {
            throw new Exception('Missing Base Creditentials, Check over BaseUrl and Client_id and Client_secret', $this->config->getLogger()->getName());
        }
        if ($this->config->getConnectDirectly()) {
            $this->refreshAccessToken($this->config->getForceRefreshToken());
        }
    }

    protected function refreshAccessToken(bool $ForceRefreshToken = false): bool
    {
        if ($ForceRefreshToken) {
            $this->config->resetAccesToken();
        }
        if ($this->isTokenValid($this->config->getStorage())) {
            return true;
        }

        $response = $this->Authentication()->callAPIAuthToGetAccessToken();
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $body = json_decode($response->getBody()->getContents(), true);

        if (isset($body['access_token'])) {
            $this->config->saveNewAccessToken($body);

            return true;
        }

        return false;
    }

    /**
     * gets an API Client with all configuration set
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Cleanup of output array from Kivra
     * Seems like they keep sending empty fields in the form of "[]" which will make it as an array and cause conversion to string error
     *
     * @param  array  $arrayToClean
     * @return array
     */
    protected function cleanUpEmptyFields(array $arrayToClean): array
    {
        foreach ($arrayToClean as $key => $value) {
            if ($value == '[]') {
                $arrayToClean[$key] = null;
            }
            if (is_array($value) && count($value) == 0) {
                $arrayToClean[$key] = null;
            }
        }

        return $arrayToClean;
    }

    protected function isTokenValid(array $auth): bool
    {
        return $this->isSameBaseUrl($auth) && ! $this->isTokenExpired($auth);
    }

    protected function isTokenExpired(array $auth): bool
    {
        if (! isset($auth['expires_at'])) {
            return false;
        }

        return $auth['expires_at'] < (new DateTime());
    }

    protected function isSameBaseUrl(array $auth): bool
    {
        if (isset($auth['BaseUrl'])) {
            return false;
        }

        return $auth['BaseUrl'] == $this->config->getBaseUrl();
    }

    protected function basicTokenCheck(string $ScopeNeeded = null): bool|Exception
    {
        if (! $this->config->isClientAuthSet()) {
            throw new Exception('Error in Kivra Settings', $this->config->getLogger()->getName());
        }
        if (! $this->refreshAccessToken()) {
            throw new Exception('Error in fetching Access Token for basic APi CALL on Kivra', $this->config->getLogger()->getName());
        }
        if ($ScopeNeeded != null && ! $this->config->hasScope($ScopeNeeded)) {
            throw new Exception('Error in fetching Access Token for basic APi CALL on Kivra', $this->config->getLogger()->getName());
        }

        return true;
    }

    protected function getAccessToken(): string|null
    {
        return $this->config->getStorage()['access_token'] ?? null;
    }

    /**
     * Send a request to the Kivra API.
     *
     * @param  ApiMethod  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     *
     * @throws Exception
     */
    protected function request(ApiMethod $method = ApiMethod::GET, string $uri = '', array $data = [], array $params = []): Response
    {
        if (! $this->config->isClientAuthSet()) {
            throw new Exception('Error in Kivra Settings', $this->config->getLogger()->getName());
        }
        if (! $this->isTokenValid($this->config->getStorage())) {
            $this->refreshAccessToken(true);
        }
        if ($this->getAccessToken() == null) {
            throw new Exception('Error in Kivra Settings');
        }

        $optionsarray = [];
        if (! empty($params)) {
            $optionsarray[RequestOptions::QUERY] = $params;
        }
        if (! empty($data)) {
            $optionsarray[RequestOptions::JSON] = $data;
        }
        $optionsarray[RequestOptions::HEADERS] = ['Authorization' => 'Bearer '.$this->getAccessToken()];

        return $this->getClient()->request($method->value, $uri, $optionsarray);
    }

    /**
     * Send a GET request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function get(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::GET, $uri, $data, $params);
    }

    /**
     * Send a POST request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function post(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::POST, $uri, $data, $params);
    }

    /**
     * Send a PUT request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function put(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::PUT, $uri, $data, $params);
    }

    /**
     * Send a DELETE request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function delete(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::DELETE, $uri, $data, $params);
    }

    /**
     * Send a PATCH request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function patch(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::PATCH, $uri, $data, $params);
    }

    /**
     * Send a OPTIONS request to the Kivra API.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $params
     * @return Response
     */
    public function options(string $uri, array $data = [], array $params = []): Response
    {
        return $this->request(ApiMethod::OPTIONS, $uri, $data, $params);
    }

    /**
     * Tenant API - Tenant Management.
     * Endpoints for creation and administration of tenants (v2)
     *
     * @documentation http://developer.kivra.com/#tag/Tenant-API-Tenant-Management
     *
     * @return TenantManagement
     */
    public function TenantManagement(): TenantManagement
    {
        return new TenantManagement($this->config);
    }

    /**
     * API - Authentication.
     * Kivra supports Oauth2 with Client Credentials flow. Each client has a client_id and a client_secret and these need to be base64 encoded and sent to the API via POST to receive an access token which is used for subsequent calls.
     * Create the RFC 2045 base64 encoding to be used for tenant registration, replace client_id and client_secret with real values and make sure there are no trailing newlines (echo -n) and that the string is encoded literally (use single quotes and no escaping)
     *
     * @documentation http://developer.kivra.com/#section/API-Authentication
     *
     * @return Authentication
     */
    public function Authentication(): Authentication
    {
        return new Authentication($this->config);
    }

    /**
     * API - Content.
     *
     * Endpoints for matching users and sending content
     *
     * @documentation http://developer.kivra.com/#tag/Tenant-API-Content
     *
     * @return TenantContent
     */
    public function TenantContent(): TenantContent
    {
        return new TenantContent($this->config);
    }
}
