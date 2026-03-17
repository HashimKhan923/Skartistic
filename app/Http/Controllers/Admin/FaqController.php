<?php
// FILE: app/Http/Controllers/Admin/FaqController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string',
        ]);

        Faq::create([
            'question'     => $request->question,
            'answer'       => $request->answer,
            'sort_order'   => $request->sort_order ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string',
        ]);

        $faq->update([
            'question'     => $request->question,
            'answer'       => $request->answer,
            'sort_order'   => $request->sort_order ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    // Bulk sort order save (called via AJAX from drag-and-drop)
    public function reorder(Request $request)
    {
        foreach ($request->input('order', []) as $item) {
            Faq::where('id', $item['id'])->update(['sort_order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }
}