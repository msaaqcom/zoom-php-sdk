<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Requests;

use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Models\User;
use Msaaq\Zoom\Support\HttpClient;

class UserRequest
{
    private ?User $user = null;

    public function __construct(public PendingRequest $http) {}

    public function get(): User
    {
        if ($this->user) {
            return $this->user;
        }

        $response = $this->http->get('/users/me');

        HttpClient::throwOnError($response);

        $this->user = new User($response->json());

        return $this->user;
    }

    public function getId(): string
    {
        $this->get();

        return $this->user->id;
    }

    public function meetings(): MeetingRequest
    {
        return new MeetingRequest($this->http, $this->getId());
    }

    public function webinars(): WebinarRequest
    {
        return new WebinarRequest($this->http, $this->getId());
    }
}
