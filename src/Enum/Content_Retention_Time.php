<?php

namespace DeployHuman\kivra\Enum;

enum Content_Retention_Time: string
{
    /**
     * The content will be deleted after the specified time, in days
     */
    case time_30 = '30';

    /**
     * The content will be deleted after the specified time, in days
     */
    case time_390 = '390';
}
