<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice\Options;

use DeployHuman\kivra\Enum\PaymentOptionType;

class Option

{

    protected string $due_date;
    protected float $amount;
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
     *
     * @param string $due_date
     * @return Option
     */
    public function setDueDate(string $due_date): self
    {
        $this->due_date = $due_date;
        return $this;
    }

    public function getDueDate(): string|null
    {
        return $this->due_date ?? null;
    }

    /**
     * The payment amount for this option. A positive number.
     *
     * @param float $amount
     * @return Option
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount(): float|null
    {
        return $this->amount ?? null;
    }

    /**
     * Type of format for the reference
     *
     * @param PaymentOptionType $type
     * @return Option
     */
    public function setType(PaymentOptionType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): PaymentOptionType|null
    {
        return $this->type ?? null;
    }

    /**
     * The reference number used for paying. This can be maximum 25 characters long.
     *
     * @param string $reference
     * @return Option
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
     *
     * @param string $title
     * @return Option
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
     *
     * @param string $description
     * @return Option
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
     *
     * @param Icon $icon
     * @return Option
     */
    public function setIcon(Icon $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Checks both this and the icon data for validity.
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        if (isset($this->icon)) {
            if (!$this->icon->isValid()) {
                return false;
            }
        }
        return !in_array(null, array_values($this->toArray()));
    }

    public function toArray(): array
    {
        return [
            'due_date' => $this->due_date ?? null,
            'amount' => $this->amount ?? null,
            'type' => $this->type ?? null,
            'reference' => $this->reference ?? null,
            'title' => $this->title ?? null,
            'description' => $this->description ?? null,
            'icon' => $this->icon ?? null,
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
