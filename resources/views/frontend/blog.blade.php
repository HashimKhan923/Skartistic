@extends('frontend.layouts.app')
@section('title','Blog — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Blog</span></div>
    <h1>LATEST <span class="g">INSIGHTS.</span></h1>
    <p>Design, development, and digital strategy — straight from the studio.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    @if(isset($categories) && $categories->count())
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:56px" class="reveal">
      <a href="{{ route('blog') }}" style="font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;padding:10px 22px;border:1px solid {{ !request('category') ? 'rgba(0,245,255,.5)' : 'var(--rim2)' }};color:{{ !request('category') ? 'var(--cyan)' : 'var(--dim)' }};background:{{ !request('category') ? 'rgba(0,245,255,.05)' : 'transparent' }};transition:all .25s;cursor:none">All</a>
      @foreach($categories as $cat)<a href="{{ route('blog',['category'=>$cat->slug]) }}" style="font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;padding:10px 22px;border:1px solid {{ request('category')==$cat->slug ? 'rgba(0,245,255,.5)' : 'var(--rim2)' }};color:{{ request('category')==$cat->slug ? 'var(--cyan)' : 'var(--dim)' }};background:{{ request('category')==$cat->slug ? 'rgba(0,245,255,.05)' : 'transparent' }};transition:all .25s;cursor:none">{{ $cat->name }}</a>@endforeach
    </div>
    @endif
    @if($posts->count())
    @php $featured=$posts->first(); @endphp
    <a href="{{ route('blog.post',$featured->slug) }}" class="reveal" style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--rim);border:1px solid var(--rim);margin-bottom:1px;cursor:none;transition:opacity .3s" onmouseenter="this.style.opacity='.9'" onmouseleave="this.style.opacity='1'">
      <div style="height:400px;overflow:hidden;background:var(--panel);position:relative">
        @if($featured->featured_image)<img src="{{ asset('storage/'.$featured->featured_image) }}" alt="{{ $featured->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .5s" onmouseenter="this.style.transform='scale(1.04)'" onmouseleave="this.style.transform=''">@else<div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(0,245,255,.05),rgba(124,58,237,.07));display:flex;align-items:center;justify-content:center;font-size:5rem">✍️</div>@endif
        @if(is_object($featured->category))<span style="position:absolute;top:20px;left:20px;background:var(--cyan);color:var(--void);font-family:var(--font-ui);font-size:9.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 12px">{{ $featured->category->name }}</span>@endif
      </div>
      <div style="background:var(--panel);padding:52px 48px;display:flex;flex-direction:column;justify-content:center">
        <div style="font-family:var(--font-ui);font-size:10px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--cyan);margin-bottom:10px;opacity:.8">Featured Post</div>
        <h2 style="font-family:var(--font-ui);font-size:1.7rem;font-weight:700;color:var(--text);line-height:1.2;margin-bottom:16px;letter-spacing:.3px">{{ $featured->title }}</h2>
        <p style="font-size:15px;color:var(--muted);line-height:1.78;font-weight:300;margin-bottom:28px">{{ Str::limit($featured->excerpt??strip_tags($featured->content),140) }}</p>
        <div style="font-size:11px;color:var(--dim);letter-spacing:1.5px">{{ $featured->created_at->format('M d, Y') }} · {{ $featured->read_time??'5 min read' }}</div>
      </div>
    </a>
    @endif
    <div class="blog-grid">
      @foreach($posts->skip(1) as $post)
      <a href="{{ route('blog.detail',$post->slug) }}" class="blog-card">
        <div class="blog-img">
          @if($post->featured_image)
            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}">
          @else
            <div class="blog-img-ph">✍️</div>
          @endif
          @if($post->category)
            <span class="blog-cat-tag">{{ $post->category->name }}</span>
          @endif
        </div>
        <div class="blog-body">
          <div class="blog-meta">{{ $post->created_at->format('M d, Y') }} · {{ $post->read_time??'5 min read' }}</div>
          <div class="blog-title">{{ $post->title }}</div>
          <p class="blog-excerpt">{{ Str::limit($post->excerpt??strip_tags($post->content),110) }}</p>
          <span class="blog-more">Read More →</span>
        </div>
      </a>
      @endforeach
    </div>
    @if($posts->hasPages())
    <div class="pagi">
      @if(!$posts->onFirstPage())<a href="{{ $posts->previousPageUrl() }}">← Prev</a>@else<span class="disabled">← Prev</span>@endif
      @if($posts->hasMorePages())<a href="{{ $posts->nextPageUrl() }}">Next →</a>@else<span class="disabled">Next →</span>@endif
    </div>
    @endif
  </div>
</section>
@endsection