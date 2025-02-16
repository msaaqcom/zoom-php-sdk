<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models\Enums;

enum AutoRecording: string
{
    case LOCAL = 'local';
    case CLOUD = 'cloud';
    case DISABLED = 'none';
}
