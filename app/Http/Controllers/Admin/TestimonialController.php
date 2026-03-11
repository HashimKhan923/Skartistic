<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller {
    public function index() { return view('admin.testimonials.index', ['testimonials' => Testimonial::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.testimonials.form'); }
    public function store(Request $request) {
        $data = $request->validate(['client_name'=>'required','client_position'=>'nullable','review'=>'required','rating'=>'required|integer|min:1|max:5']);
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $request->input('sort_order', 0);
        if ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('testimonials','public');
        Testimonial::create($data);
        return redirect()->route('admin.testimonials.index')->with('success','Review added!');
    }
    public function edit(Testimonial $testimonial) { return view('admin.testimonials.form', compact('testimonial')); }
    public function update(Request $request, Testimonial $testimonial) {
        $data = $request->validate(['client_name'=>'required','client_position'=>'nullable','review'=>'required','rating'=>'required|integer|min:1|max:5']);
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $request->input('sort_order', 0);
        if ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('testimonials','public');
        $testimonial->update($data);
        return redirect()->route('admin.testimonials.index')->with('success','Review updated!');
    }
    public function destroy(Testimonial $testimonial) { $testimonial->delete(); return back()->with('success','Review deleted!'); }
    public function show(Testimonial $testimonial) { return redirect()->route('admin.testimonials.edit', $testimonial); }
}   