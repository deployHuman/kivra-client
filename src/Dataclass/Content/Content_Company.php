<?php

namespace DeployHuman\kivra\Dataclass\Content;

use DeployHuman\kivra\Dataclass\Content\Context\Invoice;
use DeployHuman\kivra\Dataclass\Content\Files\File;
use DeployHuman\kivra\Enum\Company_Content_Type;
use DeployHuman\kivra\Validation;

class Content_Company
{

    protected string $vat_number;
    protected string $subject;
    protected string $generated_at;
    protected Company_Content_Type $type;
    protected string $tenant_info;
    protected array $files;
    protected Invoice $context;


    public function __construct()
    {
    }


    /**
     * Unique Value of the reciver of this content.
     * 
     * A valid VAT-identifier, Swedish format: SE[xxxxxxxxxx]01
     *
     * @param string $vat_number
     * @return self
     */
    public function setVatNumber(string $vat_number): self
    {
        $this->vat_number =  $vat_number;
        return $this;
    }

    public function getVatNumber()
    {
        return $this->vat_number;
    }


    /**
     * This Subject/Title will be visibile in the Recipients Inbox.
     *
     * @param string $subject
     * @return self
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * Optional attribute providing information about the type of content being sent. The type of a content may influence how the user interacts with the content and how the user is notified about the content. Allowed values are:
     *
     * @param Company_Content_Type $type
     * @return self
     */
    public function setType(Company_Content_Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }


    /**
     * An arbitrary string defined by the tenant, used to group content for administrative tasks
     *
     * @param string $tenant_info
     * @return self
     */
    public function setTenant_Info(string $tenant_info): self
    {
        $this->tenant_info = $tenant_info;
        return $this;
    }


    public function getTenant_Info()
    {
        return $this->tenant_info;
    }


    /**
     * Array of file Objects
     *
     * @param File $file
     * @return self
     */
    public function addFile(File $file): self
    {
        $this->files[] = $file;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setContext(Invoice $context): self
    {
        $this->context = $context;
        return $this;
    }


    public function getContext()
    {
        return $this->context;
    }





    public function isValid(): bool
    {
        if (!Validation::orgnumber($this->vat_number)) {
            return false;
        }
        if (isset($this->context)) {
            if (!$this->context->isValid()) {
                return false;
            }
        }

        return !in_array(null, array_values([
            'vat_number' => $this->vat_number,
            'files' => $this->files,
            'context' => $this->context,
        ]));
    }

    public function toArray(): array
    {
        return [
            'vat_number' => $this->vat_number,
            'subject' => $this->subject,
            'generated_at' => $this->generated_at,
            'type' => $this->type,
            'tenant_info' => $this->tenant_info,
            'files' => $this->files,
            'context' => $this->context,
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
