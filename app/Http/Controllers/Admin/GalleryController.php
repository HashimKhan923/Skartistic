<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('sort_order')->latest('id')->get();

        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1|max:20',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240|dimensions:ratio=4/5',
        ], [
            'images.required'    => 'Choose at least one image.',
            'images.max'         => 'You can upload up to 20 images at a time.',
            'images.*.dimensions' => 'Every image must be 4:5, for example 1080 × 1350 pixels.',
            'images.*.mimes'     => 'Use JPG, PNG or WebP images.',
            'images.*.max'       => 'Each image must be 10 MB or smaller.',
        ]);

        foreach ($request->file('images') as $file) {
            GalleryImage::create(['image' => $file->store('gallery', 'public')]);
        }

        return back()->with('success', 'Gallery updated!');
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $data = $request->validate([
            'caption'    => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        $gallery->update($data);

        return back()->with('success', 'Image updated!');
    }

    public function destroy(GalleryImage $gallery)
    {
        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Image deleted!');
    }
}
