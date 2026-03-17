<?php
// FILE: app/Http/Controllers/Admin/PricingPlanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $plans = PricingPlan::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.pricing.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.pricing.edit');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        PricingPlan::create($this->prepareData($request));
        return redirect()->route('admin.pricing.index')->with('success', 'Plan created.');
    }

    public function edit(PricingPlan $pricing)
    {
        return view('admin.pricing.edit', ['plan' => $pricing]);
    }

    public function update(Request $request, PricingPlan $pricing)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $pricing->update($this->prepareData($request));
        return redirect()->route('admin.pricing.index')->with('success', 'Plan updated.');
    }

    public function destroy(PricingPlan $pricing)
    {
        $pricing->delete();
        return redirect()->route('admin.pricing.index')->with('success', 'Plan deleted.');
    }

    private function prepareData(Request $request): array
    {
        // Parse textarea lines into arrays
        $features = array_values(array_filter(
            array_map('trim', explode("\n", $request->input('features_raw', '')))
        ));
        $excluded = array_values(array_filter(
            array_map('trim', explode("\n", $request->input('excluded_features_raw', '')))
        ));

        return [
            'name'              => $request->name,
            'badge'             => $request->badge,
            'price'             => $request->price ?: null,
            'price_suffix'      => $request->price_suffix ?? '/mo',
            'description'       => $request->description,
            'features'          => $features,
            'excluded_features' => $excluded ?: null,
            'cta_label'         => $request->cta_label ?? 'Get Started',
            'cta_url'           => $request->cta_url,
            'is_featured'       => $request->boolean('is_featured'),
            'is_published'      => $request->boolean('is_published'),
            'sort_order'        => $request->sort_order ?? 0,
        ];
    }
}