<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = $this->defaults();

        foreach ($defaults as $type => $value) {
            $stored = is_array($value) ? json_encode($value) : (string) $value;

            BusinessSetting::updateOrCreate(
                ['type' => $type],
                ['value' => $stored]
            );
        }

        $this->command?->info('Business settings seeded successfully.');
    }

    /**
     * Core settings required for admin panel, API, and campaigns.
     * Values aligned with production defaults (datascrpynew.sql).
     */
    private function defaults(): array
    {
        $policyPlaceholder = '<p>Default policy content. Update from Admin → Business Settings.</p>';

        return [
            // General / branding
            'language' => [
                [
                    'id' => '1',
                    'name' => 'English',
                    'code' => 'en',
                    'status' => 1,
                    'default' => true,
                    'direction' => 'ltr',
                ],
            ],
            'company_name' => 'Rexarix',
            'company_phone' => '9876543210',
            'company_email' => 'admin@gmail.com',
            'timezone' => 'Asia/Kolkata',
            'country_code' => 'IN',
            'currency_model' => 'single_currency',
            'currency_symbol_position' => 'left',
            'decimal_point_settings' => '2',
            'pagination_limit' => '10',
            'colors' => [
                'primary' => '#282828',
                'secondary' => '#000000',
                'primary_light' => '#CFDFFB',
            ],
            'app_header_colors' => [
                'header_color' => '#3370ff',
                'text_color' => '#343258',
            ],
            'shop_address' => 'Vijay Nagar, Indore, Madhya Pradesh',
            'company_gst_number' => '',

            // Mail (disabled by default for fresh installs)
            'mail_config' => [
                'status' => '0',
                'name' => 'Rexarix',
                'host' => '',
                'driver' => 'SMTP',
                'port' => '465',
                'username' => '',
                'email_id' => '',
                'encryption' => 'ssl',
                'password' => '',
            ],

            // Legal / content
            'terms_condition' => $policyPlaceholder,
            'privacy_policy' => $policyPlaceholder,
            'about_us' => $policyPlaceholder,
            'brand_terms_condition' => $policyPlaceholder,
            'brand_privacy_policy' => $policyPlaceholder,
            'sales_terms_condition' => $policyPlaceholder,
            'sales_privacy_policy' => $policyPlaceholder,
            'campaign_guideline' => 'Ensure all campaign content is clear and relevant to the target audience.',

            // Coins / wallet / rewards
            'minimum_coin_withdrawl' => '200',
            'max_coin_withdrawal' => '20000',
            'upi_value' => '0.5',
            'voucher_value' => '0.5',
            'post_footer_content' => 'Follow us at: @rexarix_official  |  #Adv #Rexarix',
            'kyc_amount' => '1000',
            'minimum_wallet_balance' => '500',

            // Campaign / posting limits
            'max_posts_per_user' => '10',
            'brand_wise_posting_limits' => '2',
            'cost_per_post' => '50',
            'cool_down_period_between_campaigns' => '1',
            'post_sharing_reward' => '1',
            'feedback_incentive' => '1',
            'platform_commission' => '1',
            'campaign_gst_percentage' => '18',
            'brand_max_campaigns_per_timeframe' => '8',
            'brand_campaign_creation_timeframe_hours' => '24',

            // TDS
            'tds_percent' => '1',
            'tds_rate_valid_pan' => '1',
            'tds_rate_invalid_pan' => '20',
            'tds_section' => '194C',

            // Sales commissions
            'sale_post_commission' => '1',
            'sale_brand_commission' => '2',

            // FAQ / popup
            'user_faq' => [
                [
                    'question' => 'How to use the app?',
                    'answer' => 'Register, complete KYC, and join campaigns from the home screen.',
                ],
            ],
            'popup_banner' => [
                'status' => 0,
                'title' => 'Welcome to Rexarix',
                'description' => 'Turn everyday users into brand ambassadors.',
                'image' => '',
            ],

            // Footer / social (optional)
            'footer_short_desc' => 'Rexarix — social campaign platform',
            'social_facebook' => '#',
            'social_twitter' => '#',
            'social_instagram' => '#',
            'social_youtube' => '#',
            'social_linkedin' => '#',
        ];
    }
}
