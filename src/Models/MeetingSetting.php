<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Msaaq\Zoom\Models\Enums\AutoRecording;
use Msaaq\Zoom\Models\Enums\MeetingApprovalType;
use Msaaq\Zoom\Models\Enums\MeetingRegistrationType;

class MeetingSetting extends Model
{
    public array $additional_data_center_regions;

    public bool $allow_multiple_devices;

    public string $alternative_hosts;

    public bool $alternative_hosts_email_notification;

    public array $approved_or_denied_countries_or_regions;

    public string $audio;

    public string $audio_conference_info;

    public string $authentication_domains;

    public $authentication_exception;

    public $authentication_option;

    public AutoRecording $auto_recording;

    public array $breakout_room;

    public int $calendar_type;

    public string $contact_email;

    public string $contact_name;

    public bool $email_notification;

    public string $encryption_type;

    public bool $focus_mode;

    public array $global_dial_in_countries;

    public bool $host_video;

    public int $jbh_time;

    public bool $join_before_host;

    public array $language_interpretation;

    public bool $mute_upon_entry;

    public bool $participant_video;

    public bool $private_meeting;

    public bool $registrants_confirmation_email;

    public bool $registrants_email_notification;

    public MeetingApprovalType $approval_type;

    public MeetingRegistrationType $registration_type;

    public bool $close_registration;

    public bool $show_share_button;

    public bool $use_pmi;

    public bool $waiting_room;

    public bool $watermark;

    public bool $host_save_video_order;

    public bool $alternative_host_update_polls;

    public function setApprovalType(MeetingApprovalType $type): self
    {
        $this->approval_type = $type;

        return $this;
    }

    public function setRegistrationType(MeetingRegistrationType $type): self
    {
        $this->registration_type = $type;

        return $this;
    }

    public function setAutoRecording(AutoRecording $autoRecording): self
    {
        $this->auto_recording = $autoRecording;

        return $this;
    }

    public function setMuteUponEntry(bool $mute): self
    {
        $this->mute_upon_entry = $mute;

        return $this;
    }

    public function setAllowMultipleDevices(bool $allow): self
    {
        $this->allow_multiple_devices = $allow;

        return $this;
    }
}
