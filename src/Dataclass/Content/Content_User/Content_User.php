<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User;

use DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice;
use DeployHuman\kivra\Dataclass\Content\Content_User\Context\Booking;
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
    protected array $files;
    protected Booking|Invoice $context;


    public function __construct()
    {
    }


    /**
     * User's unique SSN, according to the YYYYMMDDnnnn format
     *
     * @param string $ssn
     * @return self
     */
    public function setSsn(string $ssn): self
    {
        $this->ssn =  $ssn;
        return $this;
    }

    public function getSsn()
    {
        return $this->ssn;
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
     * The date and time when the content was generated.
     *
     * @param string $generated_at
     * @return self
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
     * Optional attribute providing information about the type of content being sent. The type of a content may influence how the user interacts with the content and how the user is notified about the content. Allowed values are:
     *
     * @param User_Content_Type $type
     * @return self
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
     *
     * @param boolean $retain
     * @return self
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
     *
     * @param Content_Retention_Time $retention_time
     * @return self
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

    /**
     * Sets the main Contect of this send.
     * 
     *
     * @param Invoice|Booking $context
     * @return self
     */
    public function setContext(Invoice|Booking $context): self
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
        if (!Validation::personnummer($this->ssn)) {
            return false;
        }
        if (isset($this->context)) {
            if (!$this->context->isValid()) {
                return false;
            }
        }
        return !in_array(null, array_values([
            'ssn' => $this->ssn,
            'files' => $this->files,
            'context' => $this->context
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
        (isset($this->ssn)) ? $returnarray['ssn'] = $this->ssn : null;
        (isset($this->subject)) ? $returnarray['subject'] = $this->subject : null;
        (isset($this->generated_at)) ? $returnarray['generated_at'] = $this->generated_at : null;
        (isset($this->type)) ? $returnarray['type'] = $this->type : null;
        (isset($this->retain)) ? $returnarray['retain'] = $this->retain : null;
        (isset($this->retention_time)) ? $returnarray['retention_time'] = $this->retention_time : null;
        (isset($this->tenant_info)) ? $returnarray['tenant_info'] = $this->tenant_info : null;
        (isset($this->files)) ? $returnarray['files'] = $files : null;
        (isset($this->context)) ? $returnarray['context'] = $this->context->toArray() : null;
        return $returnarray;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
