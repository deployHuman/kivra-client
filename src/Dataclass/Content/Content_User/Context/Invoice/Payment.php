<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice;

use DeployHuman\kivra\Enum\BankPaymentType;
use DeployHuman\kivra\Enum\PaymentOptionType;
use DeployHuman\kivra\Validation;

/**
 * This is a class which is used to create a single payment
 */
class Payment
{
    protected bool $payable;

    protected string $currency;

    protected string $due_date;

    protected string $total_owed;

    protected PaymentOptionType $type;

    protected BankPaymentType $method;

    protected string $account;

    protected string $reference;

    protected bool $variable_amount = false;

    protected string $min_amount;

    /**
     * Toggles whether this content should be payable through Kivra´s payment platform
     */
    public function setPayable(bool $payable): self
    {
        $this->payable = $payable;

        return $this;
    }

    public function getPayable(): bool|null
    {
        return $this->payable ?? null;
    }

    /**
     * Currency used in specifying ´total_owed´
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency(): string|null
    {
        return $this->currency ?? null;
    }

    /**
     * Date when this Invoice is due.
     *
     * @param  string  $due_date ISO8601 date format
     */
    public function setDueDate(string $due_date): self
    {
        $this->due_date = $due_date ?? null;

        return $this;
    }

    public function getDueDate(): string|null
    {
        return $this->due_date ?? null;
    }

    /**
     * The total amount owed according to the invoice.
     * If payable equals true this must be a non negative number that`s greater than "0"
     */
    public function setTotal_owed(string $total_owed): self
    {
        $this->total_owed = $total_owed;

        return $this;
    }

    public function getTotal_owed(): string|null
    {
        return $this->total_owed ?? null;
    }

    /**
     * Enum: "SE_OCR" "TENANT_REF"
     * Type of format for the reference
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
     * Use Enum, represents int 1 or 2
     */
    public function setMethod(BankPaymentType $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod(): BankPaymentType|null
    {
        return $this->method ?? null;
    }

    /**
     * Tenant`s account number
     * where to transfer money
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAccount(): string|null
    {
        return $this->account ?? null;
    }

    /**
     * The reference number used for paying.
     * This can be maximum 25 characters long.
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference(): string|null
    {
        return $this->reference ?? null;
    }

    /**
     * Toggles whether this content should be payable through Kivra´s payment platform
     */
    public function setVariableAmount(bool $variable_amount): self
    {
        $this->variable_amount = $variable_amount;

        return $this;
    }

    public function getVariableAmount(): bool|null
    {
        return $this->variable_amount ?? null;
    }

    /**
     * The minimum amount that can be paid when variable_amount equals true.
     * If variable_amount equals true this must be a non negative number that`s greater than "0"
     *
     * Note that this is a soft limit, so whenever variable_amount is true the user will be able to choose freely the amount to be paid,
     *  but it may be warned if the amount paid is inferior to min_amount.
     *  min_amount must be greater than "0" and less than "total_owed".
     */
    public function setMinAmount(string $min_amount): self
    {
        $this->min_amount = $min_amount;

        return $this;
    }

    public function getMinAmount(): string|null
    {
        return $this->min_amount ?? null;
    }

    public function isValid(): bool
    {
        if ($this->payable && $this->total_owed < 0) {
            return false;
        }

        if ($this->variable_amount && ($this->min_amount < 0 || $this->min_amount > $this->total_owed)) {
            return false;
        }

        if (Validation::ISO8601Date($this->due_date) == false) {
            return false;
        }

        return ! in_array(null, array_values([
            'payable' => $this->payable ?? null,
            'currency' => $this->currency ?? null,
            'due_date' => $this->due_date ?? null,
            'total_owed' => $this->total_owed ?? null,
            'type' => $this->type->value ?? null,
            'method' => $this->method->value ?? null,
            'account' => $this->account ?? null,
            'reference' => $this->reference ?? null,
        ]));
    }

    public function toArray(): array
    {
        return [
            'payable' => $this->payable ?? null,
            'currency' => $this->currency ?? null,
            'due_date' => $this->due_date ?? null,
            'total_owed' => $this->total_owed ?? null,
            'type' => $this->type->value ?? null,
            'method' => $this->method->value ?? null,
            'account' => $this->account ?? null,
            'reference' => $this->reference ?? null,
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
