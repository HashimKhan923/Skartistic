<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    private function uploadFile($file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        return 'uploads/' . $folder . '/' . $name;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fileFields = ['site_logo', 'promo_image', 'promo_video_file', 'og_image'];

        $skip = array_merge(
            $fileFields,
            ['_token', '_method'],
            ['remove_site_logo', 'remove_promo_image', 'remove_promo_video_file', 'remove_og_image']
        );

        // ── 1. Save all plain text / URL fields ──
        foreach ($request->except($skip) as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        // ── 2. Handle file uploads & removals ──
        $fileConfigs = [
            'site_logo'        => ['folder' => 'settings',       'remove_key' => 'remove_site_logo'],
            'promo_image'      => ['folder' => 'settings/promo', 'remove_key' => 'remove_promo_image'],
            'promo_video_file' => ['folder' => 'settings/promo', 'remove_key' => 'remove_promo_video_file'],
            'og_image'         => ['folder' => 'settings/seo',   'remove_key' => 'remove_og_image'],
        ];

        foreach ($fileConfigs as $field => $config) {
            $current   = Setting::get($field, '');
            $removeKey = $config['remove_key'];

            if ($request->boolean($removeKey) && $current) {
                $this->deleteFile($current);
                Setting::set($field, '');
                continue;
            }

            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $this->deleteFile($current);
                Setting::set($field, $this->uploadFile($request->file($field), $config['folder']));
            }
        }

        return back()->with('success', 'Settings saved successfully!');
    }
}