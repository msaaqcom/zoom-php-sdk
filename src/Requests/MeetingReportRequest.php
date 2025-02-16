<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Exception\UnauthorizedException;
use Msaaq\Zoom\Models\Participant;
use Msaaq\Zoom\Support\HttpClient;
use Msaaq\Zoom\Support\Pagination;

class MeetingReportRequest implements Request
{
    public function __construct(
        public PendingRequest $http,
        public string $meetingId,
        public string $resource = 'meetings'
    ) {}

    /**
     * @return Participant[]|Pagination
     *
     * @throws UnauthorizedException
     */
    public function participants(array $payload = []): Pagination
    {
        $response = $this->http->get("/report/{$this->resource}/{$this->meetingId}/participants", [
            ...$payload,
            'include_fields' => 'registrant_id',
        ]);

        HttpClient::throwOnError($response);

        return new Pagination(
            response: $response->json(),
            request: $this,
            collectionKey: 'participants',
            method: 'participants',
            model: Participant::class
        );
    }

    public function detail(array $payload = []): array
    {
        $response = $this->http->get("/report/{$this->resource}/{$this->meetingId}", $payload);

        HttpClient::throwOnError($response);

        return $response->json();
    }
}
