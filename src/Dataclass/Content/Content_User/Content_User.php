<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User;

use DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice\PaymentMultipleOptions;
use DeployHuman\kivra\Dataclass\Content\Files\File;
use DeployHuman\kivra\Enum\Content_Retention_Time;
use DeployHuman\kivra\Enum\User_Content_Type;
use DeployHuman\kivra\Validation;

class Content_User
{
    protected string $ssn;

    protected string $subject;

    protected string $generated_at;

    protected User_Content_Type $type;

    protected bool $retain = false;

    protected Content_Retention_Time $retention_time;

    protected string $tenant_info;

    protected array $parts;

    protected PaymentMultipleOptions $payment_options;

    public function __construct()
    {
    }

    /**
     * User's unique SSN, according to the YYYYMMDDnnnn format
     */
    public function setSsn(string $ssn): self
    {
        $this->ssn = $ssn;

        return $this;
    }

    public function getSsn()
    {
        return $this->ssn;
    }

    /**
     * The subject/title will be visibile in the Recipients Inbox.
     * Keep the subject short and concise (i.e. up to 30-35 characters) to make sure that is fully visible on most screen sizes.
     * Avoid using personal and sensitive information in the subject.
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
     * Optional attribute which denotes when a specific Content was generated at the tenant/integrator’s site. 
     * The attribute will be used for sorting in the Kivra user interface, which makes it possible for a tenant or integrator to control the sorting.
     */
    public function setGeneratedAt(string $generated_at): self
    {
        $this->generated_at = $generated_at;

        return $this;
    }

    public function getGeneratedAt()
    {
        return $this->generated_at;
    }

    /**
     * Optional attribute providing information about the type of content being sent. 
     * The type of a content may influence how the user interacts with the content and how the user is notified about the content.
     */
    public function setType(User_Content_Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Boolean denoting if Kivra should try and retain this Content if it can´t be delivered.
     * Default false.
     * Please note that retain must never be set to true for payable content.
     */
    public function setRetain(bool $retain): self
    {
        $this->retain = $retain;

        return $this;
    }

    public function getRetain()
    {
        return $this->retain;
    }

    /**
     * How long to retain a Content. Supported values: "30" and "390"
     */
    public function setRetantion_time(Content_Retention_Time $retention_time): self
    {
        $this->retention_time = $retention_time;

        return $this;
    }

    public function getRetention_Time()
    {
        return $this->retention_time;
    }

    /**
     * An arbitrary string defined by the tenant, used to group content for administrative tasks
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
     */
    public function addPart(File $SinglePart): self
    {
        $this->parts[] = $SinglePart;

        return $this;
    }

    public function getParts()
    {
        return $this->parts;
    }

    public function setPaymentOptions(PaymentMultipleOptions $payment_options): self
    {
        $this->payment_options = $payment_options;

        return $this;
    }

    /**
     * Tenant´s own Invoice Reference
     */
    public function getPaymentOptions(): PaymentMultipleOptions|null
    {
        return $this->payment_options ?? null;
    }

    public function isValid(): bool
    {
        if (!Validation::personnummer($this->ssn)) {
            return false;
        }

        return !in_array(null, array_values([
            'ssn' => $this->ssn,
            'subject' => $this->subject,
            'parts' => $this->parts,
            'payment_multiple_options' => $this->payment_options,
        ]));
    }

    public function toArray(): array
    {

        if (isset($this->parts)) {
            $parts = [];
            foreach ($this->parts as $file) {
                $parts[] = $file->toArray();
            }
        }

        $returnarray = [];
        !empty($this->ssn) ? $returnarray['ssn'] = $this->ssn : null;
        !empty($this->subject) ? $returnarray['subject'] = $this->subject : null;
        !empty($this->type) ? ($returnarray['type'] = $this->type->value) : null;
        !empty($this->retain) ? ($returnarray['retain'] = $this->retain) : null;
        !empty($this->retention_time) ? ($returnarray['retention_time'] = $this->retention_time) : null;
        !empty($this->tenant_info) ? ($returnarray['tenant_info'] = $this->tenant_info) : null;
        !empty($this->parts) ? ($returnarray['parts'] = $parts) : null;
        !empty($this->payment_options) ? ($returnarray['payment_multiple_options'] = $this->payment_options->toArray()) : null;

        return $returnarray;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
