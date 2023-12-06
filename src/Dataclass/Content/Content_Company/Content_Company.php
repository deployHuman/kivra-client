<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_Company;

use DeployHuman\kivra\Dataclass\Content\Content_Company\Context\Invoice;
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

    /**
     * Content that can be sent to a Company, See `Company_Content_Type` for more information
     */
    public function __construct()
    {
    }

    /**
     * Unique Value of the reciver of this content.
     *
     * A valid VAT-identifier, Swedish format: SE[xxxxxxxxxx]01
     * Will not set vat number if not valid.
     */
    public function setVatNumber(string $vat_number): self
    {
        if (! Validation::vatnumber($vat_number)) {
            return $this;
        }
        $this->vat_number = $vat_number;

        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vat_number ?? null;
    }

    /**
     * This Subject/Title will be visibile in the Recipients Inbox.
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject ?? null;
    }

    /**
     * Optional attribute providing information about the type of content being sent. The type of a content may influence how the user interacts with the content and how the user is notified about the content. Allowed values are:
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
     */
    public function setTenant_Info(string $tenant_info): self
    {
        $this->tenant_info = $tenant_info;

        return $this;
    }

    public function getTenant_Info(): ?string
    {
        return $this->tenant_info ?? null;
    }

    /**
     * Array of file Objects
     */
    public function addFile(File $file): self
    {
        $this->files[] = $file;

        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files ?? null;
    }

    public function setContext(Invoice $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context ?? null;
    }

    public function isValid(): bool
    {
        if (! Validation::vatnumber($this->vat_number)) {
            return false;
        }
        if (isset($this->context)) {
            if (! $this->context->isValid()) {
                return false;
            }
        }

        return ! in_array(null, array_values([
            'vat_number' => $this->vat_number,
            'files' => $this->files,
            'context' => $this->context,
        ]));
    }

    public function toArray(): array
    {
        if (isset($this->files)) {
            $files = [];
            foreach ($this->files as $file) {
                $files[] = $file->toArray();
            }
        }
        $returnarray = [];
        (isset($this->vat_number)) ? $returnarray['vat_number'] = $this->vat_number : null;
        (isset($this->subject)) ? $returnarray['subject'] = $this->subject : null;
        (isset($this->generated_at)) ? $returnarray['generated_at'] = $this->generated_at : null;
        (isset($this->type)) ? $returnarray['type'] = $this->type : null;
        (isset($this->tenant_info)) ? $returnarray['tenant_info'] = $this->tenant_info : null;
        (isset($this->files)) ? $returnarray['files'] = $files : null;
        (isset($this->context)) ? $returnarray['context'] = $this->context->toArray() : null;

        return $returnarray;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
