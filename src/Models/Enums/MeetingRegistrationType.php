<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum MeetingRegistrationType: int
{
    /**
     * Attendees register once and can attend any meeting occurrence.
     */
    case REGISTER_ONCE_TO_ATTEND_ALL = 1;

    /**
     * Attendees must register for each meeting occurrence.
     */
    case MUST_REGISTER_FOR_EACH_OCCURRENCE = 2;

    /**
     * Attendees register once and can select one or more meeting occurrences to attend.
     */
    case REGISTER_ONCE_AND_SELECT_OCCURRENCES_TO_ATTEND = 3;
}
