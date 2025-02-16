<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

class Participant extends Model
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $user_id = null;

    public ?string $registrant_id = null;

    public ?string $user_email = null;

    public ?string $join_time = null;

    public ?string $leave_time = null;

    public ?int $duration = null;

    public ?bool $failover = null;

    public ?string $status = null;

    public ?string $customer_key = null;

    public ?string $bo_mtg_id = null;

    public ?string $participant_user_id = null;
}
