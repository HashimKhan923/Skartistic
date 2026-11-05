<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller {
    public function index() { return view('admin.team.index', ['members' => TeamMember::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.team.form'); }

    public function store(Request $request) {
        TeamMember::create($this->prepareData($request, new TeamMember()));
        return redirect()->route('admin.team.index')->with('success','Member added!');
    }

    public function edit(TeamMember $team) { return view('admin.team.form', ['member' => $team]); }

    public function update(Request $request, TeamMember $team) {
        $team->update($this->prepareData($request, $team));
        return redirect()->route('admin.team.index')->with('success','Member updated!');
    }

    public function destroy(TeamMember $team) { $team->delete(); return back()->with('success','Deleted!'); }
    public function show(TeamMember $team) { return redirect()->route('admin.team.edit', $team); }

    private function prepareData(Request $request, TeamMember $member): array {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|max:5120',
            'gallery.*' => 'image|max:5120',
        ]);
        unset($data['gallery'], $data['photo']);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $request->input('sort_order', 0);
        foreach (['expertise', 'education', 'achievements'] as $list) {
            $data[$list] = $this->lines($request->input($list.'_raw', ''));
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        // Only paths already on this member can be removed
        $gallery = $member->gallery ?? [];
        $remove = array_intersect($gallery, (array) $request->input('remove_gallery', []));
        Storage::disk('public')->delete($remove);
        $gallery = array_values(array_diff($gallery, $remove));
        foreach ($request->file('gallery', []) as $image) {
            $gallery[] = $image->store('team/gallery', 'public');
        }
        $data['gallery'] = $gallery;

        return $data;
    }

    private function lines(?string $raw): array {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $raw))));
    }
}
