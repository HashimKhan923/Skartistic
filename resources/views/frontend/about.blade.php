@extends('frontend.layouts.app')
@section('title','About — '.($settings['site_name']??'SK Artistic'))
@section('content')

<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>About</span></div>
    <h1>WE'RE <span class="g">SK ARTISTIC.</span></h1>
    <p>A team of passionate designers and developers building digital excellence without limits.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:start">

      <div class="reveal-l">
        <div class="s-tag">Who We Are</div>
        <h2 class="s-h">BUILT FOR<br><span class="g2">IMPACT.</span></h2>
        <div class="about-visual">
          <div class="about-visual-inner">
            <div class="circuit-line h" style="top:30%;width:60%;left:20%"></div>
            <div class="circuit-line h" style="top:65%;width:40%;left:10%;animation-delay:1.5s"></div>
            <div class="circuit-line v" style="left:40%;height:50%;top:20%;animation-delay:.8s"></div>
            <div class="circuit-line v" style="left:65%;height:40%;top:30%;animation-delay:2s"></div>
            <div class="about-big-num">SK</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);background:#fff;border:1px solid var(--rim);border-radius:12px;overflow:hidden;margin-top:1px">
          <div style="padding:24px 16px;border-right:1px solid var(--rim)"><div class="stat-box-n" style="font-size:2.4rem" data-count="{{ preg_replace('/\D/','',$settings['stats_clients']??'70') }}">{{ $settings['stats_clients']??'70+' }}</div><div class="stat-box-l">Clients</div></div>
          <div style="padding:24px 16px;border-right:1px solid var(--rim)"><div class="stat-box-n" style="font-size:2.4rem" data-count="{{ preg_replace('/\D/','',$settings['stats_projects']??'65') }}">{{ $settings['stats_projects']??'65+' }}</div><div class="stat-box-l">Projects</div></div>
          <div style="padding:24px 16px"><div class="stat-box-n" style="font-size:2.4rem">5★</div><div class="stat-box-l">Reviews</div></div>
        </div>
      </div>

      <div class="reveal-r">
        <p style="font-size:17px;color:var(--muted);line-height:1.9;font-weight:300;margin-bottom:40px">SK Artistic is a full-cycle digital agency. We combine strategy, technology, and world-class design to build digital products that move businesses forward — founded on the belief that exceptional design should produce real, measurable results.</p>
        <div class="about-values">
          <div class="a-value"><div class="a-value-icon">🎯</div><div><h4>Purpose-Driven</h4><p>Every pixel serves a measurable goal. We design and build with clear, deliberate intent.</p></div></div>
          <div class="a-value"><div class="a-value-icon">💎</div><div><h4>Quality First</h4><p>We never ship something we wouldn't be proud to put our name on. Full stop.</p></div></div>
          <div class="a-value"><div class="a-value-icon">🤝</div><div><h4>True Partnership</h4><p>We treat your business like our own — investing fully in your long-term success.</p></div></div>
          <div class="a-value"><div class="a-value-icon">🚀</div><div><h4>Always Evolving</h4><p>Constantly learning, adapting, and staying ahead of what's coming next.</p></div></div>
        </div>
        <a href="{{ route('contact') }}" class="btn-primary" style="display:inline-flex;margin-top:40px"><span>Work With Us</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
      </div>
    </div>
  </div>
</section>

@if(isset($team) && $team->count())
<section class="section section-alt">
  <div class="container">
    <div style="display:flex;align-items:end;justify-content:space-between;margin-bottom:52px;flex-wrap:wrap;gap:16px">
      <div class="reveal-l"><div class="s-tag">The Crew</div><h2 class="s-h">MEET THE<br><span class="g">TEAM.</span></h2></div>
      <a href="{{ route('team') }}" class="btn-ghost reveal-r">Full Team <span class="arr">→</span></a>
    </div>
    <div class="team-grid">
      @foreach($team->take(4) as $member)
      <div class="team-card">
        <div class="team-img">
          @if($member->photo)<img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}">@else<div class="team-img-ph">👤</div>@endif
          <div class="team-overlay"></div>
          <div class="team-socials">
            @if($member->linkedin)<a href="{{ $member->linkedin }}" class="team-soc" target="_blank">in</a>@endif
            @if($member->instagram)<a href="{{ $member->instagram }}" class="team-soc" target="_blank">ig</a>@endif
          </div>
        </div>
        <div class="team-info"><div class="team-name">{{ $member->name }}</div><div class="team-pos">{{ $member->position }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="section" style="padding-top:0;padding-bottom:80px">
  <div class="container">
    <div class="cta-block reveal">
      <div class="cta-glow1"></div><div class="cta-glow2"></div>
      <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
      <h2>READY TO START<br><span style="background:linear-gradient(135deg,var(--light),var(--magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">YOUR PROJECT?</span></h2>
      <p>Let's build something extraordinary together.</p>
      <div class="cta-btns"><a href="{{ route('contact') }}" class="btn-primary"><span>Get In Touch</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></div>
    </div>
  </div>
</section>

@endsection