<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum UserStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
