<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Support;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Msaaq\Zoom\AccessToken;
use Msaaq\Zoom\Exception\MissingScopeException;
use Msaaq\Zoom\Exception\MissingWebinarPlanException;
use Msaaq\Zoom\Exception\NotFoundException;
use Msaaq\Zoom\Exception\UnauthorizedException;

class HttpClient
{
    const AUTHORIZE_USER_URL = 'https://zoom.us/oauth/authorize';

    const OAUTH_TOKEN_URL = 'https://zoom.us/oauth/token';

    const OAUTH_REVOKE_TOKEN_URL = 'https://zoom.us/oauth/revoke';

    const API_BASE_URL = 'https://api.zoom.us/v2';

    public static function http(?AccessToken $token = null): PendingRequest
    {
        $http = Http::baseUrl(self::API_BASE_URL);

        if ($token) {
            $http->withToken($token->getAccessToken());
        }

        return $http;
    }

    /**
     * @throws MissingScopeException
     * @throws MissingWebinarPlanException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws Exception
     */
    public static function throwOnError(Response $response): void
    {
        if ($response->failed()) {
            $message = $response->json('message') ?? $response->json('reason');
            $code = $response->json('code') ?? $response->json('error');
            $message = "$message - Code: $code";

            if ($response->status() == 401) {
                throw new UnauthorizedException($message, $code);
            }

            if ($response->status() == 404) {
                throw new NotFoundException($message, $code);
            }

            if (str_contains($message, 'Invalid access token, does not contain scopes')) {
                throw new MissingScopeException($message, $code);
            }

            if (str_contains($message, 'Webinar plan is missing')) {
                throw new MissingWebinarPlanException($message, $code);
            }

            logger()->error($message, ['response' => $response->json()]);

            throw new Exception($message, (int) $code);
        }
    }
}
