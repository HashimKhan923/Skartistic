<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('sort_order')->get();
        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolio.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content'      => 'nullable|string',
            'client'       => 'nullable|string|max:255',
            'category'     => 'nullable|string|max:100',
            'tags'         => 'nullable|string',
            'project_url'  => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['slug']        = Str::slug($data['title']) . '-' . Str::random(4);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published']= $request->boolean('is_published');

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        // Gallery images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('portfolio/gallery', 'public');
            }
        }
        $data['images'] = $images;

        Portfolio::create($data);
        return redirect()->route('admin.portfolio.index')->with('success', 'Project added!');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolio.form', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'content'      => 'nullable|string',
            'client'       => 'nullable|string|max:255',
            'category'     => 'nullable|string|max:100',
            'tags'         => 'nullable|string',
            'project_url'  => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published']= $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        if ($request->hasFile('images')) {
            $images = $portfolio->images ?? [];
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('portfolio/gallery', 'public');
            }
            $data['images'] = $images;
        }

        $portfolio->update($data);
        return redirect()->route('admin.portfolio.index')->with('success', 'Project updated!');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return back()->with('success', 'Project deleted!');
    }

    public function show(Portfolio $portfolio)
    {
        return redirect()->route('portfolio.detail', $portfolio->slug);
    }
}