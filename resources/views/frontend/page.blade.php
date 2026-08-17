@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>{{ $page->title }}</span></div>
    <h1>{{ strtoupper($page->title) }}</h1>
  </div>
</div>
<section class="section">
  <div class="container" style="max-width:820px">
    <div class="blog-content reveal">
      {!! $page->content !!}
    </div>
  </div>
</section>
@endsection
