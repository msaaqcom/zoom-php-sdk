<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Support;

use Exception;
use Msaaq\Zoom\AccessToken;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Msaaq\Zoom\Exception\NotFoundException;
use Msaaq\Zoom\Exception\MissingScopeException;
use Msaaq\Zoom\Exception\UnauthorizedException;
use Msaaq\Zoom\Exception\WebinarIsOverException;
use Msaaq\Zoom\Exception\MissingWebinarPlanException;
use Msaaq\Zoom\Exception\MeetingDoesNotExistException;

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

            // Not every Zoom error body carries message/code — validation failures in particular
            // answer with a different shape. Falling through with an empty string produced
            // exceptions reading " - Code: " with code 0, which no caller can branch on and no
            // amount of log reading can diagnose. Keep the raw body as the message instead.
            if ($message === null && $code === null) {
                $message = trim((string) $response->body()) ?: "HTTP {$response->status()}";
            } else {
                $message = "$message - Code: $code";
            }

            if ($response->status() == 401) {
                throw new UnauthorizedException($message, $code);
            }

            if ($response->status() == 404) {
                switch ((int) $code) {
                    case 3001:
                        throw new MeetingDoesNotExistException($message);

                    default:
                        throw new NotFoundException($message, $code);
                }
            }

            if (str_contains($message, 'Invalid access token, does not contain scopes')) {
                throw new MissingScopeException($message, $code);
            }

            if (str_contains($message, 'Webinar plan is missing')) {
                throw new MissingWebinarPlanException($message, $code);
            }

            if (str_contains($message, 'The webinar is over')) {
                throw new WebinarIsOverException($message, $code);
            }

            logger()->error($message, [
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ]);

            throw new Exception($message, (int) $code);
        }
    }
}
