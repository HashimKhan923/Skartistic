@php
  $svcTitle = strtolower($service->title);
  $svcIcons = [
    '/\b(mobile|app|apps|android|ios|application)\b/' => '<rect x="6.5" y="2.5" width="11" height="19" rx="2.5"/><path d="M10.5 18.5h3"/>',
    '/\b(ai|automation|machine|chatbot)\b/' => '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9.5 2.5v3.5M14.5 2.5v3.5M9.5 18v3.5M14.5 18v3.5M2.5 9.5H6M2.5 14.5H6M18 9.5h3.5M18 14.5h3.5"/>',
    '/\b(seo|marketing|growth|ads|social)\b/' => '<path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
    '/\b(design|graphic|graphics|brand|branding|logo|ui|ux)\b/' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
    '/\b(ecommerce|shop|store|commerce)\b/' => '<path d="M6 7h12l1.5 13.5h-15z"/><path d="M9 10V6a3 3 0 0 1 6 0v4"/>',
    '/\b(video|motion|animation|media)\b/' => '<rect x="2.5" y="4.5" width="19" height="15" rx="3"/><path d="M10 9l5 3-5 3z"/>',
    '/\b(cloud|devops|hosting|server)\b/' => '<path d="M7 18.5h10a4.5 4.5 0 0 0 .9-8.9 6 6 0 0 0-11.6-1A5 5 0 0 0 7 18.5z"/>',
    '/\b(web|website|websites|development|software)\b/' => '<rect x="2.5" y="4" width="19" height="16" rx="2.5"/><path d="M2.5 9h19M9.5 13l-2.5 2 2.5 2M14.5 13l2.5 2-2.5 2"/>',
  ];
  $svcIcon = '<path d="M12 2.5l9.5 5-9.5 5-9.5-5z"/><path d="M2.5 12l9.5 5 9.5-5M2.5 16.5l9.5 5 9.5-5"/>';
  foreach ($svcIcons as $pattern => $paths) {
    if (preg_match($pattern, $svcTitle)) { $svcIcon = $paths; break; }
  }
  $svcTags = collect($service->offer_features ?? [])->pluck('title')->filter()->take(3);
@endphp
<a href="{{ route('service.detail', $service->slug) }}" class="svc-cell" data-cursor="Explore">
  <span class="svc-fill" aria-hidden="true"></span>
  <span class="svc-index" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
  <span class="svc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">{!! $svcIcon !!}</svg></span>
  <h3>{{ $service->title }}</h3>
  <p>{{ $service->short_description ?: \Illuminate\Support\Str::limit($service->hero_subtitle, 150) }}</p>
  @if($svcTags->count())
  <span class="svc-tags">@foreach($svcTags as $tag)<span class="svc-tag">{{ $tag }}</span>@endforeach</span>
  @endif
  <span class="svc-go">Explore service <i aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
</a>
