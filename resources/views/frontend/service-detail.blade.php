@extends('frontend.layouts.app')
@section('title',$service->title.' — '.($settings['site_name']??'SK Artistic'))
@section('meta_description',$service->short_description)
@section('content')
<div class="page-hero">
  <div class="container" style="position:relative;z-index:1">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('services') }}">Services</a><span class="sep">/</span><span>{{ $service->title }}</span></div>
    <h1>{{ $service->title }}</h1>
    <p>{{ $service->short_description }}</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:64px;align-items:start">
      <div class="reveal-l">
        @if($service->banner_image)<div style="margin-bottom:40px;border-radius:var(--r);overflow:hidden;border:1px solid var(--border)"><img src="{{ asset('storage/'.$service->banner_image) }}" alt="{{ $service->title }}" style="width:100%;max-height:420px;object-fit:cover;display:block;transition:transform .5s" onmouseenter="this.style.transform='scale(1.02)'" onmouseleave="this.style.transform=''"></div>@endif
        <div style="font-size:16px;color:var(--text-2);line-height:1.85;margin-bottom:40px">{!! nl2br(e($service->description)) !!}</div>
        @if($service->features && count($service->features))
        <h3 style="font-size:1.3rem;font-weight:800;color:var(--text);margin-bottom:20px;letter-spacing:-.5px">What's Included</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:40px">
          @foreach($service->features as $f)
          <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--bg-card);border-radius:var(--r-sm);border:1px solid var(--border);font-size:14px;color:var(--text-2)">
            <div style="width:6px;height:6px;border-radius:50%;background:var(--brand);flex-shrink:0"></div>{{ $f }}
          </div>
          @endforeach
        </div>
        @endif
        @if(isset($related_portfolios)&&$related_portfolios->count())
        <h3 style="font-size:1.3rem;font-weight:800;color:var(--text);margin-bottom:20px;letter-spacing:-.5px">Related Projects</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
          @foreach($related_portfolios as $p)
          <a href="{{ route('portfolio.detail',$p->slug) }}" style="display:block;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;transition:all .3s;cursor:none" onmouseenter="this.style.borderColor='var(--brand)';this.style.boxShadow='var(--shadow-sm)'" onmouseleave="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
            <div style="height:130px;overflow:hidden">@if($p->thumbnail)<img src="{{ asset('storage/'.$p->thumbnail) }}" alt="{{ $p->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .5s" onmouseenter="this.style.transform='scale(1.05)'" onmouseleave="this.style.transform=''">@else<div style="width:100%;height:100%;background:var(--brand-light);display:flex;align-items:center;justify-content:center;font-size:2rem">🎨</div>@endif</div>
            <div style="padding:14px 16px"><div style="font-size:.9rem;font-weight:700;color:var(--text)">{{ $p->title }}</div></div>
          </a>
          @endforeach
        </div>
        @endif
      </div>
      <div class="reveal-r" style="position:sticky;top:90px">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r);padding:32px;margin-bottom:12px;border-top:3px solid var(--brand)">
          <h4 style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:8px">Interested in {{ $service->title }}?</h4>
          <p style="font-size:13.5px;color:var(--text-3);line-height:1.65;margin-bottom:22px">No commitment needed — let's have a conversation about your project.</p>
          <a href="{{ route('contact') }}" class="btn-brand" style="display:flex;justify-content:center;width:100%;margin-bottom:8px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Schedule a Meeting</a>
          <a href="{{ route('free-audit') }}" class="btn-outline-dark" style="display:flex;justify-content:center;width:100%;font-size:14px">Get a Free Audit</a>
        </div>
        @if(isset($other_services)&&$other_services->count())
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r);padding:24px">
          <div style="font-size:12px;font-weight:700;color:var(--text-3);margin-bottom:14px;letter-spacing:.5px;text-transform:uppercase">Other Services</div>
          <div style="display:flex;flex-direction:column;gap:2px">
            @foreach($other_services as $s)
            <a href="{{ route('service.detail',$s->slug) }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);color:var(--text-2);font-size:14px;transition:all .2s;cursor:none" onmouseenter="this.style.background='var(--bg)';this.style.color='var(--brand)'" onmouseleave="this.style.background='';this.style.color='var(--text-2)'">{{ $s->icon ?? '⚡' }} {{ $s->title }}</a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
<div class="cta-band reveal"><h2>Start Your {{ $service->title }} Project</h2><p>Let's build something extraordinary together.</p><div class="cta-band-btns"><a href="{{ route('contact') }}" class="btn-white"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Get Started</a><a href="{{ route('services') }}" class="btn-white-out">All Services →</a></div></div>
@endsection