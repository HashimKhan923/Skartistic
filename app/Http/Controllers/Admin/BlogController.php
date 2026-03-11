<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller {
    public function index() {
        $posts = BlogPost::latest()->get();
        return view('admin.blog.index', compact('posts'));
    }
    public function create() { return view('admin.blog.form'); }
    public function store(Request $request) {
        $data = $request->validate(['title'=>'required','excerpt'=>'nullable','content'=>'nullable','author'=>'nullable','category'=>'nullable','is_published'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = now();
        if ($request->hasFile('featured_image')) $data['featured_image'] = $request->file('featured_image')->store('blog','public');
        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success','Post created!');
    }
    public function edit(BlogPost $blog) { return view('admin.blog.form', ['post' => $blog]); }
    public function update(Request $request, BlogPost $blog) {
        $data = $request->validate(['title'=>'required','excerpt'=>'nullable','content'=>'nullable','author'=>'nullable','category'=>'nullable','is_published'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('featured_image')) $data['featured_image'] = $request->file('featured_image')->store('blog','public');
        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success','Post updated!');
    }
    public function destroy(BlogPost $blog) { $blog->delete(); return back()->with('success','Post deleted!'); }
    public function show(BlogPost $blog) { return redirect()->route('admin.blog.edit', $blog); }
}