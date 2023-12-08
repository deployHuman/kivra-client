<?php

namespace DeployHuman\kivra\Dataclass\Content;

use DeployHuman\kivra\Enum\Content_Retention_Time;
use DeployHuman\kivra\Enum\Content_SendToType;
use DeployHuman\kivra\Enum\Content_Type;
use DeployHuman\kivra\Helper;
use DeployHuman\kivra\Validation;

class Content
{
    protected string $ssn;

    protected string $VAT_number;

    protected string $email;

    protected Content_SendToType $send_to_type;

    protected string $subject;

    protected string $generated_at;

    protected Content_Type $type;

    protected bool $retain = false;

    protected Content_Retention_Time $retention_time;

    protected string $tenant_info;

    protected array $parts;

    protected PaymentMultipleOptions $payment_options;

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
     * User's unique VAT number, according to the SE999999999901 format
     */
    public function setVAT_Number(string $VAT_number): self
    {
        $this->VAT_number = $VAT_number;

        return $this;
    }

    public function getVAT_Number()
    {
        return $this->VAT_number;
    }

    /**
     * User's unique email address
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * The type of recipient identifier that is used to send the content to the recipient.
     * The type of recipient identifier must match the type of identifier that is used in the ssn or vat_number attribute.
     * If the type is not set, the type will be inferred from the ssn or vat_number attribute.
     */
    public function setSendToType(Content_SendToType $send_to_type): self
    {
        $this->send_to_type = $send_to_type;

        return $this;
    }

    public function getSendToType()
    {
        return $this->send_to_type;
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
    public function setType(Content_Type $type): self
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
    public function addPart(Part $SinglePart): self
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
    public function getPaymentOptions(): ?PaymentMultipleOptions
    {
        return $this->payment_options ?? null;
    }

    public function isValid(): bool
    {
        if (! isset($this->type)) {
            return false;
        }

        if (! isset($this->send_to_type)) {
            return false;
        }

        if ($this->send_to_type == Content_SendToType::SSN && ! Validation::personnummer($this->ssn)) {
            return false;
        }

        if ($this->send_to_type == Content_SendToType::VAT_NUMBER && ! Validation::vatnumber($this->VAT_number)) {
            return false;
        }

        return ! in_array(null, array_values([
            'ssn' => $this->ssn,
            'subject' => $this->subject,
            'parts' => $this->parts,
            'payment_multiple_options' => $this->payment_options,
        ]));
    }

    public function toArray(): array
    {

        $returnarray = [];
        $parts = [];

        if (! $this->isValid()) {
            return $returnarray;
        }

        if ($this->send_to_type == Content_SendToType::SSN) {
            Helper::addIfNotEmpty($returnarray, 'ssn', $this->ssn);
        } elseif ($this->send_to_type == Content_SendToType::VAT_NUMBER) {
            Helper::addIfNotEmpty($returnarray, 'vat_number', $this->VAT_number);
        } elseif ($this->send_to_type == Content_SendToType::EMAIL) {
            Helper::addIfNotEmpty($returnarray, 'email', $this->email);
        }

        Helper::addIfNotEmpty($returnarray, 'subject', $this->subject);
        Helper::addIfNotEmpty($returnarray, 'type', $this->type->value);
        Helper::addIfNotEmpty($returnarray, 'retain', $this->retain);
        Helper::addIfNotEmpty($returnarray, 'retention_time', $this->retention_time);
        Helper::addIfNotEmpty($returnarray, 'tenant_info', $this->tenant_info);
        Helper::addIfNotEmpty($returnarray, 'parts', $parts);
        Helper::addIfNotEmpty($returnarray, 'payment_multiple_options', $this->payment_options);

        return $returnarray;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
