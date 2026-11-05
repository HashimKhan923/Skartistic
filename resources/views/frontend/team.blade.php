@extends('frontend.layouts.app')
@section('title','Our Team — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Team</span></div>
    <h1>The <span class="g2">crew.</span></h1>
    <p>Talented humans behind every pixel, line of code, and big idea that ships.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div style="margin-bottom:64px" class="reveal-l">
      <div class="s-tag">Our People</div>
      <h2 class="s-h">Meet the <span class="g">team.</span></h2>
    </div>
    @if($team->isEmpty())
    <div class="empty-state reveal"><div class="ico">👋</div><h3>Team profiles coming soon</h3><p>Our people are camera-shy for the moment. Come say hello, or join us.</p><a href="{{ route('careers') }}" class="btn-primary" style="display:inline-flex"><span>View Openings</span></a></div>
    @else
    <div class="team-grid">
      @foreach($team as $member)
      <div class="team-card" data-cursor="Profile">
        <div class="team-img">
          @if($member->photo)<img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" loading="lazy">@else<div class="team-img-ph">{{ strtoupper(substr($member->name, 0, 1)) }}</div>@endif
        </div>
        <div class="team-info">
          <a href="{{ route('team.member', $member->slug) }}" class="team-link"><div class="team-name">{{ $member->name }}</div></a>
          <div class="team-pos">{{ $member->position }}</div>
          @if($member->bio)<p style="font-size:14px;color:var(--muted);line-height:1.65;margin-top:10px">{{ \Illuminate\Support\Str::limit($member->bio,100) }}</p>@endif
          @if(count($member->socialLinks()))
            @include('frontend.partials.social-links', ['links' => $member->socialLinks(), 'variant' => 'on-light'])
          @endif
          <span class="team-view">View full profile →</span>
        </div>
      </div>
      @endforeach
    </div>
    @endif
    <div class="reveal" style="margin-top:80px">
      <div class="split-band band-pad" style="background:var(--panel);border:1px solid var(--rim);border-left:3px solid var(--cyan);padding:52px 60px;border-radius:12px">
        <div><div class="s-tag" style="margin-bottom:10px">Join Us</div><h3 style="font-family:var(--font-d);font-size:2.5rem;letter-spacing:1px;color:var(--text);margin-bottom:10px">Want to be on <span class="g">this team?</span></h3><p style="font-size:15px;color:var(--muted);font-weight:400">We're always looking for passionate creatives and engineers.</p></div>
        <a href="{{ route('careers') }}" class="btn-primary" style="flex-shrink:0"><span>View Openings</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
      </div>
    </div>
  </div>
</section>
@endsection