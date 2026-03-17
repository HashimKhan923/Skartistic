<?php
// FILE: app/Http/Controllers/Admin/CareerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.careers.index', compact('careers'));
    }

    public function create()
    {
        return view('admin.careers.edit');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:200']);

        Career::create($this->prepareData($request));
        return redirect()->route('admin.careers.index')->with('success', 'Job posting created.');
    }

    public function edit(Career $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career)
    {
        $request->validate(['title' => 'required|string|max:200']);

        $career->update($this->prepareData($request));
        return redirect()->route('admin.careers.index')->with('success', 'Job posting updated.');
    }

    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()->route('admin.careers.index')->with('success', 'Job posting deleted.');
    }

    private function prepareData(Request $request): array
    {
        $parseList = fn($raw) => array_values(array_filter(
            array_map('trim', explode("\n", $request->input($raw, '')))
        ));

        return [
            'title'            => $request->title,
            'department'       => $request->department,
            'location'         => $request->location,
            'type'             => $request->type ?? 'Full-time',
            'experience'       => $request->experience,
            'summary'          => $request->summary,
            'description'      => $request->description,
            'responsibilities' => $parseList('responsibilities_raw'),
            'requirements'     => $parseList('requirements_raw'),
            'benefits'         => $parseList('benefits_raw'),
            'apply_email'      => $request->apply_email,
            'apply_url'        => $request->apply_url,
            'deadline'         => $request->deadline ?: null,
            'is_published'     => $request->boolean('is_published'),
            'sort_order'       => $request->sort_order ?? 0,
        ];
    }
}