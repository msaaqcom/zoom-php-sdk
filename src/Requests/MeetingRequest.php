<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Exception\UnauthorizedException;
use Msaaq\Zoom\Models\Meeting;
use Msaaq\Zoom\Models\Webinar;
use Msaaq\Zoom\Support\HttpClient;
use Msaaq\Zoom\Support\Pagination;

class MeetingRequest implements Request
{
    public string $resource = 'meetings';

    public string $model = Meeting::class;

    public function __construct(
        public PendingRequest $http,
        public string $userId,
    ) {}

    /**
     * @return Meeting[]|Webinar[]|Pagination
     *
     * @throws UnauthorizedException
     */
    public function all(array $payload = []): Pagination
    {
        $response = $this->http->get("/users/{$this->userId}/{$this->resource}", $payload);

        HttpClient::throwOnError($response);

        return new Pagination(
            response: $response->json(),
            request: $this,
            collectionKey: $this->resource,
            model: $this->model
        );
    }

    public function get(
        ?int $meetingId,
        ?string $occurrenceId = null,
        array $payload = []
    ): Meeting|Webinar {
        $response = $this->http->get("/{$this->resource}/{$meetingId}?occurrence_id=$occurrenceId", [
            'show_previous_occurrences' => true,
            ...$payload,
        ]);

        HttpClient::throwOnError($response);

        return new $this->model($response->json());
    }

    public function recordings(
        ?string $meetingId,
        array $payload = []
    ) {
        $response = $this->http->get("/{$this->resource}/{$meetingId}/recordings", $payload);

        HttpClient::throwOnError($response);

        return $response->json();
    }

    public function create(Meeting|Webinar $meeting): Meeting|Webinar
    {
        $response = $this->http->post("/users/{$this->userId}/{$this->resource}", $meeting->toArray());

        HttpClient::throwOnError($response);

        return new $this->model($response->json());
    }

    public function update(
        string|int $meetingId,
        Meeting|Webinar $meeting,
        ?string $occurrenceId = null
    ): Meeting|Webinar {
        $response = $this->http
            ->patch("/{$this->resource}/{$meetingId}?occurrence_id=$occurrenceId", $meeting->toArray());

        HttpClient::throwOnError($response);

        return $this->get($meetingId, $occurrenceId);
    }

    public function delete(string|int $meetingId, ?string $occurrenceId = null, array $payload = []): bool
    {
        $response = $this->http->delete("/{$this->resource}/{$meetingId}", [
            ...$payload,
            'occurrence_id' => $occurrenceId,
        ]);

        HttpClient::throwOnError($response);

        return $response->status() == 204;
    }

    public function past(string|int $meetingId): PastMeetingRequest
    {
        return new PastMeetingRequest($this->http, $meetingId, $this->resource);
    }

    public function reports(?string $meetingId): MeetingReportRequest
    {
        return new MeetingReportRequest($this->http, $meetingId, $this->resource);
    }

    public function registrants(?string $meetingId): RegistrantRequest
    {
        return new RegistrantRequest($this->http, $meetingId, $this->resource);
    }
}
