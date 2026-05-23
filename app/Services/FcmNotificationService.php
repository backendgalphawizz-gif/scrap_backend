<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\User;
use App\Providers\FirebaseService;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    public function __construct(private FirebaseService $firebase) {}

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
        try {
            $result = $this->firebase->sendNotification($token, $title, $body, $data);

            if (is_string($result)) {
                Log::warning('FCM notification failed', ['error' => $result]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('FCM notification exception: ' . $e->getMessage());
            return false;
        }
    }
}
