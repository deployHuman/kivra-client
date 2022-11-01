<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice;

use DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice\Options\Option;
use DeployHuman\kivra\Enum\BankPaymentType;

/**
 * This is a class which is used to create multiple payment option under the PaymentMultipleOptions class
 */
class PaymentMultipleOptions
{
    protected bool $payable;

    protected BankPaymentType $method;

    protected string $account;

    protected string $currency;

    protected array $options;

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
     * The payment method for this option.
     *
     * @param  BankPaymentType  $method
     * @return PaymentMultipleOptions
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
     * The account number for this option.
     *
     * @param  string  $account
     * @return PaymentMultipleOptions
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
     * The currency for this option.
     *
     * @param  string  $currency
     * @return PaymentMultipleOptions
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

    public function addOption(Option $option): self
    {
        if ($option->isValid()) {
            $this->options[] = $option;
        }

        return $this;
    }

    /**
     * Checks both this and all options and subsets to see if they are valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (isset($this->options)) {
            foreach ($this->options as $key => $value) {
                if (! $value->isValid()) {
                    return false;
                }
            }
        }

        return ! in_array(null, array_values($this->toArray()));
    }

    public function toArray(): array
    {
        return [
            'payable' => $this->payable ?? null,
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
