<?php

namespace DeployHuman\kivra\Dataclass\Content;

use DeployHuman\kivra\Dataclass\Content\Options\Option;
use DeployHuman\kivra\Enum\BankPaymentType;
use DeployHuman\kivra\Helper;

/**
 * This is a class which is used to create multiple payment option under the PaymentMultipleOptions class
 */
class PaymentMultipleOptions
{
    protected bool $payable;

    protected BankPaymentType $method;

    protected string $account;

    protected string $currency = 'SEK';

    protected array $options;

    public function setPayable(bool $payable): self
    {
        $this->payable = $payable;

        return $this;
    }

    public function getPayable(): ?bool
    {
        return $this->payable ?? null;
    }

    /**
     * The payment method for this option.
     */
    public function setMethod(BankPaymentType $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod(): ?BankPaymentType
    {
        return $this->method ?? null;
    }

    /**
     * The account number for this option.
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account ?? null;
    }

    /**
     * The currency for this option.
     * Value: "SEK"
     * Currency used in specifying amount. In Sweden, only SEK is allowed.
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency ?? null;
    }

    public function addOption(Option $option): self
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * Checks both this and all options and subsets to see if they are valid.
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
        $returnArray = [];
        Helper::addIfNotEmpty($returnArray, 'payable', $this->payable);
        Helper::addIfNotEmpty($returnArray, 'method', $this->method->value);
        Helper::addIfNotEmpty($returnArray, 'account', $this->account);
        Helper::addIfNotEmpty($returnArray, 'currency', $this->currency);
        Helper::addIfNotEmpty($returnArray, 'options', $this->options);

        return $returnArray;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
