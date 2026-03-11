@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('content')
<section style="min-height:100vh;padding:120px 0 80px;">
    <div class="container" style="max-width:900px;">
        <h1 style="font-size:3rem;font-weight:800;margin-bottom:40px;">{{ $page->title }}</h1>
        <div style="line-height:1.8;font-size:1.05rem;">{!! $page->content !!}</div>
    </div>
</section>
@endsection