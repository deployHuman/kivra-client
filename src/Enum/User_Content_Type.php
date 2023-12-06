<?php

namespace DeployHuman\kivra\Enum;

/**
 * Optional attribute providing information about the type of content being sent. The type of a content may influence how the user interacts with the content and how the user is notified about the content. Allowed values are:
 * "letter": indicating that the content is an information letter. This is the default type for all non-payable content.
 * "letter.salary": indicating that the content is a salary specification.
 * "letter.creditnotice": indicating that the content is a creditnotice.
 * "letter.form": indicating that the content contains a form.
 * "invoice": | indicating that the content is an invoice. A valid "payment" object needs to be provided and the "payable" attribute must be set to true. This is the default type for all payable content.
 * "invoice.reminder": | indicating that the content is an invoice, a reminder for a previously unpaid invoice. The invoice might include late fees and other differences compared to the original invoice. A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
 * "invoice.debtcampaign": | indicating that the content is an invoice or payment plan from a debt collection company. The invoice might include fees such as interest and reminder fees. A valid "payment" object needs to be provided and the "payable" attribute must be set to "true". This content types enables long due dates with a longer notification scheme
 * "invoice.renewal": | indicating that the content is not a real invoice, but an offer that is voluntary to pay for the receiver. It can be used to send an offer to renew a subscription, insurance or similar. A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
 * "invoice.debtcollection": | indicating that the content is a debt collection claim (in Swedish: "inkassokrav"). A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
 * "booking": indicating that the content is a booking/appointement.
 */
enum User_Content_Type: string
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
     * indicating that the content contains a form.
     */
    case letter_form = 'letter.form';

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
    case invoice_debtcampaign = 'invoice.debtcampaign';

    /**
     *  indicating that the content is not a real invoice, but an offer that is voluntary to pay for the receiver.
     *  It can be used to send an offer to renew a subscription, insurance or similar.
     *  A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
     */
    case invoice_renewal = 'invoice.renewal';

    /**
     *  indicating that the content is a debt collection claim (in Swedish: "inkassokrav").
     *  A valid "payment" object needs to be provided and the "payable" attribute must be set to true.
     */
    case invoice_debtcollection = 'invoice.debtcollection';

    /**
     * indicating that the content is a booking/appointement.
     */
    case booking = 'booking';
}
