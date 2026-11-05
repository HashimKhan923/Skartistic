@extends('frontend.layouts.app')
@section('title','Gallery — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('portfolio') }}">Portfolio</a><span class="sep">/</span><span>Gallery</span></div>
    <h1>Our <span class="g">gallery.</span></h1>
    <p>A closer look at our creative work.</p>
  </div>
</div>
<section class="section" style="padding-top:60px">
  <div class="container">
    @if($images->count())
    <div class="g45-grid">
      @foreach($images as $image)
      <button type="button" class="g45-item" aria-label="Enlarge {{ $image->caption ?: 'gallery image' }}" data-cursor="Zoom" onclick="openLightbox('{{ asset('storage/'.$image->image) }}')">
        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: 'Gallery image' }}" loading="lazy">
        @if($image->caption)<span class="g45-cap">{{ $image->caption }}</span>@endif
      </button>
      @endforeach
    </div>
    @else
    <div class="empty-state">
      <h3>Coming soon</h3>
      <p>We're preparing this gallery. See our projects in the meantime.</p>
      <a href="{{ route('portfolio') }}" class="btn-primary"><span>View Projects</span></a>
    </div>
    @endif
  </div>
</section>
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Image preview" onclick="if(event.target===this)closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:99999;align-items:center;justify-content:center">
  <img id="lb-img" alt="" style="max-width:90vw;max-height:90vh;object-fit:contain">
  <button type="button" class="lb-close" aria-label="Close preview" onclick="closeLightbox()">✕</button>
</div>
@endsection
