<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class MaintenanceController extends Controller
{
    /**
     * GET /api/maintenance/migrate — runs `php artisan migrate --force`
     */
    public function migrate(): JsonResponse
    {
        try {
            Artisan::call('migrate', [
                '--force' => true,
                '--no-interaction' => true,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Migrate completed.',
            'output' => trim(Artisan::output()),
        ]);
    }

    /**
     * GET /api/maintenance/optimize — runs `php artisan optimize`
     */
    public function optimize(): JsonResponse
    {
        try {
            Artisan::call('optimize', ['--no-interaction' => true]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Optimize completed.',
            'output' => trim(Artisan::output()),
        ]);
    }
}
