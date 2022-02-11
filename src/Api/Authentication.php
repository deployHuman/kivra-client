<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;

class Authentication extends ApiClient
{
    /**
     * To Get Bearer Token from Client ID and Client Secret
     * @url /v1/auth
     * @Documentation http://developer.kivra.com/#section/API-Authentication
     *
     * @return response|false
     */
    public function callAPIAuthToGetAccessToken(): Response|false
    {
        if (!$this->config->isClientAuthSet()) throw new Exception("Error in Kivra Settings");

        $client = $this->getClient();
        try {
            $response = $client->request(
                "POST",
                '/v2/auth',
                [
                    'form_params' => [
                        'grant_type' => 'client_credentials'
                    ],
                    'auth' => [$this->config->getClient_id(), $this->config->getClient_secret()]
                ]
            );
        } catch (ClientException $e) {
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $this->setAPIError('ClientException', 'Description: ' . $desc . ' Request: ' . $SentRequest);
            return false;
        }
        return $response;
    }
}
