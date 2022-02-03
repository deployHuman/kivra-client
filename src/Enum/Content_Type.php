<?php

namespace DeployHuman\kivra\Enum;


enum Content_Type: string
{
    /**
     * indicating that the content is an information letter. This is the default type for all non-payable content
     */
    case letter = 'letter';

    /**
     * indicating that the content is a salary specification.
     */
    case letter_sallery = 'letter.sallery';

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

    /**
     *  indicating that the content is an invoice, a reminder for a previously unpaid invoice. 
     * The invoice might include late fees and other differences compared to the original invoice. 
     * A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
     */
    case invoice_reminder = 'invoice.reminder';

    /**
     *  indicating that the content is an invoice or payment plan from a debt collection company.
     *  The invoice might include fees such as interest and reminder fees.
     *  A valid "payment" object needs to be provided and the "payable" attribute must be set to "true". 
     *  This content types enables long due dates with a longer notification scheme
     */
    case invoice_debtcompaign = 'invoice.debtcompaign';

    /**
     * indicating that the content is a booking/appointement.
     */
    case booking = 'booking';
}
