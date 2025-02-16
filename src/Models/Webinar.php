<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Msaaq\Zoom\Models\Enums\WebinarType;

class Webinar extends BaseMeeting
{
    public WebinarType $type;
}
