<?php

namespace DeployHuman\kivra\Dataclass\Content\Options;

use DeployHuman\kivra\Enum\PaymentOptionType;
use DeployHuman\kivra\Helper;

class Option
{
    protected string $due_date;

    protected string $amount;

    protected PaymentOptionType $type;

    protected string $reference;

    protected string $title;

    protected string $description;

    protected Icon $icon;

    /**
     * This is a class which is used to create multiple payment option under the PaymentMultipleOptions class
     */
    public function __construct()
    {
    }

    /**
     * Date when this option is due.
     */
    public function setDueDate(string $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date ?? null;
    }

    /**
     * The payment amount for this option. A positive number.
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount ?? null;
    }

    /**
     * Type of format for the reference
     */
    public function setType(PaymentOptionType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?PaymentOptionType
    {
        return $this->type ?? null;
    }

    /**
     * The reference number used for paying. This can be maximum 25 characters long.
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference ?? '';
    }

    /**
     * Title for this option
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * Optional description for this option
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * Optional icon for this option
     */
    public function setIcon(Icon $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Checks both this and the icon data for validity.
     */
    public function isValid(): bool
    {
        if (isset($this->icon)) {
            if (! $this->icon->isValid()) {
                return false;
            }
        }

        return ! in_array(null, array_values($this->toArray()));
    }

    public function toArray(): array
    {
        $returnarray = [];
        Helper::addIfNotEmpty($returnarray, 'due_date', $this->due_date);
        Helper::addIfNotEmpty($returnarray, 'amount', $this->amount);
        Helper::addIfNotEmpty($returnarray, 'type', $this->type->value);
        Helper::addIfNotEmpty($returnarray, 'reference', $this->reference);
        Helper::addIfNotEmpty($returnarray, 'title', $this->title);
        Helper::addIfNotEmpty($returnarray, 'description', $this->description);
        Helper::addIfNotEmpty($returnarray, 'icon', $this->icon);

        return $returnarray;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
