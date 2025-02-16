<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

use Illuminate\Support\Collection;
use Msaaq\Zoom\Models\Enums\MeetingStatus;
use Msaaq\Zoom\Models\Enums\MeetingType;
use Msaaq\Zoom\Models\Enums\WebinarType;
use Msaaq\Zoom\Support\HttpClient;

class BaseMeeting extends Model
{
    public string $uuid;

    public int $id;

    public string $host_id;

    public string $host_email;

    public string $assistant_id;

    public string $topic;

    public ?MeetingStatus $status = null;

    public string $timezone;

    public int $duration;

    public ?string $start_time = null;

    public string $agenda;

    public string $created_at;

    public string $start_url;

    public string $join_url;

    public ?string $password = null;

    /**
     * @var Occurrence[]|Collection
     */
    public $occurrences;

    public MeetingSetting $settings;

    public Recurrence $recurrence;

    public bool $pre_schedule;

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function setSettings(MeetingSetting $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function setType(MeetingType|WebinarType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setRecurrence(Recurrence $recurrence): self
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    public function setStartTime($startTime): self
    {
        $this->start_time = $startTime;

        return $this;
    }

    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function setTimezone($timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function setAgenda(string $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isRecurring(): bool
    {
        return (bool) $this->occurrences?->count();
    }

    public function __setOccurrences(?array $occurrences)
    {
        if (! $occurrences) {
            return collect();
        }

        $total = count($occurrences);
        $current = 1;

        foreach ($occurrences as $key => $occurrence) {
            $occurrence = new Occurrence($occurrence);
            $occurrence->total = $total;
            $occurrence->current = $current++;

            $occurrences[$key] = $occurrence;
        }

        return collect($occurrences);
    }

    public function getFirstStartingTime(): ?string
    {
        if ($this->start_time) {
            return $this->start_time;
        }

        if ($this->isRecurring()) {
            return $this->occurrences->first()->start_time;
        }

        return null;
    }

    public function registrants(?string $meetingId, array $payload = [])
    {
        $response = $this->http->get("/meetings/{$this->id}/registrants", $payload);

        HttpClient::throwOnError($response);

        return $response->json();
    }
}
