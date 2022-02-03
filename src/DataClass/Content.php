<?php

namespace DeployHuman\kivra\Dataclass;

use DeployHuman\kivra\Enum\Content_Retention_Time;
use DeployHuman\kivra\Enum\Content_SendToType;
use DeployHuman\kivra\Enum\Content_Type;

class Content
{
    protected Content_SendToType $SendToType;
    protected Content_Type $type;
    protected Content_Retention_Time $retention_time;
    protected bool $retain = false;
    protected string $SendToValue;
    protected string $subject;
    protected string $tenant_info;
    protected array $files;
    protected array $context;


    public function __construct()
    {
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getSendToType()
    {
        return $this->SendToType;
    }

    public function getSendToValue()
    {
        return $this->SendToValue;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRetain()
    {
        return $this->retain;
    }

    public function getRetention_Time()
    {
        return $this->retention_time;
    }
    public function getTenant_Info()
    {
        return $this->tenant_info;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getContext()
    {
        return $this->context;
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

    /**
     * The type of the Recipient, Company or Private
     *
     * @param Content_Type $type
     * @return self
     */
    public function setSendToType(Content_SendToType $SendToType): self
    {
        $this->SendToType = $SendToType;
        return $this;
    }

    /**
     * Unique Value of the reciver of this content.
     * 
     * Either A valid VAT-identifier, Swedish format: SE[xxxxxxxxxx]01 or,
     * User's unique SSN, according to the YYYYMMDDnnnn format
     *
     * @param string $SendToValue
     * @return self
     */
    public function setSendToValue(string $SendToValue): self
    {
        $this->SendToValue =  $SendToValue;
        return $this;
    }
    /**
     * Optional attribute providing information about the type of content being sent. The type of a content may influence how the user interacts with the content and how the user is notified about the content. Allowed values are:
     *
     * @param Content_Type $type
     * @return self
     */
    public function setType(Content_Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Boolean denoting if Kivra should try and retain this Content if it can´t be delivered. 
     * Default false. 
     * Please note that retain must never be set to true for payable content.
     *
     * @param boolean $retain
     * @return self
     */
    public function setRetain(bool $retain): self
    {
        $this->retain = $retain;
        return $this;
    }

    /**
     * How long to retain a Content. Supported values: "30" and "390"
     *
     * @param Content_Retention_Time $retention_time
     * @return self
     */
    public function setRetantion_time(Content_Retention_Time $retention_time): self
    {
        $this->retention_time = $retention_time;
        return $this;
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

    /**
     * Array of file Objects
     *
     * @param Content_File $file
     * @return self
     */
    public function addFile(Content_File $file): self
    {
        $this->files[] = $file;
        return $this;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function isValid(): bool
    {
        if (
            empty($this->SendToType) ||
            empty($this->SendToValue) ||
            empty($this->type)
        ) {
            return false;
        }
        
    }

    public function toArray(): array
    {
        return [
            'subject' => $this->subject ?? null,
            'send_to_type' => $this->SendToType ?? null,
            'send_to_value' => $this->SendToValue ?? null,
            'type' => $this->type ?? null,
            'retain' => $this->retain ?? null,
            'retention_time' => $this->retention_time ?? null,
            'tenant_info' => $this->tenant_info ?? null,
            'files' => $this->files ?? null,
            'context' => $this->context ?? null
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
