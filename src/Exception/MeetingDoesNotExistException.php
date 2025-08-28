<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Exception;

class MeetingDoesNotExistException extends NotFoundException
{
    public function __construct(string $message, int $code = 3001)
    {
        parent::__construct($message, $code);
    }
}
