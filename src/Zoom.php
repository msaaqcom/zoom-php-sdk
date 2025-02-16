<?php

declare(strict_types=1);

namespace Msaaq\Zoom;

use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Requests\UserRequest;
use Msaaq\Zoom\Support\HttpClient;

class Zoom
{
    public PendingRequest $http;

    public function __construct(public AccessToken $token)
    {
        $this->http = HttpClient::http($token);
    }

    public function user(): UserRequest
    {
        return new UserRequest($this->http);
    }
}
