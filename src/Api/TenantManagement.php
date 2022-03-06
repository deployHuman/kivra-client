<?php

namespace DeployHuman\kivra\Api;

use DeployHuman\kivra\ApiClient;
use DeployHuman\kivra\Dataclass\CompanyId;
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
        $querys = isset($QueryParamOrgnr) ? ['query' => ['orgnr' => $QueryParamOrgnr]] : [];
        return $this->get('/v2/tenant', $querys);
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
        return $this->post('/v2/tenant/request_access', ['json' => ['vat_number' => $vat_number]]);
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
    public function callAPIRequestAccessStatus(string $requestKey): response|false
    {
        return $this->get('/v2/tenant/request_access/' . $requestKey);
    }

    /**
     * Tenant information
     * Get detailed information on a tenant
     * @url /v2/tenant/{tenantKey}
     * @documentation http://developer.kivra.com/#operation/Get%20information%20on%20tenant
     * 
     * @param string $tenantkey The unique Key for a Tenant
     * @return Response|false
     */
    public function apiGetInformationOnTenant(string $tenantkey): Response|false
    {
        return $this->get('/v2/tenant/' . $tenantkey);
    }

    /**
     * Create Tenant.
     * Creation of tenants via API allows clients to create new tenants in an efficient manner. The created tenant is automatically added to the client scope. The client needs to re-authenticate to have the new scope in effect.
     * Note: Creation of tenants via API is only allowed in certain specific cases and its usage needs to be regulated in the business relationship between the sender party and Kivra.
     *
     * @param CompanyId $companyObjects
     * @return Response
     */
    public function apiCreateTenant(CompanyId $companyObjects): Response
    {
        $response = $this->post('/v2/tenant', $companyObjects->toArray());
        if (in_array($response->getStatusCode(), [200, 201])) $this->refreshAccessToken(true);
        return $response;
    }
}
