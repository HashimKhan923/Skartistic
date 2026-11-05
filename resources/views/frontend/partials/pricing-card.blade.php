@php
  $planHref = $plan->cta_url && !in_array($plan->cta_url, ['/contact', route('contact')]) ? $plan->cta_url : route('contact', ['plan' => $plan->name]);
  $planFlag = $plan->is_featured && stripos($plan->name, 'popular') === false;
@endphp
<article class="price-card {{ $plan->is_featured ? 'featured' : '' }}">
  <div class="price-head">
    @if($plan->badge)<span class="price-tier">{{ $plan->badge }}</span>@endif
    @if($planFlag)<span class="price-flag">Most Popular</span>@endif
  </div>
  <h3 class="price-name">{{ $plan->name }}</h3>
  @if($plan->description)<p class="price-tagline">{{ $plan->description }}</p>@endif
  @if($plan->price)
    <div class="price-amount">${{ number_format($plan->price, 0) }}<small>{{ $plan->price_suffix }}</small></div>
  @else
    <div class="price-amount is-custom">Custom Quote<small>Tailored to your scope</small></div>
  @endif
  <ul class="price-features">
    @foreach($plan->features ?? [] as $f)<li>{{ $f }}</li>@endforeach
    @foreach($plan->excluded_features ?? [] as $f)<li class="off">{{ $f }}</li>@endforeach
  </ul>
  <a href="{{ $planHref }}" class="btn-price {{ $plan->is_featured ? 'btn-price-fill' : 'btn-price-out' }}">{{ $plan->cta_label ?: 'Get Started' }} <span aria-hidden="true">→</span></a>
</article>
