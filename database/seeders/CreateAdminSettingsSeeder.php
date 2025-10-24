<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdminSettingsSeeder extends Seeder
{

    public function run(): void
    {
        $settings = [
            // General site info
            'site_name' => null,
            'site_email' => null,
            'site_phone' => null,
            'site_logo' => null,
            'site_favicon' => null,
            'timezone' => null,
            'currency' => null,
            'date_format' => null,
            'time_format' => null,

            // SEO settings
            'seo_title' => null,
            'seo_description' => null,
            'seo_keywords' => null,
            'seo_author' => null,
            'seo_image' => null,
            'seo_google_verification' => null,
            'seo_bing_verification' => null,

            // Social media links
            'facebook_url' => null,
            'twitter_url' => null,
            'linkedin_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'tiktok_url' => null,

            // Footer / additional
            'footer_text' => null,
            'contact_address' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'support_email' => null,

            // Analytics / Scripts
            'google_analytics_id' => null,
            'facebook_pixel_id' => null,
            'custom_head_script' => null,
            'custom_body_script' => null,
        ];

        foreach ($settings as $key => $value) {
            Settings::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Extended default settings seeded successfully!');
    }
}
