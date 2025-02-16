<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum RecurringWeeklyDays: int
{
    case SUNDAY = 1;
    case MONDAY = 2;
    case TUESDAY = 3;
    case WEDNESDAY = 4;
    case THURSDAY = 5;
    case FRIDAY = 6;
    case SATURDAY = 7;
}
