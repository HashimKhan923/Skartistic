@extends('frontend.layouts.app')
@section('title',$project->title.' — Portfolio — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('portfolio') }}">Portfolio</a><span class="sep">/</span><span>{{ $project->title }}</span></div>
    @if($project->category)<div style="display:inline-block;background:var(--cyan);color:var(--void);font-family:var(--font-ui);font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;margin-bottom:20px">{{ $project->category }}</div>@endif
    <h1>{{ strtoupper($project->title) }}</h1>
    @if($project->client)<p>Client — {{ $project->client }}</p>@endif
  </div>
</div>
<section class="section" style="padding-top:60px">
  <div class="container">
    @if($project->banner_image ?? $project->thumbnail)
    <div class="reveal" style="margin-bottom:64px;overflow:hidden;border:1px solid var(--rim)">
      <img src="{{ asset('storage/'.($project->banner_image??$project->thumbnail)) }}" alt="{{ $project->title }}" style="width:100%;max-height:580px;object-fit:cover;display:block">
    </div>
    @endif
    <div class="split-side">
      <div class="reveal-l">
        @if($project->description)
        <div style="font-size:16px;color:var(--muted);line-height:1.9;font-weight:300;margin-bottom:48px">{!! nl2br(e($project->description)) !!}</div>
        @endif
        @if($project->content)
        <div class="blog-content" style="margin-bottom:48px">{!! $project->content !!}</div>
        @endif
        @if($project->images && count($project->images))
        <h3 style="font-family:var(--font-d);font-size:2rem;letter-spacing:1px;color:var(--text);margin-bottom:28px">PROJECT <span style="background:linear-gradient(135deg,var(--cyan),var(--violet));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">GALLERY.</span></h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--rim);border:1px solid var(--rim);margin-bottom:48px">
          @foreach($project->images as $img)
          <button type="button" class="gallery-btn" aria-label="Enlarge image" data-cursor="Zoom" onclick="openLightbox('{{ asset('storage/'.$img) }}')"><img src="{{ asset('storage/'.$img) }}" alt="{{ $project->title }}" loading="lazy"></button>
          @endforeach
        </div>
        @endif
        @if($project->testimonial)
        <div style="background:var(--panel);border:1px solid var(--rim);border-left:2px solid var(--cyan);padding:36px 40px">
          <div style="display:flex;gap:4px;margin-bottom:16px"><span style="color:var(--cyan);text-shadow:0 0 8px rgba(var(--accent-rgb),.4)">★★★★★</span></div>
          <p style="font-family:var(--font-ui);font-size:1.1rem;font-style:italic;color:var(--muted);line-height:1.8;margin-bottom:24px;font-weight:400">"{{ $project->testimonial }}"</p>
          @if($project->testimonial_name)<div style="font-family:var(--font-ui);font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text);font-size:.9rem">— {{ $project->testimonial_name }}</div>@endif
        </div>
        @endif
      </div>
      <div class="reveal-r sticky-side">
        <div style="background:var(--panel);border:1px solid var(--rim);border-top:2px solid var(--cyan);padding:32px;margin-bottom:12px">
          <div style="font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--cyan);margin-bottom:20px;opacity:.8">Project Details</div>
          <dl style="display:flex;flex-direction:column;gap:0">
            @if($project->client)<div style="padding:14px 0;border-bottom:1px solid var(--rim);display:flex;flex-direction:column;gap:4px"><dt style="font-size:10px;color:var(--dim);letter-spacing:2.5px;text-transform:uppercase">Client</dt><dd style="font-size:14px;color:var(--text)">{{ $project->client }}</dd></div>@endif
            @if($project->category)<div style="padding:14px 0;border-bottom:1px solid var(--rim);display:flex;flex-direction:column;gap:4px"><dt style="font-size:10px;color:var(--dim);letter-spacing:2.5px;text-transform:uppercase">Category</dt><dd style="font-size:14px;color:var(--text)">{{ $project->category }}</dd></div>@endif
            @if($project->completed_at??$project->created_at)<div style="padding:14px 0;border-bottom:1px solid var(--rim);display:flex;flex-direction:column;gap:4px"><dt style="font-size:10px;color:var(--dim);letter-spacing:2.5px;text-transform:uppercase">Year</dt><dd style="font-size:14px;color:var(--text)">{{ ($project->completed_at??$project->created_at)->format('Y') }}</dd></div>@endif
            @php $tags = array_values(array_filter(array_map('trim', explode(',', (string) $project->tags)))); @endphp
            @if(count($tags))<div style="padding:14px 0;border-bottom:1px solid var(--rim);display:flex;flex-direction:column;gap:8px"><dt style="font-size:10px;color:var(--dim);letter-spacing:2.5px;text-transform:uppercase">Stack</dt><dd style="display:flex;flex-wrap:wrap;gap:6px">@foreach($tags as $t)<span style="font-size:11px;padding:4px 10px;border:1px solid rgba(var(--accent-rgb),.2);color:var(--cyan)">{{ $t }}</span>@endforeach</dd></div>@endif
            @if($project->project_url)<div style="padding:14px 0;display:flex;flex-direction:column;gap:4px"><dt style="font-size:10px;color:var(--dim);letter-spacing:2.5px;text-transform:uppercase">Live URL</dt><dd><a href="{{ $project->project_url }}" target="_blank" rel="noopener" style="font-size:14px;color:var(--cyan)">{{ Str::limit(str_replace(['https://','http://'],'',$project->project_url),28) }} ↗</a></dd></div>@endif
          </dl>
        </div>
        <div style="background:var(--panel);border:1px solid var(--rim);padding:28px">
          <h4 style="font-family:var(--font-d);font-size:1.4rem;letter-spacing:1px;color:var(--text);margin-bottom:8px">LIKE THIS WORK?</h4>
          <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin-bottom:20px;font-weight:300">Let's build your next project together.</p>
          <a href="{{ route('contact') }}" class="btn-primary" style="display:flex;width:100%;justify-content:center"><span>Start a Project</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
      </div>
    </div>
    @if(isset($related_projects) && $related_projects->count())
    <div style="margin-top:80px" class="reveal">
      <div style="display:flex;align-items:end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px">
        <div><div class="s-tag">More Work</div><h3 style="font-family:var(--font-d);font-size:3rem;letter-spacing:1px;color:var(--text)">RELATED <span style="background:linear-gradient(135deg,var(--violet),var(--magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">PROJECTS.</span></h3></div>
        <a href="{{ route('portfolio') }}" class="btn-ghost">All Projects <span class="arr">→</span></a>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1px;background:var(--rim);border:1px solid var(--rim);border-radius:12px;overflow:hidden">
        @foreach($related_projects as $rel)
        <a href="{{ route('portfolio.detail',$rel->slug) }}" class="port-item" data-cursor="View" style="height:220px;display:block">
          @if($rel->thumbnail)<img src="{{ asset('storage/'.$rel->thumbnail) }}" alt="{{ $rel->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .5s">@else<div class="port-ph">🎨</div>@endif
          <div class="port-overlay"></div>
          <div class="port-info"><div class="port-title">{{ $rel->title }}</div></div>
        </a>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
<!-- Lightbox -->
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Image preview" onclick="if(event.target===this)closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:99999;align-items:center;justify-content:center">
  <img id="lb-img" alt="" style="max-width:90vw;max-height:90vh;object-fit:contain">
  <button type="button" class="lb-close" aria-label="Close preview" onclick="closeLightbox()">✕</button>
</div>
@endsection