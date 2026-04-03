<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ImageUploadService
{
    public function uploadToImgBB($file)
    {
        if (!$file) {
            return null;
        }

        try {
            $image = base64_encode(file_get_contents($file->getRealPath()));

            $response = Http::asForm()->post(
                'https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY'),
                [
                    'image' => $image,
                ]
            );

            if ($response->successful()) {
                return $response->json()['data']['url'];
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }
}