@extends('frontend.layouts.app')
@section('title','Portfolio — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Portfolio</span></div>
    <h1>Our <span class="g">work.</span></h1>
    <p>@if($portfolios->total()){{ $portfolios->total() }} {{ \Illuminate\Support\Str::plural('project', $portfolios->total()) }} shipped. @endif Here are the ones we're most proud of.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    @if(isset($categories) && $categories->count())
    <div class="filter-bar reveal">
      <a href="{{ route('portfolio') }}" class="filter-pill {{ !request('category') ? 'on' : '' }}">All</a>
      @foreach($categories as $cat)
      <a href="{{ route('portfolio',['category'=>$cat]) }}" class="filter-pill {{ request('category')==$cat ? 'on' : '' }}">{{ ucfirst($cat) }}</a>
      @endforeach
    </div>
    @endif
    @if($portfolios->isEmpty())
    <div class="empty-state reveal"><div class="ico">🎨</div><h3>New work loading</h3><p>We're putting the finishing touches on our case studies. Want to be our next one?</p><a href="{{ route('contact') }}" class="btn-primary" style="display:inline-flex"><span>Start a Project</span></a></div>
    @else
    <div class="project-grid">
      @foreach($portfolios as $project)
      @include('frontend.partials.project-card', ['project' => $project])
      @endforeach
    </div>
    @endif
    @if($portfolios->hasPages())
    <div class="pagi">
      @if(!$portfolios->onFirstPage())<a href="{{ $portfolios->previousPageUrl() }}">← Prev</a>@else<span class="disabled">← Prev</span>@endif
      @if($portfolios->hasMorePages())<a href="{{ $portfolios->nextPageUrl() }}">Next →</a>@else<span class="disabled">Next →</span>@endif
    </div>
    @endif
  </div>
</section>
<section class="section" style="padding-top:0;padding-bottom:80px">
  <div class="container">
  <div class="cta-block reveal">
    <div class="cta-glow1"></div><div class="cta-glow2"></div>
    <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
    <h2>Like what <span class="g">you see?</span></h2>
    <p>Let's make your project the next one on here.</p>
    <div class="cta-btns"><a href="{{ route('contact') }}" class="btn-primary"><span>Start Your Project</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></div>
  </div>
  </div>
</section>
@endsection