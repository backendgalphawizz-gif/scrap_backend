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
        'landing_footer',
    ];

    public function index(): JsonResponse
    {
        $sections = array_filter($this->sections, fn($s) => $s !== 'landing_footer');
        $rows = BusinessSetting::whereIn('type', $sections)
            ->pluck('value', 'type');

        $data = [];
        foreach ($sections as $section) {
            $raw = $rows[$section] ?? null;
            $parsed = $raw ? json_decode($raw, true) : null;
            $data[$section] = $this->resolveImageUrls($section, $parsed);
        }
        $data['landing_footer'] = $this->buildFooter();

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

        if ($section === 'landing_footer') {
            return response()->json([
                'status'  => true,
                'message' => 'Section data retrieved successfully.',
                'data'    => $this->buildFooter(),
            ]);
        }

        $row = BusinessSetting::where('type', $section)->first();
        $parsed = $row ? json_decode($row->value, true) : null;

        return response()->json([
            'status'  => true,
            'message' => 'Section data retrieved successfully.',
            'data'    => $this->resolveImageUrls($section, $parsed),
        ]);
    }

    private function buildFooter(): array
    {
        $keys = [
            'company_name', 'company_email', 'company_phone', 'shop_address',
            'company_web_logo',
            'footer_short_desc', 'footer_copyright',
            'social_facebook', 'social_twitter', 'social_instagram',
            'social_youtube', 'social_linkedin',
        ];

        $rows = BusinessSetting::whereIn('type', $keys)->pluck('value', 'type');

        $logo = $rows['company_web_logo'] ?? null;

        // Build social array — only include non-empty links
        $social = [];
        $socialMap = [
            'facebook'  => 'social_facebook',
            'twitter'   => 'social_twitter',
            'instagram' => 'social_instagram',
            'youtube'   => 'social_youtube',
            'linkedin'  => 'social_linkedin',
        ];
        foreach ($socialMap as $platform => $key) {
            $val = trim($rows[$key] ?? '');
            if ($val !== '') {
                $social[$platform] = $val;
            }
        }

        return [
            'company_name'    => $rows['company_name']    ?? '',
            'short_desc'      => $rows['footer_short_desc'] ?? '',
            'email'           => $rows['company_email']   ?? '',
            'phone'           => $rows['company_phone']   ?? '',
            'address'         => $rows['shop_address']    ?? '',
            'logo'            => $logo,
            'logo_url'        => $logo ? asset('storage/company/' . $logo) : null,
            'copyright'       => $rows['footer_copyright'] ?? '',
            'social'          => $social,
        ];
    }

    private function resolveImageUrls(string $section, ?array $data): ?array
    {
        if (!$data) {
            return $data;
        }

        if ($section === 'landing_hero' && !empty($data['slides'])) {
            $data['slides'] = array_map(function ($slide) {
                if (!empty($slide['image']) && !str_starts_with($slide['image'], 'http')) {
                    $slide['image_url'] = asset('storage/landing/hero/' . $slide['image']);
                }
                return $slide;
            }, $data['slides']);
        }

        if ($section === 'landing_advertise' && !empty($data['banners'])) {
            $data['banners'] = array_map(function ($banner) {
                if (!empty($banner['image']) && !str_starts_with($banner['image'], 'http')) {
                    $banner['image_url'] = asset('storage/landing/banners/' . $banner['image']);
                }
                return $banner;
            }, $data['banners']);
        }

        return $data;
    }
}
