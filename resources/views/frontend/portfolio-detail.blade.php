@extends('frontend.layouts.app')
@section('title',$project->title.' — Portfolio — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('portfolio') }}">Portfolio</a><span class="sep">/</span><span>{{ $project->title }}</span></div>
    @if($project->category)<div class="pd-badge">{{ $project->category }}</div>@endif
    <h1>{{ $project->title }}</h1>
    @if($project->client)<p>Client — {{ $project->client }}</p>@endif
  </div>
</div>
<section class="section" style="padding-top:60px">
  <div class="container">
    @if($project->banner_image ?? $project->thumbnail)
    <div class="reveal" style="margin-bottom:64px;overflow:hidden;border:1px solid var(--rim);border-radius:22px;box-shadow:var(--shadow-lg)">
      <img src="{{ asset('storage/'.($project->banner_image??$project->thumbnail)) }}" alt="{{ $project->title }}" style="width:100%;height:auto;display:block">
    </div>
    @endif
    <div class="split-side">
      <div class="reveal-l">
        @if($project->description)
        <div style="font-size:16px;color:var(--muted);line-height:1.9;font-weight:400;margin-bottom:48px">{!! nl2br(e($project->description)) !!}</div>
        @endif
        @if($project->content)
        <div class="blog-content" style="margin-bottom:48px">{!! $project->content !!}</div>
        @endif
        @if($project->images && count($project->images))
        <h3 style="font-family:var(--font-d);font-size:2rem;letter-spacing:1px;color:var(--text);margin-bottom:28px">Project <span class="g">gallery.</span></h3>
        <div class="pd-gallery">
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
      <div class="reveal-r sticky-side pd-side">
        <div class="pd-card">
          <div class="pd-card-title">Project details</div>
          <dl>
            @if($project->client)<div class="pd-row"><span class="pd-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span><div><dt>Client</dt><dd>{{ $project->client }}</dd></div></div>@endif
            @if($project->category)<div class="pd-row"><span class="pd-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h7l2 3h9v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></span><div><dt>Category</dt><dd>{{ $project->category }}</dd></div></div>@endif
            @if($project->completed_at??$project->created_at)<div class="pd-row"><span class="pd-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span><div><dt>Year</dt><dd>{{ ($project->completed_at??$project->created_at)->format('Y') }}</dd></div></div>@endif
            @php $tags = array_values(array_filter(array_map('trim', explode(',', (string) $project->tags)))); @endphp
            @if(count($tags))<div class="pd-row"><span class="pd-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 8-5 4 5 4M16 8l5 4-5 4M14 4l-4 16"/></svg></span><div><dt>Stack</dt><dd class="pd-chips">@foreach($tags as $t)<span>{{ $t }}</span>@endforeach</dd></div></div>@endif
            @if($project->project_url)<div class="pd-row"><span class="pd-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg></span><div><dt>Live site</dt><dd><a class="pd-live" href="{{ $project->project_url }}" target="_blank" rel="noopener">{{ Str::limit(rtrim(str_replace(['https://','http://','www.'],'',$project->project_url),'/'),28) }} ↗</a></dd></div></div>@endif
          </dl>
        </div>
        <div class="pd-cta">
          <h4>Like this work?</h4>
          <p>Let's build your next project together.</p>
          <a href="{{ route('contact') }}">Start a Project <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
      </div>
    </div>
    @if(isset($related_projects) && $related_projects->count())
    <div style="margin-top:80px" class="reveal">
      <div style="display:flex;align-items:end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px">
        <div><div class="s-tag">More Work</div><h3 style="font-size:var(--h-size);font-weight:800;letter-spacing:-.025em;color:var(--text)">Related <span class="g">projects.</span></h3></div>
        <a href="{{ route('portfolio') }}" class="btn-ghost">All Projects <span class="arr">→</span></a>
      </div>
      <div class="project-grid is-three">
        @foreach($related_projects as $rel)
        @include('frontend.partials.project-card', ['project' => $rel])
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