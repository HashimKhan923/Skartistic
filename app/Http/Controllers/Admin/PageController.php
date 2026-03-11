<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller {
    public function index() { return view('admin.pages.index', ['pages' => Page::all()]); }
    public function create() { return view('admin.pages.form'); }
    public function store(Request $request) {
        $data = $request->validate(['title'=>'required','content'=>'nullable','meta_title'=>'nullable','meta_description'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['show_in_menu'] = $request->boolean('show_in_menu');
        $data['menu_order'] = $request->input('menu_order', 0);
        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success','Page created!');
    }
    public function edit(Page $page) { return view('admin.pages.form', compact('page')); }
    public function update(Request $request, Page $page) {
        $data = $request->validate(['title'=>'required','content'=>'nullable','meta_title'=>'nullable','meta_description'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['show_in_menu'] = $request->boolean('show_in_menu');
        $data['menu_order'] = $request->input('menu_order', 0);
        $page->update($data);
        return redirect()->route('admin.pages.index')->with('success','Page updated!');
    }
    public function destroy(Page $page) { $page->delete(); return back()->with('success','Deleted!'); }
    public function show(Page $page) { return redirect()->route('admin.pages.edit', $page); }
}