<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Dataclass\Content\Content_Company\Content_Company;
use DeployHuman\kivra\Dataclass\Content\Content_User\Content_User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Message;


class TenantContent extends ApiClient
{

    /**
     * List available recipient users for a tenant.
     * This resource is used to list all or search for users that are eligible for receiving Content from the specific Tenant. The response is a JSON list of Objects containing the User's key and SSN. The diffId contained in the response header can be used to fetch added/removed users in subsequent requests to the /v1/tenant/{tenantKey}/user/diff/{diffId} endpoint.
     * If a search is done with a query string and the user doesn´t exist or has Opt-ed out from receiving Content from the Tenant, an empty list is returned.
     * Access to this resource might be enabled or disabled via agreement. To match a given list of users, please use the `usermatch` resource.
     * @url /v1/tenant/{tenantKey}/user //missmatch in documentation
     * @documentation http://developer.kivra.com/#operation/List%20Users
     * 
     * @param ?string $ssn Example: ssn=191212121212 - Perform a search to see if specific Users are available
     * @param ?string $include Value: "ssn" Example: include=ssn - List of fields that are returned for each user object
     * @return array|false
     */
    public function callAPIListUsers(string $tenantKey, string $ssn = null, string $include = null): array|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.user";
        $this->basicTokenCheck($scopeNeeded);
        $querys = [];
        $querys = isset($ssn) ? array_merge($querys, ['ssn' => $ssn]) : $querys;
        $querys = isset($include) ? array_merge($querys, ['include' => $include]) : $querys;
        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
        try {
            $response = $client->request(
                "GET",
                '/v1/tenant/' . $tenantKey . '/user',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
                    'query' => $querys
                ]
            );
        } catch (ClientException $e) {
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }

        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            if ($this->config->getDebug()) echo "<br>Got non Accepted StatusCode `" . $response->getStatusCode() .  "` From Kivra Api: " . Message::toString($response);
            return false;
        }
        return (array) json_decode($response->getBody()->getContents(), true);
    }



    /**
     * Match a list of recipient users for a specific tenant.
     * This resource is used to match a list of users to check that they are eligible for receiving Content from the specific Tenant. The request contains a list of SSNs to be matched, and the response is a filtered list containing only the SSNs that are eligible to receive content from the tenant.
     * If none of the provided SSNs are eligible to receive content from this tenant, an empty list will be returned.
     * @url /v2/tenant/{tenantKey}/usermatch
     * @documentation http://developer.kivra.com/#operation/Match%20Users
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @param array $ssns A list of SSNs to be matched, in string format
     * @return array|false
     */
    public function callAPIMatchUsers(string $tenantkey, array $ssns): array|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.usermatch";
        $this->basicTokenCheck($scopeNeeded);

        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
        try {
            $response = $client->request(
                "POST",
                '/v1/tenant/' . $tenantkey . '/usermatch',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
                    'json' => [
                        'ssns' => $ssns
                    ]
                ]
            );
        } catch (ClientException $e) {
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }
        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            return false;
        }
        return (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
    }


    /**
     * List available recipient companies for a tenant.
     * This resource is used to list all or search for companies that eligible for receiving Content from the specific Tenant. The response is a JSON list of Objects containing the Companies key and Vat Number.
     * If a search is done and the Company doesn´t exist or have Opt-ed out of receiving Content from the Tenant an empty list will be returned.
     * @url /v1/tenant/{tenantKey}/company
     * @documentation http://developer.kivra.com/#operation/List%20Companies
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @return array|false
     */
    public function callAPIListCompanies(string $tenantkey, string $vat_number = null): array|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.company";
        $this->basicTokenCheck($scopeNeeded);
        $querys = [];
        $querys = isset($vat_number) ? array_merge($querys, ['vat_number' => $vat_number]) : $querys;
        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
        try {
            $response = $client->request(
                "POST",
                '/v1/tenant/' . $tenantkey . '/company',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
                    'query' => $querys

                ]
            );
        } catch (ClientException $e) {
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }
        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            return false;
        }
        return (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
    }


    /**
     * Send content to a recipient (user or company).
     * Metadata is data that Kivra needs to send the Content to the right User. It may also determine how a User can interact with the Content.
     * @url /v1/tenant/{tenantKey}/company
     * @documentation http://developer.kivra.com/#operation/Send%20content
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @param Content_Company|Content_User $content
     * @return array|false
     */
    public function callAPISendContent(string $tenantkey, Content_User|Content_Company $contentData): array|false
    {

        $scopeNeeded = "post:kivra.v1.tenant.{tenantKey}.content";
        $this->basicTokenCheck($scopeNeeded);
        $querys = [];
        $querys = isset($vat_number) ? array_merge($querys, ['vat_number' => $vat_number]) : $querys;
        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
        try {
            $response = $client->request(
                "POST",
                '/v1/tenant/' . $tenantkey . '/content',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
                    'json' => $contentData->toArray()

                ]
            );
        } catch (ClientException $e) {
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }
        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            return false;
        }
        return (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
    }
}
