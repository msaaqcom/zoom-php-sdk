<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Msaaq\Zoom\Models\Enums\MeetingType;

class Meeting extends BaseMeeting
{
    public MeetingType $type;
}
