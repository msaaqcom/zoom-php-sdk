<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum MeetingRecurringType: int
{
    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
}
