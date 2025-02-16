<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum MeetingApprovalType: int
{
    /**
     * Automatically approve registration
     */
    case AUTOMATICALLY_APPROVE = 0;

    /**
     * Manually approve registration.
     */
    case MANUALLY_APPROVE = 1;

    /**
     * No registration required.
     *
     * @defulte
     */
    case NO_REGISTRATION_REQUIRED = 2;
}
