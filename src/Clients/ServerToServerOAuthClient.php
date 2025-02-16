<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Clients;

use Msaaq\Zoom\Support\HttpClient;

class ServerToServerOAuthClient extends BaseClient implements ClientInterface
{
    public array $response = [];

    public function __construct(
        string $clientId,
        string $clientSecret,
        private string $accountId,
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function createToken($code = null): array
    {
        $response = $this->http()->post(HttpClient::OAUTH_TOKEN_URL, [
            'grant_type' => 'account_credentials',
            'account_id' => $this->accountId,
        ]);

        HttpClient::throwOnError($response);

        $this->accessToken = $response->json('access_token');
        $this->refreshToken = $response->json('refresh_token');

        $this->response = $response->json();

        return $this->response;
    }

    public function refreshToken($refreshToken = null): array
    {
        return $this->createToken();
    }
}
