<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Seller;
use App\Models\Sale;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     *  check.account.status:user    — for User (Passport auth:api), status tinyint 1=active
     *  check.account.status:seller  — for Seller/Brand, status string 'active'
     *  check.account.status:sale    — for Sale agent, status enum 'active'
     */
    public function handle(Request $request, Closure $next, string $guard = 'user'): Response
    {
        switch ($guard) {
            case 'seller':
                $token = $this->extractBearerToken($request);
                if (!$token) {
                    return $this->unauthorized('Unauthorized.');
                }
                $account = Seller::where('auth_token', $token)->first();
                if (!$account) {
                    return $this->unauthorized('Unauthorized.');
                }
                if ($account->status !== 'approved') {
                    return $this->accountInactive();
                }
                break;

            case 'sale':
                $token = $this->extractBearerToken($request);
                if (!$token) {
                    return $this->unauthorized('Unauthorized.');
                }
                $account = Sale::where('auth_token', $token)->first();
                if (!$account) {
                    return $this->unauthorized('Unauthorized.');
                }
                if ($account->status !== 'active') {
                    return $this->accountInactive();
                }
                break;

            default: // user — resolved by auth:api (Passport)
                $user = $request->user();
                if (!$user) {
                    return $this->unauthorized('Unauthorized.');
                }
                // User status: 1 = active, 2 = inactive
                if ((int) $user->status !== 1) {
                    return $this->accountInactive();
                }
                break;
        }

        return $next($request);
    }

    private function extractBearerToken(Request $request): ?string
    {
        $authorization = $request->header('Authorization', '');
        $parts = explode(' ', $authorization);

        if (count($parts) === 2 && strtolower($parts[0]) === 'bearer' && strlen($parts[1]) > 30) {
            return $parts[1];
        }

        return null;
    }

    private function unauthorized(string $message = 'Unauthorized.'): Response
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], 401);
    }

    private function accountInactive(): Response
    {
        return response()->json([
            'status'  => false,
            'message' => 'Your account is not active. Please contact support.',
        ], 401);
    }
}
