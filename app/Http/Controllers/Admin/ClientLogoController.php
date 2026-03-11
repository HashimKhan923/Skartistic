<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;

class ClientLogoController extends Controller
{
    public function index()
    {
        $logos = ClientLogo::orderBy('sort_order')->get();
        return view('admin.client-logos.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.client-logos.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|file|image|max:2048',
        ]);

        ClientLogo::create([
            'name'        => $request->name,
            'logo'        => $request->file('logo')->store('clients', 'public'),
            'website_url' => $request->website_url,
            'is_published'=> $request->boolean('is_published'),
            'sort_order'  => $request->input('sort_order', 0),
        ]);

        return redirect()->route('admin.client-logos.index')->with('success', 'Logo added!');
    }

    public function edit(ClientLogo $clientLogo)
    {
        return view('admin.client-logos.form', ['logo' => $clientLogo]);
    }

    public function update(Request $request, ClientLogo $clientLogo)
    {
        $data = $request->only(['name','website_url','sort_order']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }
        $clientLogo->update($data);
        return redirect()->route('admin.client-logos.index')->with('success', 'Logo updated!');
    }

    public function destroy(ClientLogo $clientLogo)
    {
        $clientLogo->delete();
        return back()->with('success', 'Logo deleted!');
    }
}