<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller {
    public function index() { return view('admin.team.index', ['members' => TeamMember::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.team.form'); }
    public function store(Request $request) {
        $data = $request->validate(['name'=>'required','position'=>'required','bio'=>'nullable','linkedin'=>'nullable','twitter'=>'nullable','instagram'=>'nullable']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('team','public');
        TeamMember::create($data);
        return redirect()->route('admin.team.index')->with('success','Member added!');
    }
    public function edit(TeamMember $team) { return view('admin.team.form', ['member' => $team]); }
    public function update(Request $request, TeamMember $team) {
        $data = $request->validate(['name'=>'required','position'=>'required','bio'=>'nullable','linkedin'=>'nullable','twitter'=>'nullable','instagram'=>'nullable']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('team','public');
        $team->update($data);
        return redirect()->route('admin.team.index')->with('success','Member updated!');
    }
    public function destroy(TeamMember $team) { $team->delete(); return back()->with('success','Deleted!'); }
    public function show(TeamMember $team) { return redirect()->route('admin.team.edit', $team); }
}