<?php

namespace DeployHuman\kivra\Dataclass;

class ErrorCodes
{
    public string $long_message;

    public string $message;

    public int $code;

    public int $code_group;

    protected array $errors = [
        [
            'long_message' => 'The request payload does not pass required validation',
            'message' => 'Request validation failed',
            'code' => '40000',
            'code_group' => '4',
        ], [
            'long_message' => 'The request was invalid',
            'message' => 'Invalid Request',
            'code' => '40001',
            'code_group' => '4',
        ], [
            'long_message' => 'The redirect_uri does not match the registered redirect_uri',
            'message' => 'Redirect URI Mismatch',
            'code' => '40002',
            'code_group' => '4',
        ], [
            'long_message' => 'An invalid or insufficient scope was used',
            'message' => 'Invalid Scope',
            'code' => '40003',
            'code_group' => '4',
        ], [
            'long_message' => 'This user is already registered',
            'message' => 'Already registered',
            'code' => '40004',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to phonenumber not meeting the required format',
            'message' => 'Error in phonenumber',
            'code' => '40005',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to password not meeting the required format',
            'message' => 'Error in password',
            'code' => '40006',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to email not meeting the required format',
            'message' => 'Error in email',
            'code' => '40007',
            'code_group' => '4',
        ], [
            'long_message' => 'The JSON payload was malformed. The client should not resend the same payload without first correcting the erroneous JSON payload.',
            'message' => 'Unprocessable Entity',
            'code' => '40008',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to SSN not meeting the required format.',
            'message' => 'Error in SSN',
            'code' => '40009',
            'code_group' => '4',
        ], [
            'long_message' => 'The action parameter was not supplied or invalid.',
            'message' => 'No action supplied or invalid',
            'code' => '40010',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to SSN and/or mobile failed extended validation.',
            'message' => 'Failed Extended Validation',
            'code' => '40011',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to type field failing extended validation.',
            'message' => 'Error in type',
            'code' => '40012',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to user have already an accepted sendrequest',
            'message' => 'Sendrequest already accepted',
            'code' => '40013',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid Status',
            'message' => 'Invalid Status',
            'code' => '40014',
            'code_group' => '4',
        ], [
            'long_message' => 'Invalid access_token provided',
            'message' => 'Invalid Token',
            'code' => '40015',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid State',
            'message' => 'Invalid State',
            'code' => '40016',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid campaigns',
            'message' => 'Invalid Campaigns',
            'code' => '40017',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to Company ID not meeting the required format',
            'message' => 'Error in Company ID',
            'code' => '40018',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid files',
            'message' => 'Invalid Files',
            'code' => '40019',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid data for parties',
            'message' => 'Invalid Parties',
            'code' => '40020',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to invalid bank account',
            'message' => 'Invalid Bank Account',
            'code' => '40021',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to missing postal address',
            'message' => 'Missing Postal Address',
            'code' => '40022',
            'code_group' => '4',
        ], [
            'long_message' => 'Invalid contact_info provided',
            'message' => 'Invalid Contact Info',
            'code' => '40023',
            'code_group' => '4',
        ], [
            'long_message' => 'Neither \'ssn\' nor \'vat_number\' has been specified as receiver',
            'message' => 'No Receiver Specified',
            'code' => '40024',
            'code_group' => '4',
        ], [
            'long_message' => 'The provided OTP is invalid or expired',
            'message' => 'Invalid OTP',
            'code' => '40025',
            'code_group' => '4',
        ], [
            'long_message' => 'Signup signature is invalid or signup data is altered',
            'message' => 'Signature verification failed',
            'code' => '40027',
            'code_group' => '4',
        ], [
            'long_message' => 'The JSON payload was malformed. Only JSON objects are supported',
            'message' => 'JSON payload was not an object',
            'code' => '40026',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to a data file was provided using an unsupported type',
            'message' => 'UnsupportedFileType',
            'code' => '40028',
            'code_group' => '4',
        ], [
            'long_message' => 'The request can\'t be processed due to the icon format not being compliant with the requirements',
            'message' => 'IconSizeError',
            'code' => '40030',
            'code_group' => '4',
        ], [
            'long_message' => 'Only PNG with alpha channel (PNG32) is allowed',
            'message' => 'PNG Missing Alpha Channel',
            'code' => '40031',
            'code_group' => '4',
        ], [
            'long_message' => 'No user exists with provided SSN',
            'message' => 'User doesn\'t exist',
            'code' => '40032',
            'code_group' => '4',
        ], [
            'long_message' => 'One or more query parameters were invalid, e.g. bad format, not supported',
            'message' => 'Invalid query parameters',
            'code' => '40033',
            'code_group' => '4',
        ], [
            'long_message' => 'One or more required headers were missing in the request',
            'message' => 'Required header missing',
            'code' => '40035',
            'code_group' => '4',
        ], [
            'long_message' => 'The resource you are trying to create already exists',
            'message' => 'Resource Already Exists',
            'code' => '40036',
            'code_group' => '4',
        ], [
            'long_message' => 'All agreement parties must have a unique SSN',
            'message' => 'Agreement parties are not unique',
            'code' => '40037',
            'code_group' => '4',
        ], [
            'long_message' => 'Its either not following the format https://subdomain.domain.tld/* or is not yet whitelisted in our system. Please contact us for support',
            'message' => 'The provided destination URL does not meet our requirements',
            'code' => '40038',
            'code_group' => '4',
        ], [
            'long_message' => 'Cannot publish or update a canceled campaign',
            'message' => 'Cannot publish or update a canceled campaign',
            'code' => '40039',
            'code_group' => '4',
        ], [
            'long_message' => 'Cannot publish campaign without an image',
            'message' => 'Campaign must have an image',
            'code' => '40040',
            'code_group' => '4',
        ], [
            'long_message' => 'Pay date is in the past, too far in the future or not a bank day',
            'message' => 'Invalid pay date',
            'code' => '40041',
            'code_group' => '4',
        ], [
            'long_message' => 'The amount is out of range',
            'message' => 'Invalid amount',
            'code' => '40042',
            'code_group' => '4',
        ], [
            'long_message' => 'This preference is not supported',
            'message' => 'Invalid user preference',
            'code' => '40043',
            'code_group' => '4',
        ], [
            'long_message' => 'The OCR did not pass validation',
            'message' => 'Invalid OCR',
            'code' => '40044',
            'code_group' => '4',
        ], [
            'long_message' => 'The option does not exist',
            'message' => 'Invalid option id',
            'code' => '40045',
            'code_group' => '4',
        ], [
            'long_message' => 'The supplied agreement PDF was invalid. Please check that the file is a valid PDF',
            'message' => 'Invalid PDF',
            'code' => '40047',
            'code_group' => '4',
        ], [
            'long_message' => 'The barcode could not be created due to invalid barcode type',
            'message' => 'Invalid barcode data',
            'code' => '40098',
            'code_group' => '4',
        ], [
            'long_message' => 'Supplied credentials was invalid',
            'message' => 'Unauthorized',
            'code' => '40100',
            'code_group' => '4',
        ], [
            'long_message' => 'The resource owner or authorization server denied the request',
            'message' => 'Access Denied',
            'code' => '40101',
            'code_group' => '4',
        ], [
            'long_message' => 'The client is not authorized to request an authorization code using this method.',
            'message' => 'Unauthorized Client',
            'code' => '40102',
            'code_group' => '4',
        ], [
            'long_message' => 'The provided authorization grant (e.g. authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.',
            'message' => 'Invalid Grant',
            'code' => '40103',
            'code_group' => '4',
        ], [
            'long_message' => 'Client authentication failed (e.g. unknown client, no client authentication included, or unsupported authentication method).',
            'message' => 'Invalid Client',
            'code' => '40104',
            'code_group' => '4',
        ], [
            'long_message' => 'No sendrequest exists between sender and receiver, or sendrequest is not accepted.',
            'message' => 'Invalid Sendrequest',
            'code' => '40105',
            'code_group' => '4',
        ], [
            'long_message' => 'This email adress is already in use and can not be used.',
            'message' => 'Email in use',
            'code' => '40106',
            'code_group' => '4',
        ], [
            'long_message' => 'This phone number is already in use and can not be used.',
            'message' => 'Phone number is already in use',
            'code' => '40107',
            'code_group' => '4',
        ], [
            'long_message' => 'The Registration Code is invalid or no Sendrequest exists or has been expired.',
            'message' => 'Registration Code or Sendrequest Invalid',
            'code' => '40108',
            'code_group' => '4',
        ], [
            'long_message' => 'An already existing Sendrequest exists or is blocked.',
            'message' => 'Sendrequest or Share already exists or is blocked',
            'code' => '40109',
            'code_group' => '4',
        ], [
            'long_message' => 'No print integration exists for the tenant',
            'message' => 'Missing Print Integration',
            'code' => '40110',
            'code_group' => '4',
        ], [
            'long_message' => 'The provided OTP is invalid or expired',
            'message' => 'Access Denied: Invalid OTP',
            'code' => '40111',
            'code_group' => '4',
        ], [
            'long_message' => 'The requested resource requires a greater security score than the one associated with the current login method',
            'message' => 'Access Denied: Insufficient Security Score',
            'code' => '40112',
            'code_group' => '4',
        ], [
            'long_message' => 'Contact us to enroll, make sure to include your id',
            'message' => 'User does not exist with provided id',
            'code' => '40113',
            'code_group' => '4',
        ], [
            'long_message' => 'Access was denied to the given resource, authenticating will make no difference',
            'message' => 'Forbidden',
            'code' => '40300',
            'code_group' => '4',
        ], [
            'long_message' => 'The resource was not found at the given URI at this time',
            'message' => 'Not found',
            'code' => '40400',
            'code_group' => '4',
        ], [
            'long_message' => 'The method specified in is not allowed for the resource at the requested URI',
            'message' => 'Method Not Allowed',
            'code' => '40500',
            'code_group' => '4',
        ], [
            'long_message' => 'The Accept Header contains a non valid or unknown Content-Type',
            'message' => 'Invalid Accept Header',
            'code' => '40601',
            'code_group' => '4',
        ], [
            'long_message' => 'An attempt was made to create an object that already exists',
            'message' => 'Conflict',
            'code' => '40915',
            'code_group' => '4',
        ], [
            'long_message' => 'Too many requests within this timespan have been made. Please try again later',
            'message' => 'Too Many Requests',
            'code' => '42900',
            'code_group' => '4',
        ],
    ];

    public function setError(int $errorcode)
    {
        foreach ($this->errors as $error) {
            if ($error['code'] == $errorcode) {
                $this->code_group = $error['code_group'];
                $this->code = $error['code'];
                $this->message = $error['message'];
                $this->long_message = $error['long_message'];

                return;
            }
        }
    }
}
