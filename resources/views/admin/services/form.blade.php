@extends('admin.layouts.app')
@section('title', isset($service) ? 'Edit Service' : 'Add Service')
@section('content')
<style>
/* ── bound content ── */
.main .content { box-sizing:border-box; width:100%; overflow-x:hidden; }

/* ── grid ── */
.sg     { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.sg .full { grid-column:1/-1; }
.g2col  { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.g3col  { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }

/* ── repeater ── */
.rep-item   { border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:12px; }
.rep-head   { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:#f8fafc; cursor:pointer; user-select:none; }
.rep-head-title { font-size:13px; font-weight:700; color:#334155; }
.rep-body   { padding:18px 16px; border-top:1px solid #e2e8f0; display:none; }
.rep-body.open { display:block; }
.acc-chev   { transition:transform .25s; font-size:11px; color:#94a3b8; }
.rep-item.open .acc-chev { transform:rotate(180deg); }
.rep-add    { width:100%; padding:12px; border:1.5px dashed #c7d2fe; border-radius:10px; background:none; color:#7c3aed; font-size:13px; font-weight:600; cursor:pointer; transition:all .2s; }
.rep-add:hover { background:rgba(124,58,237,.05); }

/* ── sub label ── */
.sub-lbl { font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1.2px; margin:0 0 10px; }

/* ── upload ── */
.upl-box  { border:2px dashed #e2e8f0; border-radius:10px; padding:18px 14px; text-align:center; cursor:pointer; position:relative; transition:border-color .2s; background:#fafbfc; }
.upl-box:hover { border-color:#7c3aed; }
.upl-box input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.upl-prev { width:80px; height:60px; border-radius:8px; object-fit:cover; margin:0 auto 8px; display:block; border:1px solid #e2e8f0; }
.upl-hint { font-size:12px; color:#94a3b8; }
.upl-hint strong { color:#7c3aed; }

/* ── tech category nested ── */
.tc-wrap  { border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-bottom:10px; background:#fafcff; }
.tc-items { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; min-height:32px; }
.tc-item  { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; background:#f1f5f9; border-radius:6px; font-size:12px; font-weight:600; }
.tc-item-rm { cursor:pointer; color:#ef4444; font-size:10px; }
.tc-add-row { display:flex; gap:8px; margin-top:8px; }
.tc-add-row input { flex:1; }

@media(max-width:900px){
    .sg,.g2col,.g3col { grid-template-columns:1fr; }
}
</style>

<form method="POST"
      action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
      enctype="multipart/form-data"
      id="sv-form">
    @csrf @if(isset($service)) @method('PUT') @endif

    {{-- Page header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
        <div>
            <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0">
                {{ isset($service) ? '✏️ Edit Service' : '➕ Add Service' }}
            </h2>
            <p style="font-size:13px;color:#64748b;margin:2px 0 0">Manage all sections of this service page</p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.services.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            @if(isset($service))
            <a href="{{ route('service.detail', $service->slug) }}" target="_blank" class="btn btn-ghost btn-sm">👁 Preview</a>
            @endif
        </div>
    </div>

    <div class="sg">

        {{-- ═══ HERO ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">🚀 Hero Section</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-hero" style="display:block">
                <div class="g2col" style="margin-bottom:14px">
                    <div class="form-group">
                        <label class="form-label">Service Tag Label</label>
                        <input class="form-control" type="text" name="tag_label"
                               value="{{ old('tag_label', $service->tag_label ?? '') }}"
                               placeholder="e.g. Web Development">
                        <div class="form-hint">Purple label above the headline</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Icon / Emoji</label>
                        <input class="form-control" type="text" name="icon"
                               value="{{ old('icon', $service->icon ?? '') }}"
                               placeholder="🌐">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Main Headline <span style="color:#ef4444">*</span></label>
                    <input class="form-control" type="text" name="hero_headline"
                           value="{{ old('hero_headline', $service->hero_headline ?? '') }}"
                           required placeholder="Look no further for your web development needs">
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea class="form-control" name="hero_subtitle" rows="3"
                              placeholder="We build fast, secure, and scalable websites...">{{ old('hero_subtitle', $service->hero_subtitle ?? '') }}</textarea>
                </div>
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">CTA Button 1 Label</label>
                        <input class="form-control" type="text" name="hero_cta_primary"
                               value="{{ old('hero_cta_primary', $service->hero_cta_primary ?? 'Get in touch') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CTA Button 2 Label</label>
                        <input class="form-control" type="text" name="hero_cta_secondary"
                               value="{{ old('hero_cta_secondary', $service->hero_cta_secondary ?? 'Learn more') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ BASICS / OPTIONS ═══ --}}
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Basic Info</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Service Title <span style="color:#ef4444">*</span></label>
                    <input class="form-control" type="text" name="title"
                           value="{{ old('title', $service->title ?? '') }}"
                           required placeholder="Web Development">
                </div>
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input class="form-control" type="text" name="slug"
                           value="{{ old('slug', $service->slug ?? '') }}"
                           placeholder="web-development">
                    <div class="form-hint">Leave blank to auto-generate from title</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" name="short_description" rows="2"
                              placeholder="For meta tags and listings">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                </div>
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input class="form-control" type="number" name="sort_order"
                               value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                    </div>
                    <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end">
                        <label class="form-check" style="margin-bottom:0">
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', $service->is_published ?? true) ? 'checked' : '' }}>
                            <span>Published (visible on site)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ BANNER IMAGE ═══ --}}
        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Banner Image</div></div>
            <div class="card-body">
                <div class="upl-box" onclick="document.getElementById('banner_img').click()">
                    @if(isset($service) && $service->banner_image)
                    <img src="{{ asset('storage/'.$service->banner_image) }}" class="upl-prev" id="banner_prev">
                    @else
                    <img src="" class="upl-prev" id="banner_prev" style="display:none">
                    @endif
                    <input type="file" id="banner_img" name="banner_image" accept="image/*" style="position:absolute;inset:0;opacity:0;cursor:pointer" onchange="uplPrev(this,'banner_prev')">
                    <div class="upl-hint"><strong>Click to upload</strong> or drag & drop<br>PNG, JPG, WEBP</div>
                </div>
            </div>
        </div>

        {{-- ═══ WHAT WE OFFER ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">💡 What We Offer Section</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-offer">
                <div class="g3col" style="margin-bottom:14px">
                    <div class="form-group">
                        <label class="form-label">Section Tag</label>
                        <input class="form-control" type="text" name="offer_tag"
                               value="{{ old('offer_tag', $service->offer_tag ?? 'What we offer') }}">
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Section Title</label>
                        <input class="form-control" type="text" name="offer_title"
                               value="{{ old('offer_title', $service->offer_title ?? '') }}"
                               placeholder="Tailored Web Solutions That Drive Results">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:22px">
                    <label class="form-label">Section Subtitle</label>
                    <textarea class="form-control" name="offer_subtitle" rows="2">{{ old('offer_subtitle', $service->offer_subtitle ?? '') }}</textarea>
                </div>

                <div class="sub-lbl">Feature Cards (shown 3-per-row)</div>
                <div id="offer-features-wrap">
                    @php $offerFeats = old('offer_features_title') ? [] : ($service->offer_features ?? []); @endphp
                    @foreach($offerFeats as $fi => $feat)
                    <div class="rep-item open" id="offer-feat-{{ $fi }}">
                        <div class="rep-head" onclick="repToggle(this)">
                            <span class="rep-head-title">Card {{ $fi+1 }}: {{ $feat['title'] ?? 'Untitled' }}</span>
                            <div style="display:flex;gap:12px;align-items:center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'offer-feat-{{ $fi }}')">✕</button>
                                <span class="acc-chev">▼</span>
                            </div>
                        </div>
                        <div class="rep-body open">
                            <div class="g2col">
                                <div class="form-group">
                                    <label class="form-label">Card Title</label>
                                    <input class="form-control" type="text" name="offer_features[{{ $fi }}][title]"
                                           value="{{ $feat['title'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Emoji / Visual</label>
                                    <input class="form-control" type="text" name="offer_features[{{ $fi }}][emoji]"
                                           value="{{ $feat['emoji'] ?? '' }}" placeholder="📱">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="offer_features[{{ $fi }}][description]" rows="3">{{ $feat['description'] ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Feature Image (optional)</label>
                                <input class="form-control" type="file" name="offer_feature_images[{{ $fi }}]" accept="image/*">
                                @if(!empty($feat['image']))<div style="margin-top:6px"><img src="{{ asset('storage/'.$feat['image']) }}" style="height:60px;border-radius:6px"></div>@endif
                                <input type="hidden" name="offer_features[{{ $fi }}][image]" value="{{ $feat['image'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="rep-add" onclick="addOfferFeat()">+ Add Feature Card</button>
            </div>
        </div>

        {{-- ═══ TECH STACK ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">🛠 Tech Stack Section</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-tech">
                <div class="g3col" style="margin-bottom:14px">
                    <div class="form-group">
                        <label class="form-label">Section Tag</label>
                        <input class="form-control" type="text" name="techstack_tag"
                               value="{{ old('techstack_tag', $service->techstack_tag ?? 'Tech Stack') }}">
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Section Title</label>
                        <input class="form-control" type="text" name="techstack_title"
                               value="{{ old('techstack_title', $service->techstack_title ?? '') }}"
                               placeholder="Technology Behind Every Pixel and Logic">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:22px">
                    <label class="form-label">Section Subtitle</label>
                    <textarea class="form-control" name="techstack_subtitle" rows="2">{{ old('techstack_subtitle', $service->techstack_subtitle ?? '') }}</textarea>
                </div>

                <div class="sub-lbl">Tech Categories (e.g. Frontend, Backend, Database)</div>
                <div id="tech-cats-wrap">
                    @php $techCats = $service->tech_categories ?? []; @endphp
                    @foreach($techCats as $ci => $cat)
                    <div class="tc-wrap" id="tc-cat-{{ $ci }}">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                            <input class="form-control" type="text" name="tech_categories[{{ $ci }}][name]"
                                   value="{{ $cat['name'] ?? '' }}" placeholder="Category name (e.g. Frontend)"
                                   style="flex:1">
                            <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'tc-cat-{{ $ci }}')">✕</button>
                        </div>
                        <div class="sub-lbl">Tech Items</div>
                        <div class="tc-items" id="tc-items-{{ $ci }}">
                            @foreach(($cat['items'] ?? []) as $ii => $item)
                            <span class="tc-item">
                                {{ $item['emoji'] ?? '' }} {{ $item['name'] }}
                                <input type="hidden" name="tech_categories[{{ $ci }}][items][{{ $ii }}][name]" value="{{ $item['name'] }}">
                                <input type="hidden" name="tech_categories[{{ $ci }}][items][{{ $ii }}][emoji]" value="{{ $item['emoji'] ?? '' }}">
                                <span class="tc-item-rm" onclick="this.parentElement.remove()">✕</span>
                            </span>
                            @endforeach
                        </div>
                        <div class="tc-add-row">
                            <input class="form-control" type="text" placeholder="Emoji (e.g. ⚛️)" style="max-width:100px">
                            <input class="form-control" type="text" placeholder="Tech name (e.g. React.js)">
                            <button type="button" class="btn btn-ghost btn-sm" onclick="addTechItem(this,{{ $ci }})">Add</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="rep-add" onclick="addTechCat()">+ Add Tech Category</button>
            </div>
        </div>

        {{-- ═══ WORK PROCESS ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">⚡ Work Process Section</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-process">
                <div class="g3col" style="margin-bottom:14px">
                    <div class="form-group">
                        <label class="form-label">Section Tag</label>
                        <input class="form-control" type="text" name="process_tag"
                               value="{{ old('process_tag', $service->process_tag ?? 'Work Process') }}">
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Section Title</label>
                        <input class="form-control" type="text" name="process_title"
                               value="{{ old('process_title', $service->process_title ?? '') }}"
                               placeholder="Blueprints to Browsers Our Web-Making Ritual">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:22px">
                    <label class="form-label">Section Subtitle</label>
                    <textarea class="form-control" name="process_subtitle" rows="2">{{ old('process_subtitle', $service->process_subtitle ?? '') }}</textarea>
                </div>

                <div class="sub-lbl">Process Steps</div>
                <div id="process-steps-wrap">
                    @php $steps = $service->process_steps ?? []; @endphp
                    @foreach($steps as $si => $step)
                    <div class="rep-item open" id="proc-step-{{ $si }}">
                        <div class="rep-head" onclick="repToggle(this)">
                            <span class="rep-head-title">Step {{ $si+1 }}: {{ $step['title'] ?? 'Untitled' }}</span>
                            <div style="display:flex;gap:12px;align-items:center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'proc-step-{{ $si }}')">✕</button>
                                <span class="acc-chev">▼</span>
                            </div>
                        </div>
                        <div class="rep-body open">
                            <div class="form-group">
                                <label class="form-label">Step Title</label>
                                <input class="form-control" type="text" name="process_steps[{{ $si }}][title]"
                                       value="{{ $step['title'] ?? '' }}" placeholder="Discovery & Planning">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="process_steps[{{ $si }}][description]" rows="3">{{ $step['description'] ?? '' }}</textarea>
                            </div>
                            <div class="sub-lbl">Features (icon + label pairs)</div>
                            <div id="proc-feats-{{ $si }}">
                                @foreach(($step['features'] ?? []) as $pfi => $pf)
                                <div class="g2col" style="margin-bottom:8px" id="proc-feat-{{ $si }}-{{ $pfi }}">
                                    <input class="form-control" type="text" name="process_steps[{{ $si }}][features][{{ $pfi }}][icon]"
                                           value="{{ $pf['icon'] ?? '' }}" placeholder="🔍 icon/emoji">
                                    <div style="display:flex;gap:8px">
                                        <input class="form-control" type="text" name="process_steps[{{ $si }}][features][{{ $pfi }}][label]"
                                               value="{{ $pf['label'] ?? '' }}" placeholder="Requirement Analysis" style="flex:1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'proc-feat-{{ $si }}-{{ $pfi }}')">✕</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="rep-add" style="margin-top:6px" onclick="addProcFeat({{ $si }})">+ Add Feature</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="rep-add" onclick="addProcStep()">+ Add Process Step</button>
            </div>
        </div>

        {{-- ═══ FEATURED WORK ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">🎨 Featured Work Section</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-work">
                <div class="g3col" style="margin-bottom:14px">
                    <div class="form-group">
                        <label class="form-label">Section Tag</label>
                        <input class="form-control" type="text" name="work_tag"
                               value="{{ old('work_tag', $service->work_tag ?? 'Featured Work') }}">
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Section Title</label>
                        <input class="form-control" type="text" name="work_title"
                               value="{{ old('work_title', $service->work_title ?? '') }}"
                               placeholder="From First Sketch to Final Launch Our Magic in Motion">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:22px">
                    <label class="form-label">Section Subtitle</label>
                    <textarea class="form-control" name="work_subtitle" rows="2">{{ old('work_subtitle', $service->work_subtitle ?? '') }}</textarea>
                </div>

                <div class="sub-lbl">Projects (alternate left/right layout)</div>
                <div id="projects-wrap">
                    @php $projs = $service->featured_projects ?? []; @endphp
                    @foreach($projs as $pi => $proj)
                    <div class="rep-item open" id="proj-{{ $pi }}">
                        <div class="rep-head" onclick="repToggle(this)">
                            <span class="rep-head-title">Project {{ $pi+1 }}: {{ $proj['title'] ?? 'Untitled' }}</span>
                            <div style="display:flex;gap:12px;align-items:center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'proj-{{ $pi }}')">✕</button>
                                <span class="acc-chev">▼</span>
                            </div>
                        </div>
                        <div class="rep-body open">
                            <div class="g2col">
                                <div class="form-group">
                                    <label class="form-label">Project Title</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][title]"
                                           value="{{ $proj['title'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Client</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][client]"
                                           value="{{ $proj['client'] ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="featured_projects[{{ $pi }}][description]" rows="3">{{ $proj['description'] ?? '' }}</textarea>
                            </div>
                            <div class="g3col">
                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][role]"
                                           value="{{ $proj['role'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Year</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][year]"
                                           value="{{ $proj['year'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Duration</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][duration]"
                                           value="{{ $proj['duration'] ?? '' }}">
                                </div>
                            </div>
                            <div class="g2col">
                                <div class="form-group">
                                    <label class="form-label">Team</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][team]"
                                           value="{{ $proj['team'] ?? '' }}" placeholder="3 developers, 1 designer">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <input class="form-control" type="text" name="featured_projects[{{ $pi }}][status]"
                                           value="{{ $proj['status'] ?? 'Completed' }}">
                                </div>
                            </div>
                            <div class="g2col">
                                <div class="form-group">
                                    <label class="form-label">Live URL</label>
                                    <input class="form-control" type="url" name="featured_projects[{{ $pi }}][live_url]"
                                           value="{{ $proj['live_url'] ?? '' }}" placeholder="https://...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Case Study URL</label>
                                    <input class="form-control" type="url" name="featured_projects[{{ $pi }}][case_study_url]"
                                           value="{{ $proj['case_study_url'] ?? '' }}" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Key Features (comma-separated)</label>
                                <input class="form-control" type="text"
                                       name="featured_projects[{{ $pi }}][features_raw]"
                                       value="{{ implode(', ', $proj['features'] ?? []) }}"
                                       placeholder="Admin Dashboard, Events Management, Club Member Management">
                                <div class="form-hint">Will be displayed as pill tags</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Project Image</label>
                                <input class="form-control" type="file" name="proj_images[{{ $pi }}]" accept="image/*">
                                @if(!empty($proj['image']))<div style="margin-top:6px"><img src="{{ asset('storage/'.$proj['image']) }}" style="height:60px;border-radius:6px"></div>@endif
                                <input type="hidden" name="featured_projects[{{ $pi }}][image]" value="{{ $proj['image'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="rep-add" onclick="addProject()">+ Add Project</button>
            </div>
        </div>

        {{-- ═══ CTA BAND ═══ --}}
        <div class="card full">
            <div class="card-head" onclick="repToggle(this)" style="cursor:pointer">
                <div class="card-title">📣 CTA Band</div>
                <span class="acc-chev">▼</span>
            </div>
            <div class="card-body" id="sec-cta">
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">CTA Title</label>
                        <input class="form-control" type="text" name="cta_title"
                               value="{{ old('cta_title', $service->cta_title ?? '') }}"
                               placeholder="Ready to Start Your Project?">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CTA Subtitle</label>
                        <input class="form-control" type="text" name="cta_subtitle"
                               value="{{ old('cta_subtitle', $service->cta_subtitle ?? '') }}"
                               placeholder="Let's build something extraordinary together.">
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end .sg --}}

    {{-- Save row --}}
    <div style="margin-top:24px;display:flex;gap:10px;align-items:center">
        <button type="submit" class="btn btn-primary">
            💾 {{ isset($service) ? 'Update Service' : 'Create Service' }}
        </button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-ghost">Cancel</a>
    </div>

</form>

<script>
/* ─── helpers ─── */
function repToggle(head){
    var card = head.closest('.card, .rep-item');
    var body = card.querySelector('.card-body, .rep-body');
    if(!body) return;
    var open = body.style.display !== 'none' && body.style.display !== '';
    body.style.display = open ? 'none' : 'block';
    var chev = head.querySelector('.acc-chev');
    if(chev) chev.style.transform = open ? '' : 'rotate(180deg)';
}
function delRepItem(e, id){
    e.stopPropagation();
    var el = document.getElementById(id);
    if(el) el.remove();
}

/* ─── counters ─── */
var offerFeatCount  = {{ count($service->offer_features ?? []) }};
var techCatCount    = {{ count($service->tech_categories ?? []) }};
var procStepCount   = {{ count($service->process_steps ?? []) }};
var projCount       = {{ count($service->featured_projects ?? []) }};
var procFeatCounts  = {
    @foreach($service->process_steps ?? [] as $si => $step)
    {{ $si }}: {{ count($step['features'] ?? []) }},
    @endforeach
};
var tcItemCounts = {
    @foreach($service->tech_categories ?? [] as $ci => $cat)
    {{ $ci }}: {{ count($cat['items'] ?? []) }},
    @endforeach
};

/* ─── ADD OFFER FEATURE ─── */
function addOfferFeat(){
    var idx = offerFeatCount++;
    var html = `
    <div class="rep-item open" id="offer-feat-${idx}">
        <div class="rep-head" onclick="repToggle(this)">
            <span class="rep-head-title">Card ${idx+1}: New Feature</span>
            <div style="display:flex;gap:12px;align-items:center">
                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'offer-feat-${idx}')">✕</button>
                <span class="acc-chev" style="transform:rotate(180deg)">▼</span>
            </div>
        </div>
        <div class="rep-body open">
            <div class="g2col">
                <div class="form-group">
                    <label class="form-label">Card Title</label>
                    <input class="form-control" type="text" name="offer_features[${idx}][title]" placeholder="Mobile friendly">
                </div>
                <div class="form-group">
                    <label class="form-label">Emoji / Visual</label>
                    <input class="form-control" type="text" name="offer_features[${idx}][emoji]" placeholder="📱">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="offer_features[${idx}][description]" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Feature Image (optional)</label>
                <input class="form-control" type="file" name="offer_feature_images[${idx}]" accept="image/*">
                <input type="hidden" name="offer_features[${idx}][image]" value="">
            </div>
        </div>
    </div>`;
    document.getElementById('offer-features-wrap').insertAdjacentHTML('beforeend', html);
}

/* ─── ADD TECH CATEGORY ─── */
function addTechCat(){
    var ci = techCatCount++;
    tcItemCounts[ci] = 0;
    var html = `
    <div class="tc-wrap" id="tc-cat-${ci}">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
            <input class="form-control" type="text" name="tech_categories[${ci}][name]" placeholder="Category name (e.g. Frontend)" style="flex:1">
            <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'tc-cat-${ci}')">✕</button>
        </div>
        <div class="sub-lbl">Tech Items</div>
        <div class="tc-items" id="tc-items-${ci}"></div>
        <div class="tc-add-row">
            <input class="form-control" type="text" placeholder="Emoji" style="max-width:100px">
            <input class="form-control" type="text" placeholder="Tech name">
            <button type="button" class="btn btn-ghost btn-sm" onclick="addTechItem(this,${ci})">Add</button>
        </div>
    </div>`;
    document.getElementById('tech-cats-wrap').insertAdjacentHTML('beforeend', html);
}

function addTechItem(btn, ci){
    var row   = btn.closest('.tc-add-row');
    var emoji = row.querySelector('input:nth-child(1)').value.trim();
    var name  = row.querySelector('input:nth-child(2)').value.trim();
    if(!name) return;
    var ii = tcItemCounts[ci] === undefined ? 0 : tcItemCounts[ci]++;
    if(tcItemCounts[ci] === undefined) tcItemCounts[ci] = 1;
    var html = `<span class="tc-item">
        ${emoji} ${name}
        <input type="hidden" name="tech_categories[${ci}][items][${ii}][name]" value="${name}">
        <input type="hidden" name="tech_categories[${ci}][items][${ii}][emoji]" value="${emoji}">
        <span class="tc-item-rm" onclick="this.parentElement.remove()">✕</span>
    </span>`;
    document.getElementById('tc-items-'+ci).insertAdjacentHTML('beforeend', html);
    row.querySelector('input:nth-child(1)').value = '';
    row.querySelector('input:nth-child(2)').value = '';
}

/* ─── ADD PROCESS STEP ─── */
function addProcStep(){
    var si = procStepCount++;
    procFeatCounts[si] = 0;
    var html = `
    <div class="rep-item open" id="proc-step-${si}">
        <div class="rep-head" onclick="repToggle(this)">
            <span class="rep-head-title">Step ${si+1}: New Step</span>
            <div style="display:flex;gap:12px;align-items:center">
                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'proc-step-${si}')">✕</button>
                <span class="acc-chev" style="transform:rotate(180deg)">▼</span>
            </div>
        </div>
        <div class="rep-body open">
            <div class="form-group">
                <label class="form-label">Step Title</label>
                <input class="form-control" type="text" name="process_steps[${si}][title]" placeholder="Discovery & Planning">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="process_steps[${si}][description]" rows="3"></textarea>
            </div>
            <div class="sub-lbl">Features (icon + label pairs)</div>
            <div id="proc-feats-${si}"></div>
            <button type="button" class="rep-add" style="margin-top:6px" onclick="addProcFeat(${si})">+ Add Feature</button>
        </div>
    </div>`;
    document.getElementById('process-steps-wrap').insertAdjacentHTML('beforeend', html);
}

function addProcFeat(si){
    var pfi = procFeatCounts[si] === undefined ? 0 : procFeatCounts[si]++;
    if(procFeatCounts[si] === undefined) procFeatCounts[si] = 1;
    var id = 'proc-feat-'+si+'-'+pfi;
    var html = `
    <div class="g2col" style="margin-bottom:8px" id="${id}">
        <input class="form-control" type="text" name="process_steps[${si}][features][${pfi}][icon]" placeholder="🔍 icon/emoji">
        <div style="display:flex;gap:8px">
            <input class="form-control" type="text" name="process_steps[${si}][features][${pfi}][label]" placeholder="Feature label" style="flex:1">
            <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'${id}')">✕</button>
        </div>
    </div>`;
    document.getElementById('proc-feats-'+si).insertAdjacentHTML('beforeend', html);
}

/* ─── ADD PROJECT ─── */
function addProject(){
    var pi = projCount++;
    var html = `
    <div class="rep-item open" id="proj-${pi}">
        <div class="rep-head" onclick="repToggle(this)">
            <span class="rep-head-title">Project ${pi+1}: New Project</span>
            <div style="display:flex;gap:12px;align-items:center">
                <button type="button" class="btn btn-danger btn-sm" onclick="delRepItem(event,'proj-${pi}')">✕</button>
                <span class="acc-chev" style="transform:rotate(180deg)">▼</span>
            </div>
        </div>
        <div class="rep-body open">
            <div class="g2col">
                <div class="form-group">
                    <label class="form-label">Project Title</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][title]">
                </div>
                <div class="form-group">
                    <label class="form-label">Client</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][client]">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="featured_projects[${pi}][description]" rows="3"></textarea>
            </div>
            <div class="g3col">
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][role]">
                </div>
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][year]">
                </div>
                <div class="form-group">
                    <label class="form-label">Duration</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][duration]">
                </div>
            </div>
            <div class="g2col">
                <div class="form-group">
                    <label class="form-label">Team</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][team]" placeholder="3 developers, 1 designer">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <input class="form-control" type="text" name="featured_projects[${pi}][status]" value="Completed">
                </div>
            </div>
            <div class="g2col">
                <div class="form-group">
                    <label class="form-label">Live URL</label>
                    <input class="form-control" type="url" name="featured_projects[${pi}][live_url]" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label class="form-label">Case Study URL</label>
                    <input class="form-control" type="url" name="featured_projects[${pi}][case_study_url]" placeholder="https://...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Key Features (comma-separated)</label>
                <input class="form-control" type="text" name="featured_projects[${pi}][features_raw]"
                       placeholder="Admin Dashboard, Events Management">
                <div class="form-hint">Will be displayed as pill tags</div>
            </div>
            <div class="form-group">
                <label class="form-label">Project Image</label>
                <input class="form-control" type="file" name="proj_images[${pi}]" accept="image/*">
                <input type="hidden" name="featured_projects[${pi}][image]" value="">
            </div>
        </div>
    </div>`;
    document.getElementById('projects-wrap').insertAdjacentHTML('beforeend', html);
}

/* ─── Upload preview ─── */
function uplPrev(input, prevId){
    var prev = document.getElementById(prevId);
    if(input.files && input.files[0] && prev){
        var r = new FileReader();
        r.onload = function(e){ prev.src = e.target.result; prev.style.display='block'; };
        r.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection