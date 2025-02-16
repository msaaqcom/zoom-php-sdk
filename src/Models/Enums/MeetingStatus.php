<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum MeetingStatus: string
{
    case WAITING = 'waiting';
    case STARTED = 'started';
}
