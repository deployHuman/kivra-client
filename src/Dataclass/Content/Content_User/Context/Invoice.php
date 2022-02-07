<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User\Context;

use DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice\Payment;
use DeployHuman\kivra\Dataclass\PaymentMultipleOptions;

class Invoice
{

    protected PaymentMultipleOptions|Payment $payment_options;
    protected string $invoice_reference;


    public function __construct()
    {
    }

    public function setPaymentOptions(PaymentMultipleOptions|Payment $payment_options): self
    {
        $this->payment_options = $payment_options;
        return $this;
    }

    /**
     * Tenant´s own Invoice Reference
     *
     * @return PaymentMultipleOptions|Payment|null
     */
    public function getPaymentOptions(): PaymentMultipleOptions|Payment|null
    {
        return $this->payment_options ?? null;
    }

    public function setInvoiceReference(string $invoice_reference): self
    {
        $this->invoice_reference = $invoice_reference;
        return $this;
    }

    public function getInvoiceReference(): string|null
    {
        return $this->invoice_reference ?? null;
    }



    public function isValid(): bool
    {
        if (!$this->payment_options->isValid()) {
            return false;
        }
        return !in_array(null, array_values($this->toArray()));
    }

    public function toArray(): array
    {
        foreach ($this->payment_options as $key => $value) {
            $return_payment_options[] = $value->toArray();
        }
        return [
            'payment_options' => $return_payment_options ?? null,
            'invoice_reference' => $this->getInvoiceReference()
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
