<?php

namespace DeployHuman\kivra\Enum;

enum ApiMethod: string
{
    case GET = 'GET';

    case POST = 'POST';

    case PUT = 'PUT';

    case DELETE = 'DELETE';

    case PATCH = 'PATCH';

    case OPTIONS = 'OPTIONS';
}
