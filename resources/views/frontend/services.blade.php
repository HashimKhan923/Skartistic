@extends('frontend.layouts.app')
@section('title','Services — '.($settings['site_name']??'SK Artistic'))
@section('content')

<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Services</span></div>
    <h1>WHAT WE <span class="g">DO.</span></h1>
    <p>End-to-end digital services — from strategy and design to development and growth.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:end;margin-bottom:56px">
      <div class="reveal-l">
        <div class="s-tag">Our Expertise</div>
        <h2 class="s-h">FULL-CYCLE<br><span class="g2">DIGITAL.</span></h2>
      </div>
      <div class="reveal-r">
        <p class="s-body">Every service is delivered with obsessive attention to craft — from pixel-perfect design to production-grade code that actually performs.</p>
      </div>
    </div>
    <div class="svc-grid">
      @foreach($services as $i => $service)
      <div class="svc-cell">
        <div class="svc-bar"></div>
        <div class="svc-num">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
        <div class="svc-icon-wrap"><span class="svc-icon">{{ $service->icon ?? '⚡' }}</span></div>
        <h3>{{ $service->title }}</h3>
        <p>{{ $service->short_description }}</p>
        <a href="{{ route('service.detail',$service->slug) }}" class="svc-link">Explore →</a>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div style="text-align:center;max-width:560px;margin:0 auto 64px" class="reveal">
      <div class="s-tag" style="justify-content:center">How We Work</div>
      <h2 class="s-h">OUR<br><span class="g">PROCESS.</span></h2>
    </div>
    <div class="proc-row">
      <div class="proc-item reveal"><div class="proc-n">01</div><div class="proc-circle">I</div><h3>Discovery</h3><p>We deep-dive into your goals, audience, and competitive landscape before a single pixel is placed.</p></div>
      <div class="proc-item reveal" style="transition-delay:.15s"><div class="proc-n">02</div><div class="proc-circle" style="color:var(--accent)">II</div><h3>Design & Build</h3><p>High-fidelity prototypes, rapid iteration, production-grade code. You're involved at every checkpoint.</p></div>
      <div class="proc-item reveal" style="transition-delay:.3s"><div class="proc-n">03</div><div class="proc-circle" style="color:var(--magenta)">III</div><h3>Launch & Grow</h3><p>Rigorous testing, on-time delivery, and we stay post-launch — measuring and refining for real growth.</p></div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:0;padding-bottom:80px">
  <div class="container">
    <div class="cta-block reveal">
      <div class="cta-glow1"></div><div class="cta-glow2"></div>
      <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
      <h2>READY TO BUILD<br><span style="background:linear-gradient(135deg,var(--light),var(--magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">SOMETHING GREAT?</span></h2>
      <p>Tell us about your project — we'll respond within 24 hours.</p>
      <div class="cta-btns">
        <a href="{{ route('contact') }}" class="btn-primary"><span>Start a Project</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        <a href="{{ route('free-audit') }}" class="btn-ghost">Free Audit <span class="arr">→</span></a>
      </div>
    </div>
  </div>
</section>

@endsection