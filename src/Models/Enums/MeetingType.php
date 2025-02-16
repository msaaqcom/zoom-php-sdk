<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum MeetingType: int
{
    case INSTANT = 1;
    case SCHEDULE = 2;
    case RECURRING_NO_FIXED_TIME = 3;
    case RECURRING_FIXED_TIME = 8;

}
