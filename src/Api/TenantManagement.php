<?php

namespace DeployHuman\kivra\Api;

use DateTime;
use DeployHuman\kivra\ApiClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Message;


class TenantManagement extends ApiClient
{

    /**
     * List Tenants
     * Lists all tenants that are manageable by the current client
     * @url /v2/tenant[?orgnnr=SE556840226601]
     * @documentation http://developer.kivra.com/#operation/List%20all%20tenants%20accessible%20to%20the%20client
     * 
     * @param ?string $QueryParamOrgnr Optional Perform a search to see if a specific Company is available
     * @return array|false
     */
    public function callAPIListAllTenantsAccessibleToTheClient(string $QueryParamOrgnr = null): array|false
    {
        $scopeNeeded = "get:kivra.v2.tenant";
        $this->basicTokenCheck($scopeNeeded);
        $querys = isset($QueryParamOrgnr) ? ['orgnr' => $QueryParamOrgnr] : [];

        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
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
     * Request access to a tenant.
     * Request access to an existing tenant that is outside the client scope. Typically this request follows an unsuccessful attempt to create a tenant that resulted in a conflict error (error 409).
     * The meaning of the conflict error is that a tenant is already associated to a company_id including the same orgnr as in the tenant that the client attempted to post, and the tenant who owns the orgnr is outside the scope for the client.
     * In Kivra it is allowed to have several different flows on the same tenant, as for instance one flow for invoices and one flow for payment slips. As this flows could be managed by different clients, we need a mechanism to allow sharing a tenant between clients. The request_access endpoint provide this functionality. As the request may be granted (or denied) asynchronously, after a successfull call to request_access the client will need to poll the request until it becomes accepted or rejected.
     * As allowing access to a new tenant requires modification of the scope for the client, an authorization must be performed once the request has been accepted, to retrieve an access token with the new scope.
     * `If the client posts a new identical request (requesting the same OrgNr for the same client), the same object will be returned with an updated status.`
     * @url /v2/tenant/request_access
     * @documentation http://developer.kivra.com/#operation/Request%20access
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @param string $vat_number the VAT number of the company you want Tenant Access to
     * @return array|false
     */
    public function callAPIRequestAccess(string $vat_number): array|false
    {
        $scopeNeeded = "post:kivra.v2.tenant.request_access";
        $this->basicTokenCheck($scopeNeeded);

        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
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
            $desc = ($e->hasResponse()) ? Message::toString($e->getResponse()) : Message::toString($e->getRequest());
            $this->setAPIError('ClientException', $desc);
            return false;
        }
        $AcceptedStatus = [201];
        if (!in_array($response->getStatusCode(), $AcceptedStatus)) {
            $this->setAPIError('Non Accepted StatusCode `' . $response->getStatusCode() . '`',  Message::toString($response));
            return false;
        }
        $returnarray = (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
        // ["kivra-objkey"]=> array(1) { [0]=> string(42) "1631171803455ef65506fc41959bf684bc0809a2bc" }
        // ["location"]=> array(1) { [0]=> string(104) "https://sender.sandbox-api.kivra.com/v2/tenant/request_access/1631171803455ef65506fc41959bf684bc0809a2bc"
        $returnarray['requestKey'] = $response->getHeader('kivra-objkey'); //This is so we dont need to return the whole response obj
        return $returnarray;
        //returns the field 'client_id' too which is not in the documentation
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

        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
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
    //https://sender.sandbox-api.kivra.com/v2/tenant/request_access/1631171803455ef65506fc41959bf684bc0809a2bc

    /**
     * Tenant information
     * Get detailed information on a tenant
     * @url /v2/tenant/{tenantKey}
     * @documentation http://developer.kivra.com/#operation/Get%20information%20on%20tenant
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @return array|false
     */
    public function callAPIGetInformationOnTenant(string $tenantkey): array|false
    {
        $scopeNeeded = "get:kivra.v2.tenant.{tenantKey}";
        $this->basicTokenCheck($scopeNeeded);
        $client = new GuzzleClient(["base_uri" => $this->config->getBaseUrl(), 'debug' => $this->config->getDebug()]);
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
        return (array) $this->cleanUpEmptyFields(json_decode($response->getBody()->getContents(), true));
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
}
