<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Food & Dining' => [
                'Restaurants (Casual Dining)',
                'Fine Dining',
                'Cafes',
                'Street Food',
                'Cloud Kitchen',
                'Bakery & Desserts',
                'Fast Food Chains',
                'Healthy Food / Diet Meals',
                'Beverage Outlets (Juice, Tea, Coffee)',
                'Catering Services',
            ],
            'Fashion & Lifestyle' => [
                "Men's Wear",
                "Women's Wear",
                'Kids Wear',
                'Ethnic Wear',
                'Footwear',
                'Accessories (Watches, Bags, Jewelry)',
                'Beauty & Cosmetics',
                'Salon & Grooming',
                'Luxury Fashion',
                'Streetwear',
            ],
            'Health & Fitness' => [
                'Gyms',
                'Yoga Studios',
                'Fitness Trainers',
                'Home Workout Programs',
                'Supplements & Nutrition',
                'Sports Academies',
                'Weight Loss Programs',
                'Wellness Centers',
                'Physiotherapy',
                'Mental Wellness',
            ],
            'Technology' => [
                'Mobile Phones',
                'Laptops & Computers',
                'Gadgets & Accessories',
                'Software & SaaS',
                'Mobile Apps',
                'AI Tools',
                'IT Services',
                'Gaming Hardware',
                'Electronics Retail',
            ],
            'Travel & Hospitality' => [
                'Hotels',
                'Resorts',
                'Homestays',
                'Travel Agencies',
                'Tour Packages',
                'Local Experiences',
                'Cab / Transport Services',
                'Flight / Train Booking',
                'Adventure Travel',
                'Luxury Travel',
            ],
            'Education & Learning' => [
                'Schools',
                'Colleges',
                'Coaching Institutes',
                'Online Courses',
                'Skill Development',
                'Study Abroad',
                'Competitive Exam Prep',
                'EdTech Platforms',
                'Books & Study Material',
                'Language Learning',
            ],
            'Finance & Services' => [
                'Banking Services',
                'Credit Cards',
                'Loans',
                'Insurance',
                'Investment Platforms',
                'FinTech Apps',
                'Tax Services',
                'CA / Financial Advisors',
                'Payment Apps',
                'Business Services',
            ],
            'Entertainment & Media' => [
                'Movies',
                'Music',
                'OTT Platforms',
                'Events & Concerts',
                'Influencers / Creators',
                'Content Platforms',
                'News & Media',
                'Photography / Videography',
                'Event Management',
                'Ticketing Platforms',
            ],
            'Automobile' => [
                'Cars',
                'Bikes',
                'EV Vehicles',
                'Dealerships',
                'Service Centers',
                'Accessories',
                'Car/Bike Rentals',
                'Driving Schools',
                'Spare Parts',
                'Insurance (Auto)',
            ],
            'Home & Living' => [
                'Furniture',
                'Home Decor',
                'Interior Design',
                'Kitchen Appliances',
                'Home Appliances',
                'Real Estate',
                'Home Services (Cleaning, Repair)',
                'Smart Home Devices',
                'Gardening',
                'Rental Services',
            ],
            'Retail & Shopping' => [
                'Grocery Stores',
                'Supermarkets',
                'E-commerce',
                'Local Retail Shops',
                'Electronics Stores',
                'Clothing Stores',
                'Wholesale Stores',
                'Stationery Shops',
                'Gift Shops',
                'Pet Stores',
            ],
            'Kids & Parenting' => [
                'Toys',
                'Baby Products',
                'Kids Clothing',
                'Parenting Services',
                'Daycare / Play School',
                'Kids Education',
                'Activity Classes',
                'Pediatric Services',
                'Kids Events',
                'Learning Toys',
            ],
            'Gaming' => [
                'Mobile Gaming',
                'Console Gaming',
                'PC Gaming',
                'Gaming Cafes',
                'Esports',
                'Game Streaming',
                'Game Developers',
                'Gaming Accessories',
                'VR Gaming',
                'Online Gaming Platforms',
            ],
            'Healthcare' => [
                'Hospitals',
                'Clinics',
                'Diagnostics',
                'Pharmacies',
                'Doctors',
                'Telemedicine',
                'Dental Care',
                'Eye Care',
            ],
            'Local Services' => [
                'Repair Services',
                'Electricians / Plumbers',
                'Laundry',
                'Packers & Movers',
                'Event Decorators',
                'Security Services',
                'Freelancers',
                'Printing Services',
            ],
            'Others' => [],
        ];

        foreach ($categories as $parentName => $children) {
            $parentId = $this->upsertCategory($parentName, 0);

            foreach ($children as $childName) {
                $this->upsertCategory($childName, $parentId);
            }
        }
    }

    private function upsertCategory(string $name, int $parentId): int
    {
        $existing = DB::table('brand_categories')
            ->where('name', $name)
            ->where('parent_id', $parentId)
            ->first();

        if ($existing) {
            DB::table('brand_categories')
                ->where('id', $existing->id)
                ->update([
                    'status' => true,
                    'updated_at' => now(),
                ]);

            return (int) $existing->id;
        }

        return (int) DB::table('brand_categories')->insertGetId([
            'name' => $name,
            'parent_id' => $parentId,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
