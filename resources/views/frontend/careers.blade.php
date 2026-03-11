@extends('frontend.layouts.app')
@section('title','Careers — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Careers</span></div>
    <h1>JOIN THE <span class="g">TEAM.</span></h1>
    <p>We're building something special. Come be part of it.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start;margin-bottom:100px">
      <div class="reveal-l">
        <div class="s-tag">Why SK Artistic</div>
        <h2 class="s-h">WORK ON THINGS<br><span class="g2">THAT MATTER.</span></h2>
        <p style="font-size:16px;color:var(--muted);line-height:1.85;font-weight:300;margin-bottom:28px">We're a tight-knit crew of designers and developers who genuinely care about the work. No bureaucracy, no filler — just talented people building excellent things.</p>
        <p style="font-size:16px;color:var(--muted);line-height:1.85;font-weight:300">You'll ship real work from day one, grow fast, and have direct impact on every project you touch.</p>
      </div>
      <div class="reveal-r" style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--rim);border:1px solid var(--rim)">
        <div class="benefit-box"><div class="benefit-icon">🌍</div><h4>Remote First</h4><p>Work from anywhere. We care about output, not office hours.</p></div>
        <div class="benefit-box"><div class="benefit-icon">📈</div><h4>Real Growth</h4><p>Ship real projects, build real skills, get real responsibility fast.</p></div>
        <div class="benefit-box"><div class="benefit-icon">💰</div><h4>Fair Pay</h4><p>Competitive compensation. We pay what you're actually worth.</p></div>
        <div class="benefit-box"><div class="benefit-icon">🤝</div><h4>Great Team</h4><p>A crew that backs each other and celebrates wins together.</p></div>
      </div>
    </div>
    <div class="reveal" style="margin-bottom:32px">
      <div class="s-tag">Open Roles</div>
      <h2 class="s-h">CURRENT <span class="g">OPENINGS.</span></h2>
    </div>
    @if(isset($jobs) && $jobs->count())
    <div class="job-list">
      @foreach($jobs as $job)
      <div class="job-card">
        <div class="job-head">
          <div>
            <div class="job-title">{{ $job->title }}</div>
            <div class="job-tags">
              @if($job->type)<span class="job-tag">{{ $job->type }}</span>@endif
              @if($job->is_remote)<span class="job-tag remote">Remote ✦</span>@endif
              @if($job->department)<span class="job-tag">{{ $job->department }}</span>@endif
            </div>
          </div>
          <a href="{{ route('career.detail',$job->slug) }}" class="btn-outline" style="flex-shrink:0">Apply Now →</a>
        </div>
        @if($job->short_description)
        <div class="job-body"><div class="job-body-inner"><p>{{ $job->short_description }}</p></div></div>
        @endif
      </div>
      @endforeach
    </div>
    @else
    <div style="background:var(--panel);border:1px solid var(--rim);padding:72px;text-align:center" class="reveal">
      <div style="font-size:3rem;margin-bottom:16px">👀</div>
      <h3 style="font-family:var(--font-d);font-size:2rem;letter-spacing:1px;color:var(--text);margin-bottom:12px">NO OPENINGS RIGHT NOW</h3>
      <p style="font-size:15px;color:var(--muted);max-width:380px;margin:0 auto 28px;font-weight:300">But we're always interested in talented people. Drop us a line and introduce yourself.</p>
      <a href="{{ route('contact') }}" class="btn-primary" style="display:inline-flex"><span>Say Hello</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
    @endif
    <div class="reveal" style="margin-top:48px">
      <div style="background:var(--panel);border:1px solid var(--rim);border-left:2px solid var(--violet);padding:48px 60px;display:grid;grid-template-columns:1fr auto;gap:32px;align-items:center">
        <div><h3 style="font-family:var(--font-d);font-size:2rem;letter-spacing:1px;color:var(--text);margin-bottom:8px">DON'T SEE THE RIGHT ROLE?</h3><p style="font-size:15px;color:var(--muted);font-weight:300">Send us your portfolio anyway. If you're exceptional, we'll find a way.</p></div>
        <a href="mailto:{{ $settings['site_email']??'hello@skartistic.com' }}?subject=Open Application" class="btn-outline" style="flex-shrink:0">Send Portfolio →</a>
      </div>
    </div>
  </div>
</section>
@endsection