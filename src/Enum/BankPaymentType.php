<?php

namespace DeployHuman\kivra\Enum;

enum BankPaymentType: string
{
    /**
     * This is for when the payment is over Bankgiro.
     *
     * The backed value must be sent to the api
     */
    case BankGiro = '1';

    /**
     * This is for when the payment is over Postgiro.
     *
     * The backed value must be sent to the api
     */
    case PostGiro = '2';
}
