@extends('frontend.layouts.app')
@section('title','Pricing — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Pricing</span></div>
    <h1>Straight up <span class="g">pricing.</span></h1>
    <p>No hidden fees. No awkward convos. Just clear, honest numbers.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    @if($pricing_plans->isEmpty())
    <div class="empty-state reveal"><div class="ico">💬</div><h3>Let's talk numbers</h3><p>Every project is different. Tell us yours and we'll send a clear quote.</p><a href="{{ route('contact') }}" class="btn-primary" style="display:inline-flex"><span>Get a Quote</span></a></div>
    @endif
    <div class="price-wrap">
      @foreach($pricing_plans as $plan)
      @include('frontend.partials.pricing-card', ['plan' => $plan])
      @endforeach
    </div>
    @if(isset($faqs) && $faqs->count())
    <div style="margin-top:100px">
      <div style="text-align:center;margin:0 auto" class="reveal">
        <div class="s-tag" style="justify-content:center">Questions</div>
        <h2 class="s-h">Pricing <span class="g2">FAQ.</span></h2>
      </div>
      <div class="faq-list">
        @foreach($faqs as $faq)<div class="faq-item"><button class="faq-q-btn"><span class="faq-question">{{ $faq->question }}</span><span class="faq-icon-wrap">+</span></button><div class="faq-answer"><div><p>{{ $faq->answer }}</p></div></div></div>@endforeach
      </div>
    </div>
    @endif
    <div style="margin-top:100px">
      <div class="cta-block reveal">
        <div class="cta-glow1"></div><div class="cta-glow2"></div>
        <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
        <h2>Not sure which <span class="g">plan to pick?</span></h2>
        <p>Book a free 15-minute call and we'll figure it out together.</p>
        <div class="cta-btns"><a href="{{ route('contact') }}" class="btn-primary"><span>Talk to Us</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a><a href="{{ route('free-audit') }}" class="btn-ghost">Free Audit <span class="arr">→</span></a></div>
      </div>
    </div>
  </div>
</section>
@endsection