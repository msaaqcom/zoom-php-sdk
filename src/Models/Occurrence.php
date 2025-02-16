<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

class Occurrence extends Model
{
    public string $occurrence_id;

    public string $start_time;

    public int $duration;

    public string $status;

    /*
     * Total number of occurrence
     */
    public int $total;

    /**
     * Current occurrence number
     */
    public int $current;
}
