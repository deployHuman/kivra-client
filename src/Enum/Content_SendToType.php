<?php

namespace DeployHuman\kivra\Enum;

enum Content_SendToType: string
{
    /**
     * The recipient is a person.
     */
    case SSN = 'SSN';

    /**
     * The recipient is a company.
     */
    case VAT_NUMBER = 'VAT_NUMBER';
}
