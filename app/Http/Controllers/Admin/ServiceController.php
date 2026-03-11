<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller {
    public function index() {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }
    public function create() { return view('admin.services.form'); }
    public function store(Request $request) {
        $data = $request->validate(['title'=>'required','short_description'=>'nullable|string','content'=>'nullable|string','icon'=>'nullable|string','is_published'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('services','public');
        Service::create($data);
        return redirect()->route('admin.services.index')->with('success','Service created!');
    }
    public function edit(Service $service) { return view('admin.services.form', compact('service')); }
    public function update(Request $request, Service $service) {
        $data = $request->validate(['title'=>'required','short_description'=>'nullable|string','content'=>'nullable|string','icon'=>'nullable|string','is_published'=>'nullable']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('services','public');
        $service->update($data);
        return redirect()->route('admin.services.index')->with('success','Service updated!');
    }
    public function destroy(Service $service) {
        $service->delete();
        return back()->with('success','Service deleted!');
    }
    public function show(Service $service) { return redirect()->route('admin.services.edit', $service); }
}