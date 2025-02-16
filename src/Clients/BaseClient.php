<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Clients;

use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Support\HttpClient;

class BaseClient
{
    public array $response = [];

    protected string $clientId;

    protected string $clientSecret;

    protected ?string $redirectUri = null;

    protected ?string $accessToken = null;

    protected ?string $refreshToken = null;

    public function http(): PendingRequest
    {
        return HttpClient::http()
            ->withToken($this->basicToken(), 'Basic')
            ->asForm();
    }

    protected function basicToken(): string
    {
        return base64_encode(sprintf(
            '%s:%s',
            trim($this->clientId),
            trim($this->clientSecret)
        ));
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
