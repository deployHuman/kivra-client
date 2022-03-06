<?php

namespace DeployHuman\kivra\Dataclass;


class CompanyId
{

    public string $DisplayName;
    public array $company_id;

    public function __construct()
    {
    }

    public function getDisplayName(): string
    {
        return $this->DisplayName ?? '';
    }

    /**
     * Name of the Tenant, this name shows up in the Users Inbox.
     *
     * @param string $DisplayName
     * @return self
     */
    public function setDisplayName(string $DisplayName): self
    {
        $this->DisplayName = $DisplayName ?? '';
        return $this;
    }

    /**
     * adds to 	Array of objects (CompanyId).
     * 
     * @param string $company_name Legal name of Company
     * @param string $company_orgnr Vat number of Company
     * @return self
     */
    public function addCompanyId(string $company_name, string $company_orgnr): self
    {
        $this->company_id[] =  [
            'name' => $company_name,
            'orgnr' => $company_orgnr
        ];
        return $this;
    }

    public function getCompanyId(): array
    {
        return $this->company_id ?? [];
    }

    public function toArray(): array
    {
        $reurn_array = [];
        $reurn_array['name'] = $this->getDisplayName();
        $reurn_array['company_id'] = $this->getCompanyId();
        return $reurn_array;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
