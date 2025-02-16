<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Msaaq\Zoom\Exception\UnauthorizedException;
use Msaaq\Zoom\Models\Registrant;
use Msaaq\Zoom\Support\HttpClient;
use Msaaq\Zoom\Support\Pagination;

class RegistrantRequest implements Request
{
    public function __construct(
        public PendingRequest $http,
        public ?string $meetingId,
        public string $resource = 'meetings'
    ) {}

    /**
     * @return Registrant[]|Pagination
     *
     * @throws UnauthorizedException
     */
    public function all(array $payload = []): Pagination
    {
        $response = $this->http->get("/{$this->resource}/{$this->meetingId}/registrants", $payload);

        HttpClient::throwOnError($response);

        return new Pagination(
            response: $response->json(),
            request: $this,
            collectionKey: 'registrants',
            model: Registrant::class
        );
    }

    public function get(string $registrantId, array $payload = []): Registrant
    {
        $response = $this->http->get("/{$this->resource}/{$this->meetingId}/registrants/{$registrantId}", $payload);

        HttpClient::throwOnError($response);

        return new Registrant($response->json());
    }

    public function create(Registrant $registrant, array|string|null $occurrenceIds = null): Registrant
    {
        $path = "/{$this->resource}/{$this->meetingId}/registrants";

        $occurrenceIds = trim(implode(',', Arr::wrap($occurrenceIds)));
        if ($occurrenceIds) {
            $path = "$path?occurrence_ids=$occurrenceIds";
        }

        $response = $this->http->post($path, $registrant->toArray());

        HttpClient::throwOnError($response);

        return new Registrant($response->json());
    }

    public function delete(string|int $registrantId, ?string $occurrenceId = null, array $payload = []): bool
    {
        $response = $this->http->delete("/{$this->resource}/{$this->meetingId}/registrants/{$registrantId}", [
            ...$payload,
            'occurrence_id' => $occurrenceId,
        ]);

        HttpClient::throwOnError($response);

        return $response->status() == 204;
    }
}
