<?php

namespace DeployHuman\kivra\Enum;

enum PaymentOptionType: string
{
    /**
     * Real OCR calculated by the system.
     */
    case SE_OCR = 'SE_OCR';

    /**
     * Usage Free text as reference
     */
    case TENANT_REF = 'TENANT_REF';
}
