<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Msaaq\Zoom\Exception\MissingScopeException;
use Msaaq\Zoom\Exception\MissingWebinarPlanException;
use Msaaq\Zoom\Exception\NotFoundException;
use Msaaq\Zoom\Exception\UnauthorizedException;
use Msaaq\Zoom\Models\MeetingInstance;
use Msaaq\Zoom\Models\Participant;
use Msaaq\Zoom\Support\HttpClient;
use Msaaq\Zoom\Support\Pagination;

class PastMeetingRequest implements Request
{
    public function __construct(
        public PendingRequest $http,
        public ?string $meetingId,
        public string $resource = 'meetings'
    ) {}

    /**
     * @throws UnauthorizedException
     * @throws MissingScopeException
     * @throws MissingWebinarPlanException
     * @throws NotFoundException
     */
    public function participants(array $payload = []): Pagination
    {
        $response = $this->http->get("/past_{$this->resource}/{$this->meetingId}/participants", $payload);

        HttpClient::throwOnError($response);

        return new Pagination(
            response: $response->json(),
            request: $this,
            collectionKey: 'participants',
            model: Participant::class
        );
    }

    /**
     * @return MeetingInstance[]|Collection
     *
     * @throws MissingScopeException
     * @throws MissingWebinarPlanException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function instances(array $payload = [])
    {
        $response = $this->http->get("/past_{$this->resource}/{$this->meetingId}/instances", $payload);

        HttpClient::throwOnError($response);

        return collect($response->json($this->resource))->map(fn ($array) => new MeetingInstance($array));
    }

    public function details(array $payload = []): ?array
    {
        $response = $this->http->get("/past_{$this->resource}/{$this->meetingId}", $payload);

        HttpClient::throwOnError($response);

        return $response->json();
    }
}
