<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    //
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'image'    => 'nullable|image|max:2048',
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // ✅ Upload to ImgBB
        if ($request->hasFile('image')) {

            $image = base64_encode(file_get_contents($request->file('image')->path()));

            $response = Http::asForm()->post(
                'https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY'),
                [
                    'image' => $image,
                ]
            );

            if ($response->successful()) {
                $imageUrl = $response->json()['data']['url'];
                $user->image = $imageUrl; // store full URL
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Image upload failed'
                ], 500);
            }
        }

        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'data'    => [
                'name'  => $user->name,
                'email' => $user->email,
                'image' => $user->image,
            ]
        ], 200);
    }
}
