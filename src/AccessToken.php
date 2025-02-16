<?php

declare(strict_types=1);

namespace Msaaq\Zoom;

use Msaaq\Zoom\Clients\ClientInterface;
use Msaaq\Zoom\Exception\UnauthorizedException;

class AccessToken
{
    private ?array $tokenResponse = null;

    public function __construct(
        private readonly ClientInterface $client,
        private ?string $accessToken = null,
        private ?string $refreshToken = null,
        private bool $generateTokenIfNotValid = true,
        private mixed $onTokenChange = null,
    ) {
        if (! $accessToken) {
            $this->tokenResponse = $this->client->createToken();

            $this->tokenChanged();
        } elseif ($this->generateTokenIfNotValid && $this->isTokenExpired()) {
            $this->tokenResponse = $this->client->refreshToken($refreshToken);

            $this->tokenChanged();
        }
    }

    private function tokenChanged(): void
    {
        $this->accessToken = $this->client->getAccessToken();
        $this->refreshToken = $this->client->getRefreshToken();

        if ($callback = $this->onTokenChange) {
            $callback($this->tokenResponse);
        }
    }

    public function isTokenExpired(): bool
    {
        try {
            (new Zoom($this))->user()->get();

            return false;
        } catch (UnauthorizedException $exception) {
            return true;
        }
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
