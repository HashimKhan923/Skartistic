@extends('frontend.layouts.app')
@section('title','Our Team — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Team</span></div>
    <h1>THE <span class="g2">CREW.</span></h1>
    <p>Talented humans behind every pixel, line of code, and big idea that ships.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div style="margin-bottom:64px" class="reveal-l">
      <div class="s-tag">Our People</div>
      <h2 class="s-h">MEET THE <span class="g">TEAM.</span></h2>
    </div>
    <div class="team-grid">
      @foreach($team as $member)
      <div class="team-card">
        <div class="team-img">
          @if($member->photo)<img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}">@else<div class="team-img-ph">👤</div>@endif
          <div class="team-overlay"></div>
          <div class="team-socials">
            @if($member->linkedin)<a href="{{ $member->linkedin }}" class="team-soc" target="_blank">LinkedIn</a>@endif
            @if($member->instagram)<a href="{{ $member->instagram }}" class="team-soc" target="_blank">IG</a>@endif
            @if($member->twitter)<a href="{{ $member->twitter }}" class="team-soc" target="_blank">X</a>@endif
          </div>
        </div>
        <div class="team-info">
          <div class="team-name">{{ $member->name }}</div>
          <div class="team-pos">{{ $member->position }}</div>
          @if($member->bio)<p style="font-size:13px;color:var(--dim);line-height:1.65;margin-top:10px;font-weight:300">{{ Str::limit($member->bio,100) }}</p>@endif
        </div>
      </div>
      @endforeach
    </div>
    <div class="reveal" style="margin-top:80px">
      <div style="background:var(--panel);border:1px solid var(--rim);border-left:2px solid var(--cyan);padding:52px 60px;display:grid;grid-template-columns:1fr auto;gap:32px;align-items:center">
        <div><div class="s-tag" style="margin-bottom:10px">Join Us</div><h3 style="font-family:var(--font-d);font-size:2.5rem;letter-spacing:1px;color:var(--text);margin-bottom:10px">WANT TO BE ON<br><span style="background:linear-gradient(135deg,var(--cyan),var(--violet));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">THIS TEAM?</span></h3><p style="font-size:15px;color:var(--muted);font-weight:300">We're always looking for passionate creatives and engineers.</p></div>
        <a href="{{ route('careers') }}" class="btn-primary" style="flex-shrink:0"><span>View Openings</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
      </div>
    </div>
  </div>
</section>
@endsection