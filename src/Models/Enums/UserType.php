<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum UserType: int
{
    case BASIC = 1;
    case LICENSED = 2;
}
