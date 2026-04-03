<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
        $user = $request->user();

        if(!auth()->guard('admin')->check()){
            // $user = auth()->guard('admin')->user();
            return redirect()->route('admin.login');
        }

        // 1️⃣ No token / invalid token
        // if (!$user) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Token missing or invalid.'
        //     ], 401);
        // }

        // // Load role & permissions
        // $user->loadMissing('role.permissions');

        // // 2️⃣ If user is Admin → Allow everything
        // if ($user->role && $user->role->name === 'admin') {
        //     return $next($request);
        // }

        // // 3️⃣ If permission required but user doesn't have it
        // if ($permission && !$user->hasPermission($permission)) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'You do not have permission to perform this action.'
        //     ], 403);
        // }

        // // 4️⃣ If no permission required but user not admin
        // if (!$permission) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Unauthorized access.'
        //     ], 403);
        // }

        return $next($request);
    }
}
