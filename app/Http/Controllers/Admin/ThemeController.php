<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class ThemeController extends Controller {
    public function index() {
        $theme = ThemeSetting::all()->pluck('value', 'key');
        return view('admin.theme.index', compact('theme'));
    }
    public function update(Request $request) {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            ThemeSetting::set($key, $value);
        }
        return back()->with('success', 'Theme updated successfully!');
    }
}