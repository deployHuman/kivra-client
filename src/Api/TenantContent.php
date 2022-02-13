<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Dataclass\Content\Content_Company\Content_Company;
use DeployHuman\kivra\Dataclass\Content\Content_User\Content_User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;

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
     * @return response|false
     */
    public function callAPIListUsers(string $tenantKey, string $ssn = null, string $include = null): response|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.user";
        $this->basicTokenCheck($scopeNeeded);
        $logclient = $this->config->getLogger();
        $logclient->debug(__CLASS__ . "::" . __FUNCTION__);
        $querys = [];
        $querys = isset($ssn) ? array_merge($querys, ['ssn' => $ssn]) : $querys;
        $querys = isset($include) ? array_merge($querys, ['include' => $include]) : $querys;
        $client = $this->getClient();
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
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $logclient->error(__CLASS__ . "::" . __FUNCTION__ . " - ClientException: " . $e->getMessage() . ' Request: ' . $SentRequest . ' Description: ' . $desc);
            return false;
        }
        if ($this->config->getDebug()) {
            $logclient->debug(__CLASS__ . "::" . __FUNCTION__ . " - Response body: " . $response->getBody()->getContents());
            $response->getBody()->rewind();
        }
        return $response;
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
     * @return Response|false
     */
    public function callAPIMatchUsers(string $tenantkey, array $ssns): Response|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.usermatch";
        $this->basicTokenCheck($scopeNeeded);
        $logclient = $this->config->getLogger();
        $logclient->debug(__CLASS__ . "::" . __FUNCTION__);
        $client = $this->getClient();
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
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $logclient->error(__CLASS__ . "::" . __FUNCTION__ . " - ClientException: " . $e->getMessage() . ' Request: ' . $SentRequest . ' Description: ' . $desc);
            return false;
        }
        if ($this->config->getDebug()) {
            $logclient->debug(__CLASS__ . "::" . __FUNCTION__ . " - Response body: " . $response->getBody()->getContents());
            $response->getBody()->rewind();
        }
        return $response;
    }


    /**
     * List available recipient companies for a tenant.
     * This resource is used to list all or search for companies that eligible for receiving Content from the specific Tenant. The response is a JSON list of Objects containing the Companies key and Vat Number.
     * If a search is done and the Company doesn´t exist or have Opt-ed out of receiving Content from the Tenant an empty list will be returned.
     * @url /v1/tenant/{tenantKey}/company
     * @documentation http://developer.kivra.com/#operation/List%20Companies
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @return Response|false
     */
    public function callAPIListCompanies(string $tenantkey, string $vat_number = null): Response|false
    {
        $scopeNeeded = "get:kivra.v1.tenant.{tenantKey}.company";
        $this->basicTokenCheck($scopeNeeded);
        $logclient = $this->config->getLogger();
        $logclient->debug(__CLASS__ . "::" . __FUNCTION__);
        $querys = [];
        $querys = isset($vat_number) ? array_merge($querys, ['vat_number' => $vat_number]) : $querys;
        $client = $this->getClient();
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
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $logclient->error(__CLASS__ . "::" . __FUNCTION__ . " - ClientException: " . $e->getMessage() . ' Request: ' . $SentRequest . ' Description: ' . $desc);
            return false;
        }
        if ($this->config->getDebug()) {
            $logclient->debug(__CLASS__ . "::" . __FUNCTION__ . " - Response body: " . $response->getBody()->getContents());
            $response->getBody()->rewind();
        }
        return $response;
    }


    /**
     * Send content to a recipient (user or company).
     * Metadata is data that Kivra needs to send the Content to the right User. It may also determine how a User can interact with the Content.
     * @url /v1/tenant/{tenantKey}/company
     * @documentation http://developer.kivra.com/#operation/Send%20content
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @param Content_Company|Content_User $content
     * @return Response|false
     */
    public function callAPISendContent(string $tenantkey, Content_User|Content_Company $contentData): Response|false
    {

        $scopeNeeded = "post:kivra.v1.tenant.{tenantKey}.content";
        $this->basicTokenCheck($scopeNeeded);
        $logclient = $this->config->getLogger();
        $logclient->debug(__CLASS__ . "::" . __FUNCTION__);
        $client = $this->getClient();
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
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $logclient->error(__CLASS__ . "::" . __FUNCTION__ . " - ClientException: " . $e->getMessage() . ' Request: ' . $SentRequest . ' Description: ' . $desc);
            return false;
        }
        if ($this->config->getDebug()) {
            $logclient->debug(__CLASS__ . "::" . __FUNCTION__ . " - Response body: " . $response->getBody()->getContents());
            $response->getBody()->rewind();
        }
        return $response;
    }
}
