<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLevel;

class UserLevelController extends Controller
{
    public function index()
    {
        $levels = UserLevel::all();
        $data = $levels->map(function ($level) {
            return [
                'id' => $level->id,
                'name' => $level->name,
                'range_min' => $level->range_min,
                'range_max' => $level->range_max,
                'max_participations_per_day' => $level->max_participations_per_day,
            ];
        });
        return response()->json([
            'status' => true,
            'message' => 'User levels fetched successfully.',
            'user_levels' => $data
        ]);
    }
}
