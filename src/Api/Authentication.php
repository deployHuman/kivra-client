<?php

namespace DeployHuman\kivra\Api;

use DateTime;
use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Message;


class Authentication extends ApiClient
{
    /**
     * To Get Bearer Token from Client ID and Client Secret
     * @url /v1/auth
     * @Documentation http://developer.kivra.com/#section/API-Authentication
     *
     * @return array|false
     */
    public function callAPIAuthToGetAccessToken(): array|false
    {
        if (!$this->config->isClientAuthSet()) throw new Exception("Error in Kivra Settings");

        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
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
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }
        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            if ($this->config->getDebug()) echo "<br>Got non Accepted StatusCode `" . $response->getStatusCode() .  "` From Kivra Api: " . Message::toString($response);
            return false;
        }

        return (array) json_decode($response->getBody()->getContents(), true);
    }
}
