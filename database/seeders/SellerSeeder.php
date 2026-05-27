<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = DB::table('brand_categories')
            ->where('parent_id', 0)
            ->orderBy('id')
            ->value('id');

        $subCategoryId = $categoryId
            ? DB::table('brand_categories')
                ->where('parent_id', $categoryId)
                ->orderBy('id')
                ->value('id')
            : null;

        if (!$categoryId) {
            $this->command?->warn('SellerSeeder skipped: no brand categories found. Run BrandCategorySeeder first.');

            return;
        }

        $brands = [
            [
                'username' => 'Nike',
                'f_name' => 'John Doe',
                'l_name' => null,
                'phone' => '9999999999',
                'email' => 'john@nike.com',
                'status' => 'approved',
                'city' => 'Indore',
                'state' => 'Madhya Pradesh',
                'instagram_username' => 'nike',
                'instagram_status' => 'verified',
                'facebook_status' => 'not_verified',
                'friends_code' => 'RXS-5',
                'referral_code' => '3JCIRQTN',
                'visibility_status' => 'true',
                'pan_number' => 'HHHHH8452A',
                'pan_status' => 'Verified',
            ],
            [
                'username' => 'RB Clothing Ltd',
                'f_name' => 'RB',
                'l_name' => null,
                'phone' => '7171717171',
                'email' => 'RB@rb.com',
                'status' => 'approved',
                'city' => 'Nashik',
                'state' => 'Maharashtra',
                'instagram_username' => 'swarakx1',
                'instagram_status' => 'verified',
                'facebook_username' => 'Swara Kx',
                'facebook_status' => 'verified',
                'referral_code' => 'VPOT7MGB',
                'visibility_status' => 'true',
                'gst_status' => 'Verified',
                'pan_status' => 'Verified',
            ],
            [
                'username' => 'Demo Brand',
                'f_name' => 'Demo',
                'l_name' => 'User',
                'phone' => '9876543210',
                'email' => 'demo.brand@rexarix.com',
                'status' => 'pending',
                'city' => 'Indore',
                'state' => 'Madhya Pradesh',
                'instagram_status' => 'not_submitted',
                'facebook_status' => 'not_submitted',
                'referral_code' => 'DEMOBRND',
                'visibility_status' => null,
            ],
        ];

        foreach ($brands as $brand) {
            Seller::updateOrCreate(
                ['email' => $brand['email']],
                array_merge($brand, [
                    'image' => 'def.png',
                    'category_id' => $categoryId,
                    'sub_category_id' => $subCategoryId,
                    'business_registeration_type' => 'Proprietor',
                    'gst_status' => $brand['gst_status'] ?? 'Not Submitted',
                    'pan_status' => $brand['pan_status'] ?? 'Not Submitted',
                    'bank_status' => 'Not Submitted',
                    'referral_code' => $brand['referral_code'] ?? strtoupper(Str::random(8)),
                ])
            );
        }

        $this->command?->info('Demo brands (sellers) seeded successfully.');
    }
}
