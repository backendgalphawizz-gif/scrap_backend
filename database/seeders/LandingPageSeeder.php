<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'landing_hero' => [
                'headline'     => 'Run **Smarter** Ads. Grow **Faster**.',
                'sub_headline' => 'Manage and launch your social media campaigns effortlessly with Rexarix.',
                'cta_text'     => 'Start Advertising',
                'cta_link'     => '#',
            ],

            'landing_tagline' => [
                'badge_text'      => 'TRUSTED BRAND & CLEAN DATA',
                'headline'        => 'Turn every post into measurable growth',
                'subtitle'        => 'Rexarix helps brands and creators amplify campaigns with precision targeting, transparent analytics, and a platform built for scale — because every post counts.',
                'app_store_link'  => '#',
                'play_store_link' => '#',
            ],

            'landing_advertise' => [
                'headline' => 'Reach the right audience',
                'subtitle' => 'Promote your brand across high-intent placements with flexible budgets, real-time reporting, and dedicated support.',
                'features' => [
                    ['icon' => 'mdi-target',       'title' => 'Precision targeting',     'desc' => 'Layer demographics, interests, and behaviour to show ads only where they convert.'],
                    ['icon' => 'mdi-chart-line',    'title' => 'Live performance',        'desc' => 'Dashboards update as your campaigns run — optimize spend without guesswork.'],
                    ['icon' => 'mdi-shield-check',  'title' => 'Brand-safe delivery',     'desc' => 'Quality inventory and review workflows keep your message aligned with your values.'],
                ],
                'stats' => [
                    ['value' => '27M+',  'label' => 'Monthly Impressions'],
                    ['value' => '1.0x',  'label' => 'Avg. ROAS lift'],
                    ['value' => '11',    'label' => 'Markets live'],
                    ['value' => '22.5%', 'label' => 'Uptime SLA'],
                ],
            ],

            'landing_services' => [
                'headline' => 'Everything you need to scale',
                'subtitle' => 'From campaign setup to ongoing optimization, Rexarix bundles the tools teams use every day.',
                'items' => [
                    ['icon' => 'mdi-bullhorn-outline',     'title' => 'Campaign management',     'desc' => 'Unified workflows for creation, budgets, and scheduling across channels.'],
                    ['icon' => 'mdi-broom',                'title' => 'Data scrubbing & hygiene','desc' => 'Clean lists, dedupe roles, and validation so your CRM stays trustworthy.'],
                    ['icon' => 'mdi-chart-bar',            'title' => 'Analytics & attribution', 'desc' => 'Cross-touch reporting with exportable insights for your BI stack.'],
                    ['icon' => 'mdi-account-group',        'title' => 'Audience insights',       'desc' => 'Discover segments that resonate and refine lookalikes over time.'],
                    ['icon' => 'mdi-handshake',            'title' => 'Partner success',         'desc' => 'Strategists on-call for launches, quarterly reviews, and training.'],
                    ['icon' => 'mdi-api',                  'title' => 'API & integrations',      'desc' => 'Connect Rexarix to your stack with webhooks and secure REST APIs.'],
                ],
            ],

            'landing_about' => [
                'headline' => 'Built for clarity in a noisy feed',
                'content'  => 'We believe social and digital campaigns should be measurable, ethical, and efficient. Rexarix combines media expertise with data discipline so teams spend less time fixing spreadsheets and more time growing.',
                'bullets'  => [
                    'Transparent pricing and placement controls',
                    'Privacy-forward data handling and regional compliance',
                    'Collaboration tools for marketing, sales, and agencies',
                ],
            ],

            'landing_mobile' => [
                'headline'        => 'Manage campaigns on the go',
                'subtitle'        => 'Approve creatives, adjust budgets, and get alerts when performance shifts — all from your phone. Available on iOS and Android.',
                'app_store_link'  => '#',
                'play_store_link' => '#',
            ],

            'landing_faq' => [
                'headline' => 'Common questions',
                'subtitle' => 'Quick answers about advertising, billing, and the Rexarix platform.',
                'items'    => [
                    ['question' => 'How do I start advertising on Rexarix?',     'answer' => 'Create an account, add a payment method, and launch a campaign from the dashboard. You can set budgets, audiences, and creatives before anything goes live — our team can help with your first setup if needed.'],
                    ['question' => 'What reporting and metrics do I get?',        'answer' => 'You get real-time dashboards covering impressions, clicks, conversions, ROAS, and audience breakdowns. All data is exportable in CSV or via API.'],
                    ['question' => 'How does billing work?',                     'answer' => 'Billing is prepaid. You load credits to your account and campaigns draw from your balance. Invoices are generated monthly.'],
                    ['question' => 'Is my brand and audience data secure?',       'answer' => 'Yes. Rexarix is SOC 2 Type II audited, uses AES-256 encryption at rest, and complies with applicable regional data-protection regulations.'],
                    ['question' => 'Where can I get support?',                   'answer' => 'Email us at hello@rexarix.com or use the in-app chat. Enterprise customers have a dedicated partner success manager.'],
                ],
            ],
        ];

        foreach ($sections as $type => $value) {
            BusinessSetting::updateOrCreate(
                ['type' => $type],
                ['value' => json_encode($value)]
            );
        }

        $this->command->info('Landing page default content seeded successfully.');
    }
}
