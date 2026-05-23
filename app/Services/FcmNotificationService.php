<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    private const FCM_ENDPOINT = 'https://fcm.googleapis.com/fcm/send';

    private string $serverKey;

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key', '');
    }

    public function sendToUser(User $user, string $title, string $body, array $data = []): bool
    {
        if (empty($user->fcm_id)) {
            return false;
        }

        return $this->send($user->fcm_id, $title, $body, $data);
    }

    public function sendToSeller(Seller $seller, string $title, string $body, array $data = []): bool
    {
        if (empty($seller->cm_firebase_token)) {
            return false;
        }

        return $this->send($seller->cm_firebase_token, $title, $body, $data);
    }

    private function send(string $token, string $title, string $body, array $data = []): bool
    {
        if (empty($this->serverKey)) {
            Log::warning('FCM server key not configured. Set FCM_SERVER_KEY in .env');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type'  => 'application/json',
            ])->post(self::FCM_ENDPOINT, [
                'to'           => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                ],
                'data'         => $data,
                'priority'     => 'high',
            ]);

            if ($response->successful() && isset($response->json()['success']) && $response->json()['success'] === 1) {
                return true;
            }

            Log::warning('FCM notification failed', [
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('FCM notification exception: ' . $e->getMessage());
            return false;
        }
    }
}
