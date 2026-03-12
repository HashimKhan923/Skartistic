<?php
// FILE: app/Http/Controllers/Admin/ServiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form');
    }

    public function store(Request $request)
    {
        $data = $this->prepareData($request, new Service());
        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->prepareData($request, $service);
        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }

    /* ─── shared prepare ─── */
    private function prepareData(Request $request, Service $service): array
    {
        $data = $request->only([
            'title','slug','icon','tag_label',
            'hero_headline','hero_subtitle','hero_cta_primary','hero_cta_secondary',
            'offer_tag','offer_title','offer_subtitle',
            'techstack_tag','techstack_title','techstack_subtitle',
            'process_tag','process_title','process_subtitle',
            'work_tag','work_title','work_subtitle',
            'cta_title','cta_subtitle',
            'short_description','sort_order',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['slug']         = Str::slug($data['slug'] ?: $data['title']);

        // ── Banner image ──
        if ($request->hasFile('banner_image')) {
            if ($service->banner_image) Storage::disk('public')->delete($service->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('services/banners', 'public');
        }

        // ── Offer features ──
        $offerFeats = $request->input('offer_features', []);
        foreach ($offerFeats as $i => &$feat) {
            if ($request->hasFile("offer_feature_images.$i")) {
                $feat['image'] = $request->file("offer_feature_images.$i")
                    ->store('services/features', 'public');
            }
        }
        $data['offer_features'] = array_values(array_filter($offerFeats, fn($f) => !empty($f['title'])));

        // ── Tech categories ──
        $techCats = $request->input('tech_categories', []);
        foreach ($techCats as &$cat) {
            $cat['items'] = array_values(array_filter($cat['items'] ?? [], fn($i) => !empty($i['name'])));
        }
        $data['tech_categories'] = array_values(array_filter($techCats, fn($c) => !empty($c['name'])));

        // ── Process steps ──
        $steps = $request->input('process_steps', []);
        foreach ($steps as &$step) {
            $step['features'] = array_values(array_filter($step['features'] ?? [], fn($f) => !empty($f['label'])));
        }
        $data['process_steps'] = array_values(array_filter($steps, fn($s) => !empty($s['title'])));

        // ── Featured projects ──
        $projs = $request->input('featured_projects', []);
        foreach ($projs as $i => &$proj) {
            // handle comma-separated features
            if (!empty($proj['features_raw'])) {
                $proj['features'] = array_map('trim', explode(',', $proj['features_raw']));
            }
            unset($proj['features_raw']);

            if ($request->hasFile("proj_images.$i")) {
                $proj['image'] = $request->file("proj_images.$i")
                    ->store('services/projects', 'public');
            }
        }
        $data['featured_projects'] = array_values(array_filter($projs, fn($p) => !empty($p['title'])));

        return $data;
    }
}