@extends('admin.layouts.app')
@section('title', 'Manage About Page')
@section('content')

<style>
/* ── CRITICAL: bound .content so grid 1fr columns don't overflow left ── */
.main .content { box-sizing:border-box; width:100%; overflow-x:hidden; }

/* Grid — same pattern as General Settings */
.sg     { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.sg .full { grid-column:1/-1; }
.g2col  { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.g3col  { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
@media(max-width:900px){
    .sg,.g2col,.g3col{ grid-template-columns:1fr; }
}

/* Repeater items */
.rep-item { border:1px solid #e8ecf0; border-radius:10px; overflow:hidden; margin-bottom:10px }
.rep-head  {
    padding:11px 16px; background:#f8fafc; border-bottom:1px solid #e8ecf0;
    display:flex; align-items:center; justify-content:space-between;
    cursor:pointer; user-select:none;
}
.rep-head-label { font-size:13px; font-weight:600; color:#374151; }
.rep-body  { padding:18px; display:none; }
.rep-item.open .rep-body { display:block; }
.rep-chev  { width:15px; height:15px; color:#94a3b8; transition:transform .2s; flex-shrink:0; }
.rep-item.open .rep-chev { transform:rotate(180deg); }

/* Accordion for section cards */
.acc-head {
    display:flex; align-items:center; justify-content:space-between;
    cursor:pointer; user-select:none;
}
.acc-chev { width:18px; height:18px; color:#94a3b8; transition:transform .25s; flex-shrink:0; }
.acc-body  { display:none; }
.acc-open .acc-chev  { transform:rotate(180deg); }
.acc-open .acc-body  { display:block; }

/* Add button */
.rep-add {
    width:100%; padding:10px; margin-top:4px;
    border:1.5px dashed rgba(124,58,237,.4); border-radius:8px;
    background:none; color:#7c3aed; font-size:13px; font-weight:600;
    cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;
    font-family:'DM Sans',sans-serif; transition:background .15s;
}
.rep-add:hover { background:rgba(124,58,237,.05); }

/* Sub-section label */
.sub-lbl { font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1.2px; margin:0 0 10px; }

/* Upload box */
.upl-box {
    border:2px dashed #e2e8f0; border-radius:10px; padding:18px 14px;
    text-align:center; cursor:pointer; position:relative;
    transition:border-color .2s; background:#fafbfc;
}
.upl-box:hover { border-color:#7c3aed; }
.upl-box input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.upl-prev { width:70px; height:70px; border-radius:8px; object-fit:cover; margin:0 auto 8px; display:block; border:1px solid #e2e8f0; }
.upl-hint { font-size:12px; color:#94a3b8; }
.upl-hint strong { color:#7c3aed; }
.rm-chk   { font-size:12px; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:5px; cursor:pointer; }
</style>

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;color:#1e293b">About Page</div>
        <div style="font-size:13px;color:#94a3b8;margin-top:3px">All sections in one place. Click any card header to expand.</div>
    </div>
    <a href="{{ route('about') }}" target="_blank" class="btn btn-ghost btn-sm">👁 Preview</a>
</div>

<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="sg">

{{-- ══════════════════════════════════════
     1. HERO
══════════════════════════════════════ --}}
<div class="card acc-open" id="s-hero">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">🏠 Hero Section</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="g2col">
            <div class="form-group">
                <label class="form-label">Tag <span style="font-weight:400;color:#94a3b8">(above title)</span></label>
                <input type="text" name="hero_tag" class="form-control" value="{{ old('hero_tag', $about->hero_tag ?? 'About us') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Main Headline</label>
                <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $about->hero_title ?? "Building Tomorrow's Digital Frontier") }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Sub-headline</label>
            <textarea name="hero_subtitle" class="form-control" rows="3">{{ old('hero_subtitle', $about->hero_subtitle ?? 'We are a team of passionate developers building beautiful websites and mobile apps.') }}</textarea>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     2. MISSION
══════════════════════════════════════ --}}
<div class="card acc-open" id="s-mission">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">🎯 Mission</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="form-group">
            <label class="form-label">Mission Heading</label>
            <input type="text" name="mission_title" class="form-control" value="{{ old('mission_title', $about->mission_title ?? 'Our mission') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Paragraph 1</label>
            <textarea name="mission_text_1" class="form-control" rows="5">{{ old('mission_text_1', $about->mission_text_1 ?? '') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Paragraph 2</label>
            <textarea name="mission_text_2" class="form-control" rows="5">{{ old('mission_text_2', $about->mission_text_2 ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     3. STATS
══════════════════════════════════════ --}}
<div class="card" id="s-stats">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">📊 Stats / Numbers</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="form-group">
            <label class="form-label">Section Label</label>
            <input type="text" name="stats_label" class="form-control" value="{{ old('stats_label', $about->stats_label ?? 'THE NUMBERS') }}" style="max-width:280px">
            <small class="form-text">Shown above the stats grid e.g. "THE NUMBERS"</small>
        </div>
        <hr class="divider">
        <div class="g2col">
            <div class="form-group"><label class="form-label">Clients — Number</label><input type="text" name="stat_clients_num" class="form-control" value="{{ old('stat_clients_num', $about->stat_clients_num ?? '70+') }}"></div>
            <div class="form-group"><label class="form-label">Clients — Label</label><input type="text" name="stat_clients_label" class="form-control" value="{{ old('stat_clients_label', $about->stat_clients_label ?? 'Satisfied Clients') }}"></div>
            <div class="form-group"><label class="form-label">Projects — Number</label><input type="text" name="stat_projects_num" class="form-control" value="{{ old('stat_projects_num', $about->stat_projects_num ?? '65+') }}"></div>
            <div class="form-group"><label class="form-label">Projects — Label</label><input type="text" name="stat_projects_label" class="form-control" value="{{ old('stat_projects_label', $about->stat_projects_label ?? 'Projects') }}"></div>
            <div class="form-group"><label class="form-label">Satisfaction — Number</label><input type="text" name="stat_satisfaction_num" class="form-control" value="{{ old('stat_satisfaction_num', $about->stat_satisfaction_num ?? '99.5%') }}"></div>
            <div class="form-group"><label class="form-label">Satisfaction — Label</label><input type="text" name="stat_satisfaction_label" class="form-control" value="{{ old('stat_satisfaction_label', $about->stat_satisfaction_label ?? 'Satisfaction Rate') }}"></div>
            <div class="form-group"><label class="form-label">Experience — Number</label><input type="text" name="stat_experience_num" class="form-control" value="{{ old('stat_experience_num', $about->stat_experience_num ?? '5+') }}"></div>
            <div class="form-group"><label class="form-label">Experience — Label</label><input type="text" name="stat_experience_label" class="form-control" value="{{ old('stat_experience_label', $about->stat_experience_label ?? 'Years of Experience') }}"></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     4. PHOTOS
══════════════════════════════════════ --}}
<div class="card" id="s-photos">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">🖼️ Team Photos (Collage)</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <p style="font-size:13px;color:#64748b;margin:0 0 16px">3 photos beside the mission text. Photo 1 is wide (top), 2 &amp; 3 are square (bottom row).</p>
        <div class="g3col">
            @foreach([1,2,3] as $n)
            <div class="form-group">
                <label class="form-label">Photo {{ $n }} {{ $n==1 ? '— Wide' : '— Square' }}</label>
                <div class="upl-box" onclick="document.getElementById('ph{{$n}}').click()">
                    <input type="file" id="ph{{$n}}" name="photo_{{$n}}" accept="image/*" onchange="uplPrev(this,'pi{{$n}}')">
                    @if(!empty($about->{'photo_'.$n}))
                        <img id="pi{{$n}}" class="upl-prev" src="{{ asset('storage/'.$about->{'photo_'.$n}) }}" alt="">
                    @else
                        <div id="pi{{$n}}" style="font-size:2rem;margin-bottom:8px">📷</div>
                    @endif
                    <div class="upl-hint"><strong>Click to upload</strong><br>JPG · PNG · WEBP</div>
                </div>
                @if(!empty($about->{'photo_'.$n}))
                <label class="rm-chk"><input type="checkbox" name="remove_photo_{{$n}}" value="1"> Remove photo</label>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     5. AWARDS & MILESTONES  (full width)
══════════════════════════════════════ --}}
<div class="card full" id="s-awards">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">🏆 Awards &amp; Milestones</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="g3col" style="margin-bottom:16px">
            <div class="form-group"><label class="form-label">Section Tag</label><input type="text" name="milestones_tag" class="form-control" value="{{ old('milestones_tag', $about->milestones_tag ?? 'Milestones That Matter') }}"></div>
            <div class="form-group"><label class="form-label">Section Title</label><input type="text" name="milestones_title" class="form-control" value="{{ old('milestones_title', $about->milestones_title ?? 'Our Journey of Impact') }}"></div>
            <div class="form-group"><label class="form-label">Sub-text</label><input type="text" name="milestones_subtitle" class="form-control" value="{{ old('milestones_subtitle', $about->milestones_subtitle ?? "From startups to enterprises, we've empowered businesses worldwide.") }}"></div>
        </div>
        <hr class="divider">
        <p class="sub-lbl">Award Items</p>
        <div id="list-awards">
            @foreach($awards as $i => $award)
            <div class="rep-item open">
                <input type="hidden" name="awards[{{$i}}][id]" value="{{ $award->id }}">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">🏆 {{ $award->platform }} — {{ $award->year }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="delItem(event,'frm-del-award',{{ $award->id }})">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Platform</label><input type="text" name="awards[{{$i}}][platform]" class="form-control" value="{{ $award->platform }}"></div>
                        <div class="form-group"><label class="form-label">Achievement</label><input type="text" name="awards[{{$i}}][achievement]" class="form-control" value="{{ $award->achievement }}"></div>
                        <div class="form-group"><label class="form-label">Year</label><input type="text" name="awards[{{$i}}][year]" class="form-control" value="{{ $award->year }}" maxlength="10"></div>
                    </div>
                    <div class="g2col">
                        <div class="form-group">
                            <label class="form-label">Logo (optional)</label>
                            <div class="upl-box" onclick="document.getElementById('al{{$award->id}}').click()">
                                <input type="file" id="al{{$award->id}}" name="awards[{{$i}}][logo]" accept="image/*" onchange="uplPrev(this,'ap{{$award->id}}')">
                                @if($award->logo_path)<img id="ap{{$award->id}}" class="upl-prev" src="{{ asset('storage/'.$award->logo_path) }}" alt="">@else<div id="ap{{$award->id}}" style="font-size:1.5rem;margin-bottom:4px">🏅</div>@endif
                                <div class="upl-hint"><strong>Upload logo</strong></div>
                            </div>
                            @if($award->logo_path)<label class="rm-chk"><input type="checkbox" name="awards[{{$i}}][remove_logo]" value="1"> Remove logo</label>@endif
                        </div>
                        <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="awards[{{$i}}][sort_order]" class="form-control" value="{{ $award->sort_order }}" min="0" style="max-width:100px"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <template id="tpl-award">
            <div class="rep-item open">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">🏆 New Award</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.rep-item').remove()">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Platform</label><input type="text" name="new_awards[][platform]" class="form-control" placeholder="e.g. Clutch"></div>
                        <div class="form-group"><label class="form-label">Achievement</label><input type="text" name="new_awards[][achievement]" class="form-control" placeholder="Top Agency 2025"></div>
                        <div class="form-group"><label class="form-label">Year</label><input type="text" name="new_awards[][year]" class="form-control" placeholder="2025" maxlength="10"></div>
                    </div>
                </div>
            </div>
        </template>
        <button type="button" class="rep-add" onclick="repAdd('list-awards','tpl-award')">+ Add Award</button>
    </div>
</div>

{{-- ══════════════════════════════════════
     6. CORE VALUES  (full width)
══════════════════════════════════════ --}}
<div class="card full" id="s-values">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">💎 Core Values</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="g3col" style="margin-bottom:16px">
            <div class="form-group"><label class="form-label">Section Tag</label><input type="text" name="values_tag" class="form-control" value="{{ old('values_tag', $about->values_tag ?? 'What Drives Us') }}"></div>
            <div class="form-group"><label class="form-label">Section Title</label><input type="text" name="values_title" class="form-control" value="{{ old('values_title', $about->values_title ?? 'Our Core Values') }}"></div>
            <div class="form-group"><label class="form-label">Sub-text</label><input type="text" name="values_subtitle" class="form-control" value="{{ old('values_subtitle', $about->values_subtitle ?? '') }}"></div>
        </div>
        <hr class="divider">
        <p class="sub-lbl">Value Items</p>
        <div id="list-values">
            @foreach($values as $i => $val)
            <div class="rep-item open">
                <input type="hidden" name="values[{{$i}}][id]" value="{{ $val->id }}">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">{{ $val->icon }} {{ $val->title }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="delItem(event,'frm-del-value',{{ $val->id }})">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Icon (emoji)</label><input type="text" name="values[{{$i}}][icon]" class="form-control" value="{{ $val->icon }}" style="max-width:80px"></div>
                        <div class="form-group"><label class="form-label">Title</label><input type="text" name="values[{{$i}}][title]" class="form-control" value="{{ $val->title }}"></div>
                        <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="values[{{$i}}][sort_order]" class="form-control" value="{{ $val->sort_order }}" min="0" style="max-width:80px"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="values[{{$i}}][description]" class="form-control" rows="2">{{ $val->description }}</textarea></div>
                </div>
            </div>
            @endforeach
        </div>
        <template id="tpl-value">
            <div class="rep-item open">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">✨ New Value</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.rep-item').remove()">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Icon (emoji)</label><input type="text" name="new_values[][icon]" class="form-control" placeholder="🚀" style="max-width:80px"></div>
                        <div class="form-group"><label class="form-label">Title</label><input type="text" name="new_values[][title]" class="form-control" placeholder="Be world-class"></div>
                        <div class="form-group"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="new_values[][description]" class="form-control" rows="2" placeholder="Describe this value..."></textarea></div>
                </div>
            </div>
        </template>
        <button type="button" class="rep-add" onclick="repAdd('list-values','tpl-value')">+ Add Value</button>
    </div>
</div>

{{-- ══════════════════════════════════════
     7. FOUNDERS  (full width)
══════════════════════════════════════ --}}
<div class="card full" id="s-founders">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">👤 Founders</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <p style="font-size:13px;color:#64748b;margin:0 0 14px">Founders appear alternating left/right on the about page.</p>
        <div id="list-founders">
            @foreach($founders as $i => $founder)
            <div class="rep-item open">
                <input type="hidden" name="founders[{{$i}}][id]" value="{{ $founder->id }}">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">👤 {{ $founder->name }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="delItem(event,'frm-del-founder',{{ $founder->id }})">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="founders[{{$i}}][name]" class="form-control" value="{{ $founder->name }}"></div>
                        <div class="form-group"><label class="form-label">Company / Brand</label><input type="text" name="founders[{{$i}}][company]" class="form-control" value="{{ $founder->company }}"></div>
                        <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="founders[{{$i}}][sort_order]" class="form-control" value="{{ $founder->sort_order }}" min="0" style="max-width:80px"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Bio</label><textarea name="founders[{{$i}}][bio]" class="form-control" rows="3">{{ $founder->bio }}</textarea></div>
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Website URL</label><input type="url" name="founders[{{$i}}][website]" class="form-control" value="{{ $founder->website }}" placeholder="https://"></div>
                        <div class="form-group"><label class="form-label">LinkedIn URL</label><input type="url" name="founders[{{$i}}][linkedin]" class="form-control" value="{{ $founder->linkedin }}" placeholder="https://linkedin.com/in/"></div>
                        <div class="form-group">
                            <label class="form-label">Photo</label>
                            <div class="upl-box" onclick="document.getElementById('fp{{$founder->id}}').click()">
                                <input type="file" id="fp{{$founder->id}}" name="founders[{{$i}}][photo]" accept="image/*" onchange="uplPrev(this,'fpi{{$founder->id}}')">
                                @if($founder->photo)<img id="fpi{{$founder->id}}" class="upl-prev" src="{{ asset('storage/'.$founder->photo) }}" alt="">@else<div id="fpi{{$founder->id}}" style="font-size:2rem;margin-bottom:6px">👤</div>@endif
                                <div class="upl-hint"><strong>Upload photo</strong></div>
                            </div>
                            @if($founder->photo)<label class="rm-chk"><input type="checkbox" name="founders[{{$i}}][remove_photo]" value="1"> Remove</label>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <template id="tpl-founder">
            <div class="rep-item open">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">👤 New Founder</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.rep-item').remove()">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="g3col">
                        <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="new_founders[][name]" class="form-control" placeholder="e.g. John Doe"></div>
                        <div class="form-group"><label class="form-label">Company / Brand</label><input type="text" name="new_founders[][company]" class="form-control" placeholder="SK Artistic"></div>
                        <div class="form-group"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Bio</label><textarea name="new_founders[][bio]" class="form-control" rows="3" placeholder="Short bio..."></textarea></div>
                    <div class="g2col">
                        <div class="form-group"><label class="form-label">Website URL</label><input type="url" name="new_founders[][website]" class="form-control" placeholder="https://"></div>
                        <div class="form-group"><label class="form-label">LinkedIn URL</label><input type="url" name="new_founders[][linkedin]" class="form-control" placeholder="https://linkedin.com/in/"></div>
                    </div>
                </div>
            </div>
        </template>
        <button type="button" class="rep-add" onclick="repAdd('list-founders','tpl-founder')">+ Add Founder</button>
    </div>
</div>

{{-- ══════════════════════════════════════
     8. FAQ  (full width)
══════════════════════════════════════ --}}
<div class="card full" id="s-faq">
    <div class="card-head acc-head" onclick="this.closest('.card').classList.toggle('acc-open')">
        <div class="card-title">❓ FAQ</div>
        <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div class="card-body acc-body">
        <div class="g3col" style="margin-bottom:16px">
            <div class="form-group"><label class="form-label">Section Tag</label><input type="text" name="faq_tag" class="form-control" value="{{ old('faq_tag', $about->faq_tag ?? 'FAQ') }}"></div>
            <div class="form-group"><label class="form-label">Section Title</label><input type="text" name="faq_title" class="form-control" value="{{ old('faq_title', $about->faq_title ?? 'Frequently Asked Questions') }}"></div>
            <div class="form-group"><label class="form-label">Sub-text</label><input type="text" name="faq_subtitle" class="form-control" value="{{ old('faq_subtitle', $about->faq_subtitle ?? 'Learn more about our agency.') }}"></div>
        </div>
        <hr class="divider">
        <p class="sub-lbl">FAQ Items</p>
        <div id="list-faqs">
            @foreach($faqs as $i => $faq)
            <div class="rep-item open">
                <input type="hidden" name="faqs[{{$i}}][id]" value="{{ $faq->id }}">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">Q: {{ Str::limit($faq->question, 70) }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="delItem(event,'frm-del-faq',{{ $faq->id }})">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="form-group"><label class="form-label">Question</label><input type="text" name="faqs[{{$i}}][question]" class="form-control" value="{{ $faq->question }}"></div>
                    <div class="form-group"><label class="form-label">Answer</label><textarea name="faqs[{{$i}}][answer]" class="form-control" rows="3">{{ $faq->answer }}</textarea></div>
                    <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="faqs[{{$i}}][sort_order]" class="form-control" value="{{ $faq->sort_order }}" min="0" style="max-width:100px"></div>
                </div>
            </div>
            @endforeach
        </div>
        <template id="tpl-faq">
            <div class="rep-item open">
                <div class="rep-head" onclick="repToggle(this)">
                    <span class="rep-head-label">Q: New Question</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.rep-item').remove()">Remove</button>
                        <svg class="rep-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="rep-body">
                    <div class="form-group"><label class="form-label">Question</label><input type="text" name="new_faqs[][question]" class="form-control" placeholder="What is...?"></div>
                    <div class="form-group"><label class="form-label">Answer</label><textarea name="new_faqs[][answer]" class="form-control" rows="3" placeholder="The answer..."></textarea></div>
                </div>
            </div>
        </template>
        <button type="button" class="rep-add" onclick="repAdd('list-faqs','tpl-faq')">+ Add FAQ</button>
    </div>
</div>

</div>{{-- /.sg --}}

{{-- Save bar — matches General Settings bottom button style --}}
<div style="margin-top:16px;display:flex;gap:12px;align-items:center">
    <button type="submit" class="btn btn-primary" style="padding:13px 36px;font-size:15px">
        💾 Save All Changes
    </button>
    <a href="{{ route('about') }}" target="_blank" class="btn btn-ghost">👁 Preview Page</a>
    @if(session('success'))
        <span style="font-size:13px;color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:8px 16px;border-radius:6px">
            ✓ {{ session('success') }}
        </span>
    @endif
</div>

</form>

{{-- Delete forms --}}
<form id="frm-del-award"   action="{{ route('admin.about.award.delete') }}"   method="POST" style="display:none">@csrf @method('DELETE')<input type="hidden" name="id" id="frm-del-award-id"></form>
<form id="frm-del-value"   action="{{ route('admin.about.value.delete') }}"   method="POST" style="display:none">@csrf @method('DELETE')<input type="hidden" name="id" id="frm-del-value-id"></form>
<form id="frm-del-founder" action="{{ route('admin.about.founder.delete') }}" method="POST" style="display:none">@csrf @method('DELETE')<input type="hidden" name="id" id="frm-del-founder-id"></form>
<form id="frm-del-faq"     action="{{ route('admin.about.faq.delete') }}"     method="POST" style="display:none">@csrf @method('DELETE')<input type="hidden" name="id" id="frm-del-faq-id"></form>

<script>
/* Accordion for .card sections */
/* (handled inline via onclick classList.toggle — no JS needed) */

/* Repeater item toggle */
function repToggle(head) {
    head.closest('.rep-item').classList.toggle('open');
}

/* Add new repeater item from template */
function repAdd(listId, tplId) {
    var clone = document.getElementById(tplId).content.cloneNode(true);
    document.getElementById(listId).appendChild(clone);
}

/* Delete existing item via hidden form */
function delItem(e, formId, id) {
    e.stopPropagation();
    if (!confirm('Delete this item? This cannot be undone.')) return;
    document.getElementById(formId + '-id').value = id;
    document.getElementById(formId).submit();
}

/* Image upload preview */
function uplPrev(input, previewId) {
    if (!input.files || !input.files[0]) return;
    var r = new FileReader();
    r.onload = function(e) {
        var el = document.getElementById(previewId);
        if (!el) return;
        if (el.tagName === 'IMG') {
            el.src = e.target.result;
        } else {
            var img = document.createElement('img');
            img.id = previewId; img.className = 'upl-prev'; img.src = e.target.result;
            el.parentNode.replaceChild(img, el);
        }
    };
    r.readAsDataURL(input.files[0]);
}
</script>

@endsection