<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.seo.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'seo_title', 'seo_description', 'seo_keywords',
            'og_title', 'og_description', 'og_image',
            'google_analytics_id', 'google_tag_manager_id',
            'facebook_pixel_id', 'robots_txt',
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field, '')]
            );
        }

        if ($request->hasFile('og_image_upload')) {
            $path = $request->file('og_image_upload')->store('seo', 'public');
            Setting::updateOrCreate(['key' => 'og_image'], ['value' => asset('storage/' . $path)]);
        }

        return back()->with('success', 'SEO settings saved!');
    }
}