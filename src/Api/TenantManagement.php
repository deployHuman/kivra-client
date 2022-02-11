<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;

class TenantManagement extends ApiClient
{

    /**
     * List Tenants
     * Lists all tenants that are manageable by the current client
     * @url /v2/tenant[?orgnnr=SE556840226601]
     * @documentation http://developer.kivra.com/#operation/List%20all%20tenants%20accessible%20to%20the%20client
     * 
     * @param ?string $QueryParamOrgnr Optional Perform a search to see if a specific Company is available
     * @return Response|false
     */
    public function callAPIListAllTenantsAccessibleToTheClient(string $QueryParamOrgnr = null): Response|false
    {
        $scopeNeeded = "get:kivra.v2.tenant";
        $this->basicTokenCheck($scopeNeeded);
        $querys = isset($QueryParamOrgnr) ? ['orgnr' => $QueryParamOrgnr] : [];
        $client = $this->getClient();
        try {
            $response = $client->request(
                "GET",
                '/v2/tenant',
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
            $this->setAPIError('ClientException', 'Description: ' . $desc . ' Request: ' . $SentRequest);
            return false;
        }
        return $response;
    }


    /**
     * Request access to a tenant.
     * Request access to an existing tenant that is outside the client scope. Typically this request follows an unsuccessful attempt to create a tenant that resulted in a conflict error (error 409).
     * The meaning of the conflict error is that a tenant is already associated to a company_id including the same orgnr as in the tenant that the client attempted to post, and the tenant who owns the orgnr is outside the scope for the client.
     * In Kivra it is allowed to have several different flows on the same tenant, as for instance one flow for invoices and one flow for payment slips. As this flows could be managed by different clients, we need a mechanism to allow sharing a tenant between clients. The request_access endpoint provide this functionality. As the request may be granted (or denied) asynchronously, after a successfull call to request_access the client will need to poll the request until it becomes accepted or rejected.
     * As allowing access to a new tenant requires modification of the scope for the client, an authorization must be performed once the request has been accepted, to retrieve an access token with the new scope.
     * `If the client posts a new identical request (requesting the same OrgNr for the same client), the same object will be returned with an updated status.`
     * @url /v2/tenant/request_access
     * @documentation http://developer.kivra.com/#operation/Request%20access
     * 
     * @param string $vat_number the VAT number of the company you want Tenant Access to
     * @return Response|false
     */
    public function callAPIRequestAccess(string $vat_number): Response|false
    {
        $scopeNeeded = "post:kivra.v2.tenant.request_access";
        $this->basicTokenCheck($scopeNeeded);

        $client = $this->getClient();
        try {
            $response = $client->request(
                "POST",
                '/v2/tenant/request_access',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
                    'json' => [
                        'vat_number' => $vat_number
                    ]

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


    /**
     * Status of an access request.
     * Gets the updated status for a request generate using the request_access endpoint.
     * @url /v2/tenant/request_access/{requestKey}
     * @documentation http://developer.kivra.com/#operation/Request%20access%20status
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @param string $requestKey 
     * @return array|false
     */
    public function callAPIRequestAccessStatus(string $requestKey): array|false
    {
        $scopeNeeded = "get:kivra.v2.tenant.request_access.{requestKey}";
        $this->basicTokenCheck($scopeNeeded);

        $client = $this->getClient();
        try {
            $response = $client->request(
                "GET",
                '/v2/tenant/request_access/' . $requestKey,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ]
                ]
            );
        } catch (ClientException $e) {
            $SentRequest = $e->getRequest() ? Message::toString($e->getRequest()) : '';
            $desc = $e->hasResponse() ? Message::toString($e->getResponse()) : '';
            $this->setAPIError('ClientException', 'Description: ' . $desc . ' Request: ' . $SentRequest);
            return false;
        }
        $AcceptedStatus = [200];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            return false;
        }
        return (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
    }
    //https://sender.sandbox-api.kivra.com/v2/tenant/request_access/1631171803455ef65506fc41959bf684bc0809a2bc

    /**
     * Tenant information
     * Get detailed information on a tenant
     * @url /v2/tenant/{tenantKey}
     * @documentation http://developer.kivra.com/#operation/Get%20information%20on%20tenant
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @return Response|false
     */
    public function callAPIGetInformationOnTenant(string $tenantkey): Response|false
    {
        $scopeNeeded = "get:kivra.v2.tenant.{tenantKey}";
        $this->basicTokenCheck($scopeNeeded);
        $client = $this->getClient();
        try {
            $response = $client->request(
                "GET",
                '/v2/tenant/' . $tenantkey,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    ],
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
