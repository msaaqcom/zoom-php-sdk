<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum RecurrenceType: int
{
    use BaseEnum;

    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
}
