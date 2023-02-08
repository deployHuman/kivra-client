<?php

namespace DeployHuman\kivra\Enum;

enum RequestAccessTenantStatus: string
{
    case REJECTED = 'REJECTED';

    case PENDING = 'PENDING';

    case ACCEPTED = 'ACCEPTED';
}
