<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Msaaq\Zoom\Models\Enums\RecurrenceType;
use Msaaq\Zoom\Models\Enums\RecurringWeeklyDays;

class Recurrence extends Model
{
    /**
     * Select the final date on which the meeting will recur before it is canceled.
     * Should be in UTC time, such as 2017-11-25T12:00:00Z.
     * (Cannot be used with "end_times".)
     */
    public string $end_date_time;

    /**
     * Select how many times the meeting should recur before it is canceled.
     * The default recurrence is 50 times. To support meetings recurring more than 50 times, contact Zoom support.
     * Cannot be used with "end_date_time".
     */
    public int $end_times;

    /**
     * Define the interval at which the meeting should recur.
     * For instance, if you would like to schedule a meeting that recurs every two months,
     * you must set the value of this field as 2 and the value of the type parameter as 3.
     */
    public int $repeat_interval;

    public RecurrenceType $type;

    public RecurringWeeklyDays $weekly_days;

    public int $monthly_day;

    public int $monthly_week;

    public RecurringWeeklyDays $monthly_week_day;

    public function setEndDateTime(string $end_date_time): self
    {
        $this->end_date_time = $end_date_time;

        return $this;
    }

    public function setEndTimes(int $end_times): self
    {
        $this->end_times = $end_times;

        return $this;
    }

    public function setRepeatInterval(int $repeat_interval): self
    {
        $this->repeat_interval = $repeat_interval;

        return $this;
    }

    public function setType(RecurrenceType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setWeeklyDays(RecurringWeeklyDays $weekly_days): self
    {
        $this->weekly_days = $weekly_days;

        return $this;
    }

    public function setMonthlyDay(int $monthly_day): self
    {
        $this->monthly_day = $monthly_day;

        return $this;
    }

    public function setMonthlyWeek(int $monthly_week): self
    {
        $this->monthly_week = $monthly_week;

        return $this;
    }

    public function setMonthlyWeekDay(RecurringWeeklyDays $monthly_week_day): self
    {
        $this->monthly_week_day = $monthly_week_day;

        return $this;
    }
}
