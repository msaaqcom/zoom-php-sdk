<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum WebinarType: int
{
    case SCHEDULE = 5;
    case RECURRING_NO_FIXED_TIME = 6;
    case RECURRING_FIXED_TIME = 9;
}
