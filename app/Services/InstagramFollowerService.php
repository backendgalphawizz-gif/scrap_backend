<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramFollowerService
{
    /**
     * Fetch the follower count for an Instagram username.
     *
     * Uses the unofficial Instagram web_profile_info endpoint.
     * Returns null on failure (rate-limit, private account, network error, etc.)
     * so callers can treat null as "unknown" without breaking.
     */
    public function fetchFollowers(string $username): ?int
    {
        $appId  = config('services.instagram.app_id');
        $cookie = config('services.instagram.cookie');

        if (empty($appId) || empty($username)) {
            Log::warning('InstagramFollowerService: missing app_id or username', [
                'username'   => $username,
                'has_app_id' => !empty($appId),
            ]);
            return null;
        }

        if (empty($cookie)) {
            Log::warning('InstagramFollowerService: INSTAGRAM_COOKIE is not set — requests will likely be rejected by Instagram', [
                'username' => $username,
            ]);
        }

        $headers = [
            'x-ig-app-id' => $appId,
            'User-Agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept'      => '*/*',
            'Referer'     => 'https://www.instagram.com/',
        ];

        if (!empty($cookie)) {
            $headers['Cookie'] = $cookie;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders($headers)
                ->get('https://www.instagram.com/api/v1/users/web_profile_info/', [
                    'username' => $username,
                ]);

            if (!$response->successful()) {
                Log::warning('InstagramFollowerService: non-200 response', [
                    'username' => $username,
                    'status'   => $response->status(),
                    'body'     => substr($response->body(), 0, 500),
                ]);
                return null;
            }

            $count = data_get($response->json(), 'data.user.edge_followed_by.count');

            if (!is_numeric($count)) {
                Log::warning('InstagramFollowerService: follower count not found in response', [
                    'username' => $username,
                ]);
                return null;
            }

            return (int) $count;
        } catch (\Throwable $e) {
            Log::error('InstagramFollowerService: exception fetching followers', [
                'username' => $username,
                'error'    => $e->getMessage(),
            ]);
            return null;
        }
    }
}
