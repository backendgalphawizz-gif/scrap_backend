<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\CPU\ImageManager;

class LandingPageController extends Controller
{
    // All landing page section keys
    private array $sections = [
        'landing_hero',
        'landing_tagline',
        'landing_advertise',
        'landing_services',
        'landing_about',
        'landing_mobile',
        'landing_faq',
    ];

    public function index()
    {
        $data = [];
        foreach ($this->sections as $key) {
            $setting = BusinessSetting::where('type', $key)->first();
            $data[$key] = $setting ? json_decode($setting->value, true) : [];
        }
        return view('admin-views.landing-page.index', compact('data'));
    }

    public function update(Request $request, string $section)
    {
        if (!in_array($section, $this->sections)) {
            session()->flash('error', 'Invalid section.');
            return back();
        }

        $payload = $this->buildPayload($request, $section);

        BusinessSetting::updateOrCreate(
            ['type' => $section],
            ['value' => json_encode($payload)]
        );

        session()->flash('success', ucfirst(str_replace('_', ' ', $section)) . ' updated successfully!');
        return redirect()->route('admin.landing-page.index', ['tab' => $section]);
    }

    private function buildPayload(Request $request, string $section): array
    {
        switch ($section) {
            case 'landing_hero':
                $removeSlides = array_map('intval', (array) $request->input('remove_slides', []));
                $slides = [];

                // Keep existing slides not flagged for removal
                foreach ((array) $request->input('slides', []) as $si => $s) {
                    if (in_array((int) $si, $removeSlides, true)) {
                        ImageManager::delete('landing/hero/' . $s['image']);
                        continue;
                    }
                    if (!empty($s['image'])) {
                        $slides[] = [
                            'image' => $s['image'],
                            'title' => $s['title'] ?? '',
                            'link'  => $s['link']  ?? '',
                        ];
                    }
                }

                // Upload new slides
                if ($request->hasFile('new_slides')) {
                    $newTitles = (array) $request->input('new_slide_titles', []);
                    $newLinks  = (array) $request->input('new_slide_links', []);
                    foreach ($request->file('new_slides') as $idx => $file) {
                        if ($file && $file->isValid()) {
                            $filename = ImageManager::upload(
                                'landing/hero/',
                                $file->getClientOriginalExtension() ?: 'jpg',
                                $file
                            );
                            $slides[] = [
                                'image' => $filename,
                                'title' => $newTitles[$idx] ?? '',
                                'link'  => $newLinks[$idx]  ?? '',
                            ];
                        }
                    }
                }

                return ['slides' => $slides];

            case 'landing_tagline':
                return [
                    'headline'          => $request->input('headline', ''),
                    'subtitle'          => $request->input('subtitle', ''),
                    'app_store_link'    => $request->input('app_store_link', ''),
                    'play_store_link'   => $request->input('play_store_link', ''),
                    'badge_text'        => $request->input('badge_text', ''),
                ];

            case 'landing_advertise':
                $features = [];
                foreach ((array) $request->input('features', []) as $f) {
                    if (!empty($f['title'])) {
                        $features[] = [
                            'icon'  => $f['icon'] ?? '',
                            'title' => $f['title'],
                            'desc'  => $f['desc'] ?? '',
                        ];
                    }
                }
                $stats = [];
                foreach ((array) $request->input('stats', []) as $s) {
                    if (!empty($s['value'])) {
                        $stats[] = [
                            'value' => $s['value'],
                            'label' => $s['label'] ?? '',
                        ];
                    }
                }

                // --- Banners ---
                $removeBanners = array_map('intval', (array) $request->input('remove_banners', []));
                $banners = [];

                // Keep existing banners that are not flagged for removal
                foreach ((array) $request->input('banners', []) as $bi => $b) {
                    if (in_array((int)$bi, $removeBanners, true)) {
                        // Delete the file from storage
                        ImageManager::delete('landing/banners/' . $b['image']);
                        continue;
                    }
                    if (!empty($b['image'])) {
                        $banners[] = [
                            'image' => $b['image'],
                            'title' => $b['title'] ?? '',
                            'link'  => $b['link'] ?? '',
                        ];
                    }
                }

                // Upload new banners
                if ($request->hasFile('new_banners')) {
                    $newTitles = (array) $request->input('new_banner_titles', []);
                    $newLinks  = (array) $request->input('new_banner_links', []);
                    foreach ($request->file('new_banners') as $idx => $file) {
                        if ($file && $file->isValid()) {
                            $filename = ImageManager::upload('landing/banners/', $file->getClientOriginalExtension() ?: 'png', $file);
                            $banners[] = [
                                'image' => $filename,
                                'title' => $newTitles[$idx] ?? '',
                                'link'  => $newLinks[$idx] ?? '',
                            ];
                        }
                    }
                }

                return [
                    'headline' => $request->input('headline', ''),
                    'subtitle' => $request->input('subtitle', ''),
                    'features' => $features,
                    'stats'    => $stats,
                    'banners'  => $banners,
                ];

            case 'landing_services':
                $items = [];
                foreach ((array) $request->input('items', []) as $item) {
                    if (!empty($item['title'])) {
                        $items[] = [
                            'icon'  => $item['icon'] ?? '',
                            'title' => $item['title'],
                            'desc'  => $item['desc'] ?? '',
                        ];
                    }
                }
                return [
                    'headline' => $request->input('headline', ''),
                    'subtitle' => $request->input('subtitle', ''),
                    'items'    => $items,
                ];

            case 'landing_about':
                $bullets = array_filter(
                    array_map('trim', explode("\n", $request->input('bullets', '')))
                );
                return [
                    'headline' => $request->input('headline', ''),
                    'content'  => $request->input('content', ''),
                    'bullets'  => array_values($bullets),
                ];

            case 'landing_mobile':
                return [
                    'headline' => $request->input('headline', ''),
                    'subtitle' => $request->input('subtitle', ''),
                    'app_store_link'  => $request->input('app_store_link', ''),
                    'play_store_link' => $request->input('play_store_link', ''),
                ];

            case 'landing_faq':
                $items = [];
                foreach ((array) $request->input('items', []) as $item) {
                    if (!empty($item['question'])) {
                        $items[] = [
                            'question' => $item['question'],
                            'answer'   => $item['answer'] ?? '',
                        ];
                    }
                }
                return [
                    'headline' => $request->input('headline', ''),
                    'subtitle' => $request->input('subtitle', ''),
                    'items'    => $items,
                ];

            default:
                return [];
        }
    }
}
