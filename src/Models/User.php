<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Msaaq\Zoom\Models\Enums\UserStatus;
use Msaaq\Zoom\Models\Enums\UserType;

class User extends Model
{
    public string $id;

    public string $first_name;

    public string $last_name;

    public string $display_name;

    public string $email;

    /**
     * User's plan type:
     */
    public UserType $type;

    public string $role_name;

    public ?string $role_id;

    public int $pmi;

    public bool $use_pmi;

    public string $personal_meeting_url;

    public string $timezone;

    public int $verified;

    public string $dept;

    public string $created_at;

    public string $last_login_time;

    public string $pic_url;

    public string $cms_user_id;

    public string $jid;

    public array $group_ids;

    public array $im_group_ids;

    public string $account_id;

    public string $language;

    public string $phone_country;

    public string $phone_number;

    public UserStatus $status;

    public string $job_title;

    public string $location;

    public array $login_types;

    public int $account_number;

    public string $cluster;

    public string $user_created_at;

    public function isActive(): bool
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isLicensed(): bool
    {
        return $this->type == UserType::LICENSED;
    }
}
