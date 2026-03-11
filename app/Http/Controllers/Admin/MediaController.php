<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->input('folder', 'all');
        $query  = MediaFile::latest();
        if ($folder !== 'all') $query->where('folder', $folder);

        $files = $query->paginate(40)->through(function ($file) {
            $file->url            = asset('storage/' . $file->path);
            $file->name           = $file->original_name;
            $file->size_formatted = $file->size > 1048576
                ? round($file->size / 1048576, 1) . ' MB'
                : round($file->size / 1024) . ' KB';
            return $file;
        });

        $folderCounts = MediaFile::selectRaw('folder, COUNT(*) as count')
            ->groupBy('folder')
            ->pluck('count', 'folder')
            ->toArray();

        $folders = array_keys($folderCounts);

        return view('admin.media.index', compact('files', 'folders', 'folderCounts', 'folder'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files'   => 'required',
            'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg,pdf',
        ]);

        $uploaded = [];
        $folder   = $request->input('folder', 'general');

        foreach ($request->file('files') as $file) {
            $path = $file->store("media/{$folder}", 'public');
            $media = MediaFile::create([
                'filename'      => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'folder'        => $folder,
                'alt_text'      => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);
            $uploaded[] = [
                'id'  => $media->id,
                'url' => asset('storage/' . $path),
                'name'=> $media->original_name,
            ];
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'files' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' file(s) uploaded!');
    }

    public function destroy(MediaFile $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        if (request()->ajax()) return response()->json(['success' => true]);
        return back()->with('success', 'File deleted!');
    }

    public function updateAlt(Request $request, MediaFile $media)
    {
        $media->update(['alt_text' => $request->alt_text]);
        return response()->json(['success' => true]);
    }
}