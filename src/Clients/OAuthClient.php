<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Clients;

use Exception;
use Msaaq\Zoom\Support\HttpClient;

class OAuthClient extends BaseClient implements ClientInterface
{
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    public function setRedirectUri(string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function getAuthorizationUrl(): string
    {
        return sprintf('%s?%s', HttpClient::AUTHORIZE_USER_URL, http_build_query([
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
        ]));
    }

    public function createToken($code = null): array
    {
        if (! $code) {
            throw new Exception("This field 'code' is required.");
        }

        $response = $this->http()->post(HttpClient::OAUTH_TOKEN_URL, [
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);

        HttpClient::throwOnError($response);

        $this->accessToken = $response->json('access_token');
        $this->refreshToken = $response->json('refresh_token');

        return $response->json();
    }

    public function refreshToken($refreshToken = null): array
    {
        $response = $this->http()->post(HttpClient::OAUTH_TOKEN_URL, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken ?? $this->getRefreshToken(),
        ]);

        HttpClient::throwOnError($response);

        $this->accessToken = $response->json('access_token');
        $this->refreshToken = $response->json('refresh_token');

        return $response->json();
    }

    public function revokeToken($token = null): bool
    {
        return $this->http()
            ->post(HttpClient::OAUTH_REVOKE_TOKEN_URL, [
                'token' => $token ?? $this->getAccessToken(),
            ])
            ->json('status') === 'success';
    }
}
