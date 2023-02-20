<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class Authentication extends ApiClient
{
    /**
     * To Get Bearer Token from Client ID and Client Secret
     *
     * @url /v2/auth
     *
     * @Documentation http://developer.kivra.com/#section/API-Authentication
     *
     * @throws GuzzleException
     */
    public function callAPIAuthToGetAccessToken(): Response
    {
        if (! $this->config->isClientAuthSet()) {
            throw new Exception('Error in Kivra Auth Settings', $this->config->getLogger()->getName());
        }

        $response = $this->getClient()->request(
            'post',
            '/v2/auth',
            [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
                'auth' => [$this->config->getClient_id(), $this->config->getClient_secret()],
            ]
        );
        if ($this->config->getDebug()) {
            $this->config->getLogger()->debug(__CLASS__ . '::' . __FUNCTION__ . ' - Response body: ' . $response->getBody()->getContents());
            $response->getBody()->rewind();
        }

        return $response;
    }
}
