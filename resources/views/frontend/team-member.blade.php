@extends('frontend.layouts.app')
@section('title', $member->name.' — '.($settings['site_name'] ?? 'SK Artistic'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $member->bio) ?: $member->position, 160))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('team') }}">Team</a><span class="sep">/</span><span>{{ $member->name }}</span></div>
    <h1>{{ strtoupper($member->name) }}</h1>
    <p>{{ $member->position }}</p>
  </div>
</div>

<section class="section" style="padding-top:64px">
  <div class="container">
    <div class="profile-grid">
      <aside class="profile-side reveal-l">
        <div class="profile-photo">
          @if($member->photo)
            <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}">
          @else
            <div class="profile-ph" aria-hidden="true">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
          @endif
        </div>
        <div class="profile-id">
          <h2>{{ $member->name }}</h2>
          <div class="team-pos">{{ $member->position }}</div>
          @if(count($member->socialLinks()))
            <div class="profile-label">Find me on</div>
            @include('frontend.partials.social-links', ['links' => $member->socialLinks(), 'variant' => 'on-light'])
          @endif
        </div>
      </aside>

      <div class="profile-main">
        @if($member->bio)
        <div class="profile-block reveal">
          <div class="s-tag">About</div>
          <div class="profile-bio">{!! nl2br(e($member->bio)) !!}</div>
        </div>
        @endif

        @if(!empty($member->expertise))
        <div class="profile-block reveal">
          <div class="s-tag">Expertise</div>
          <div class="profile-tags">@foreach($member->expertise as $item)<span>{{ $item }}</span>@endforeach</div>
        </div>
        @endif

        @if(!empty($member->education))
        <div class="profile-block reveal">
          <div class="s-tag">Education</div>
          <ul class="profile-list">@foreach($member->education as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        @endif

        @if(!empty($member->achievements))
        <div class="profile-block reveal">
          <div class="s-tag">Certifications &amp; Achievements</div>
          <ul class="profile-list">@foreach($member->achievements as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        @endif

        @if(!empty($member->gallery))
        <div class="profile-block reveal">
          <div class="s-tag">Gallery</div>
          <div class="profile-gallery">
            @foreach($member->gallery as $image)
            <button type="button" class="gallery-btn" aria-label="Enlarge image" data-cursor="Zoom" onclick="openLightbox('{{ asset('storage/'.$image) }}')"><img src="{{ asset('storage/'.$image) }}" alt="{{ $member->name }}" loading="lazy"></button>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>

    <div class="reveal" style="margin-top:64px">
      <a href="{{ route('team') }}" class="btn-ghost">Back to the team <span class="arr">→</span></a>
    </div>
  </div>
</section>

<div id="lightbox" role="dialog" aria-modal="true" aria-label="Image preview" onclick="if(event.target===this)closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:99999;align-items:center;justify-content:center">
  <img id="lb-img" alt="" style="max-width:90vw;max-height:90vh;object-fit:contain">
  <button type="button" class="lb-close" aria-label="Close preview" onclick="closeLightbox()">✕</button>
</div>
@endsection
