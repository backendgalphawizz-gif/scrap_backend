<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin (role_id = 1)
        if ($user->role_id == 1) {
            return response()->json([
                'total_subadmins' => User::where('role_id', 2)->count(),
                'total_supervisor' => User::where('role_id', 3)->count(),
                'total_technician' => User::where('role_id', 4)->count(),
                'total_vehicle_manager' => User::where('role_id', 5)->count(),
                'total_inventory_manager' => User::where('role_id', 6)->count(),
                'all_users' => User::count()
            ]);
        }

        // Sub Admin (role_id = 2)
        if ($user->role_id == 2) {
            return response()->json([
                'total_users' => User::count()
            ]);
        }

        return response()->json([
            'message' => 'No dashboard access'
        ], 403);
    }
}
