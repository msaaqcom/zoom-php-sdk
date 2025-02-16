<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Clients;

interface ClientInterface
{
    public function createToken($code = null): array;

    public function refreshToken($refreshToken = null): array;

    public function getAccessToken(): ?string;

    public function getRefreshToken(): ?string;
}
