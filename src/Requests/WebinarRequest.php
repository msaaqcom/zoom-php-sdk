<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Msaaq\Zoom\Models\Webinar;

class WebinarRequest extends MeetingRequest
{
    public string $resource = 'webinars';

    public string $model = Webinar::class;
}
