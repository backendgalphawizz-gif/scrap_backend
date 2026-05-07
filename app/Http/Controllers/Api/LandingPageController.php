<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\JsonResponse;

class LandingPageController extends Controller
{
    private array $sections = [
        'landing_hero',
        'landing_tagline',
        'landing_advertise',
        'landing_services',
        'landing_about',
        'landing_mobile',
        'landing_faq',
    ];

    public function index(): JsonResponse
    {
        $rows = BusinessSetting::whereIn('type', $this->sections)
            ->pluck('value', 'type');

        $data = [];
        foreach ($this->sections as $section) {
            $raw = $rows[$section] ?? null;
            $data[$section] = $raw ? json_decode($raw, true) : null;
        }

        return response()->json([
            'status'  => true,
            'message' => 'Landing page data retrieved successfully.',
            'data'    => $data,
        ]);
    }

    public function section(string $section): JsonResponse
    {
        if (!in_array($section, $this->sections, true)) {
            return response()->json([
                'status'  => false,
                'message' => 'Section not found.',
            ], 404);
        }

        $row = BusinessSetting::where('type', $section)->first();

        return response()->json([
            'status'  => true,
            'message' => 'Section data retrieved successfully.',
            'data'    => $row ? json_decode($row->value, true) : null,
        ]);
    }
}
