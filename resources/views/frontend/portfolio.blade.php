@extends('frontend.layouts.app')
@section('title','Portfolio — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Portfolio</span></div>
    <h1>OUR <span class="g">WORK.</span></h1>
    <p>{{ $portfolios->total() }}+ projects shipped. Here are the ones we're most proud of.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    @if(isset($categories) && $categories->count())
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:56px" class="reveal">
      <a href="{{ route('portfolio') }}" style="font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;padding:10px 22px;border:1px solid {{ !request('category') ? 'rgba(0,245,255,.5)' : 'var(--rim2)' }};color:{{ !request('category') ? 'var(--cyan)' : 'var(--dim)' }};background:{{ !request('category') ? 'rgba(0,245,255,.05)' : 'transparent' }};transition:all .25s;cursor:none">All</a>
      @foreach($categories as $cat)
      <a href="{{ route('portfolio',['category'=>$cat->slug]) }}" style="font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;padding:10px 22px;border:1px solid {{ request('category')==$cat->slug ? 'rgba(0,245,255,.5)' : 'var(--rim2)' }};color:{{ request('category')==$cat->slug ? 'var(--cyan)' : 'var(--dim)' }};background:{{ request('category')==$cat->slug ? 'rgba(0,245,255,.05)' : 'transparent' }};transition:all .25s;cursor:none">{{ $cat->name }}</a>
      @endforeach
    </div>
    @endif
    <div class="port-grid reveal">
      @foreach($portfolios as $i => $project)
      @php $spans=['grid-column:span 7;grid-row:span 2','grid-column:span 5;grid-row:span 2','grid-column:span 5;grid-row:span 2','grid-column:span 7;grid-row:span 2','grid-column:span 4;grid-row:span 2','grid-column:span 4;grid-row:span 2','grid-column:span 4;grid-row:span 2']; @endphp
      <a href="{{ route('portfolio.detail',$project->slug) }}" class="port-item" style="{{ $spans[$i%count($spans)] }}">
        @if($project->thumbnail)<img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}">@else<div class="port-ph">🎨</div>@endif
        <div class="port-overlay"></div>
        <div class="port-info">
          @if($project->category)<span class="port-cat">{{ $project->category }}</span>@endif
          <div class="port-title">{{ $project->title }}</div>
        </div>
      </a>
      @endforeach
    </div>
    @if($portfolios->hasPages())
    <div class="pagi">
      @if(!$portfolios->onFirstPage())<a href="{{ $portfolios->previousPageUrl() }}">← Prev</a>@else<span class="disabled">← Prev</span>@endif
      @if($portfolios->hasMorePages())<a href="{{ $portfolios->nextPageUrl() }}">Next →</a>@else<span class="disabled">Next →</span>@endif
    </div>
    @endif
  </div>
</section>
<section class="section" style="padding:60px 0">
  <div class="cta-block reveal">
    <div class="cta-glow1"></div><div class="cta-glow2"></div>
    <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
    <h2>LIKE WHAT<br><span class="g">YOU SEE?</span></h2>
    <p>Let's make your project the next one on here.</p>
    <div class="cta-btns"><a href="{{ route('contact') }}" class="btn-primary"><span>Start Your Project</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></div>
  </div>
</section>
@endsection