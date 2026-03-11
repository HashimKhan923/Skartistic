<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // ── Fields that are FILE uploads (not text) ──────────────────
        $fileFields = [
            'site_logo',
            'promo_image',
            'promo_video_file',
            'og_image',
        ];

        // ── Fields to skip entirely (handled separately below) ───────
        $skip = array_merge(
            $fileFields,
            ['_token', '_method'],
            // Remove-checkbox fields
            ['remove_site_logo', 'remove_promo_image', 'remove_promo_video_file', 'remove_og_image']
        );

        // ── 1. Save all plain text / URL fields ───────────────────────
        foreach ($request->except($skip) as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        // ── 2. Handle file uploads & removals ─────────────────────────
        $fileConfigs = [
            'site_logo' => [
                'disk'      => 'public',
                'folder'    => 'settings',
                'remove_key'=> 'remove_site_logo',
            ],
            'promo_image' => [
                'disk'      => 'public',
                'folder'    => 'settings/promo',
                'remove_key'=> 'remove_promo_image',
            ],
            'promo_video_file' => [
                'disk'      => 'public',
                'folder'    => 'settings/promo',
                'remove_key'=> 'remove_promo_video_file',
            ],
            'og_image' => [
                'disk'      => 'public',
                'folder'    => 'settings/seo',
                'remove_key'=> 'remove_og_image',
            ],
        ];

        foreach ($fileConfigs as $field => $config) {
            $disk      = $config['disk'];
            $folder    = $config['folder'];
            $removeKey = $config['remove_key'];
            $current   = Setting::get($field, '');

            // Handle removal checkbox
            if ($request->boolean($removeKey) && $current) {
                Storage::disk($disk)->delete($current);
                Setting::set($field, '');
                continue;
            }

            // Handle new file upload
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                // Delete old file first
                if ($current) {
                    Storage::disk($disk)->delete($current);
                }
                $path = $request->file($field)->store($folder, $disk);
                Setting::set($field, $path);
            }
        }

        return back()->with('success', 'Settings saved successfully!');
    }
}