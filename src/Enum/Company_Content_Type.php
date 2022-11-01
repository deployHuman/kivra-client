<?php

namespace DeployHuman\kivra\Enum;

enum Company_Content_Type: string
{
    /**
     * indicating that the content is an information letter. This is the default type for all non-payable content
     */
    case letter = 'letter';

    /**
     * indicating that the content is a salary specification.
     */
    case letter_salary = 'letter.salary';

    /**
     * indicating that the content is a creditnotice.
     */
    case letter_creditnotice = 'letter.creditnotice';

    /**
     * indicating that the content is an invoice.
     * A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
     * This is the default type for all payable content.
     */
    case invoice = 'invoice';
}
