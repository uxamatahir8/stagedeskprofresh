<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    //
    public function index()
    {

        $title = 'Settings';

        $settings = Settings::all();

        $timezones = DB::table('timezones')->get();

        return view('dashboard.pages.settings.index', compact('title', 'settings', 'timezones'));
    }

    public function update(Request $request)
    {
        // All settings keys
        $settingsKeys = [
            // General site info
            'site_name',
            'site_email',
            'site_phone',
            'site_logo',
            'site_favicon',
            'timezone',
            'currency',
            'date_format',
            'time_format',

            // SEO settings
            'seo_title',
            'seo_description',
            'seo_keywords',
            'seo_author',
            'seo_image',
            'seo_google_verification',
            'seo_bing_verification',

            // Social media links
            'facebook_url',
            'twitter_url',
            'linkedin_url',
            'instagram_url',
            'youtube_url',
            'tiktok_url',

            // Footer / additional
            'footer_text',
            'contact_address',
            'contact_email',
            'contact_phone',
            'support_email',

            // Analytics / Scripts
            'google_analytics_id',
            'facebook_pixel_id',
            'custom_head_script',
            'custom_body_script'
        ];

        $fileFields = ['site_logo', 'site_favicon', 'seo_image'];

        foreach ($settingsKeys as $key) {

            // File upload handling
            if (in_array($key, $fileFields) && $request->hasFile($key)) {

                $file = $request->file($key);

                // Delete old file if exists
                $old = Settings::where('key', $key)->first();
                if ($old && $old->value) {
                    Storage::disk('public')->delete($old->value);
                }

                $path = $file->store('settings', 'public');

                Settings::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path]
                );
            } else {
                $value = $request->input($key, null);

                Settings::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
