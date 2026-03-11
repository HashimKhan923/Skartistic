@extends('frontend.layouts.app')
@section('title',$post->title.' — '.($settings['site_name']??'SK Artistic'))
@section('meta_description',$post->excerpt??Str::limit(strip_tags($post->content),160))
@section('content')
<div class="page-hero" style="padding-bottom:0">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('blog') }}">Blog</a><span class="sep">/</span><span>{{ Str::limit($post->title,42) }}</span></div>
    @if($post->category && is_object($post->category))<div style="display:inline-block;background:var(--cyan);color:var(--void);font-family:var(--font-ui);font-size:9.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;margin-bottom:20px">{{ $post->category->name }}</div>@elseif($post->category)<div style="display:inline-block;background:var(--cyan);color:var(--void);font-family:var(--font-ui);font-size:9.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 14px;margin-bottom:20px">{{ $post->category }}</div>@endif
    <h1 style="font-size:clamp(3rem,7vw,7rem)">{{ strtoupper($post->title) }}</h1>
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;margin-top:24px;animation:fade-up .8s .2s both">
      @if($post->author && is_object($post->author))<div style="display:flex;align-items:center;gap:10px">@if($post->author->photo)<img src="{{ asset('storage/'.$post->author->photo) }}" alt="{{ $post->author->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid rgba(0,245,255,.3)">@else<div style="width:36px;height:36px;border-radius:50%;background:rgba(0,245,255,.1);border:1px solid rgba(0,245,255,.2);display:flex;align-items:center;justify-content:center;font-size:.9rem">👤</div>@endif<span style="font-size:13px;color:var(--muted);font-weight:400">{{ $post->author->name }}</span></div><div style="width:4px;height:4px;background:var(--dim);border-radius:50%"></div>@elseif($post->author)<div style="display:flex;align-items:center;gap:10px"><span style="font-size:13px;color:var(--muted);font-weight:400">{{ $post->author }}</span></div><div style="width:4px;height:4px;background:var(--dim);border-radius:50%"></div>@endif
      <span style="font-size:11px;color:var(--dim);letter-spacing:1.5px">{{ $post->created_at->format('F d, Y') }}</span>
      <div style="width:4px;height:4px;background:var(--dim);border-radius:50%"></div>
      <span style="font-size:11px;color:var(--dim);letter-spacing:1.5px">{{ $post->read_time??'5 min read' }}</span>
    </div>
  </div>
</div>
<section class="section" style="padding-top:60px">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:64px;align-items:start">
      <article class="reveal-l">
        @if($post->featured_image)<div style="margin-bottom:48px;overflow:hidden;border:1px solid var(--rim)"><img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;max-height:500px;object-fit:cover;display:block"></div>@endif
        <div class="blog-content">{!! $post->content !!}</div>
        @if($post->tags && $post->tags->count())
        <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--rim);display:flex;align-items:center;gap:10px;flex-wrap:wrap">
          <span style="font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:3.5px;text-transform:uppercase;color:var(--cyan);opacity:.8">Tags:</span>
          @foreach($post->tags as $tag)<a href="{{ route('blog',['tag'=>$tag->slug]) }}" style="font-size:12px;padding:6px 14px;border:1px solid var(--rim2);color:var(--dim);transition:all .2s;cursor:none" onmouseenter="this.style.borderColor='rgba(0,245,255,.35)';this.style.color='var(--cyan)'" onmouseleave="this.style.borderColor='var(--rim2)';this.style.color='var(--dim)'">{{ $tag->name }}</a>@endforeach
        </div>
        @endif
        <div style="margin-top:32px;padding:24px;background:var(--panel);border:1px solid var(--rim);display:flex;align-items:center;gap:16px;flex-wrap:wrap">
          <span style="font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:3.5px;text-transform:uppercase;color:var(--cyan);flex-shrink:0;opacity:.8">Share:</span>
          <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="f-soc">Twitter</a>
          <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="f-soc">LinkedIn</a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title.' '.request()->url()) }}" target="_blank" class="f-soc">WhatsApp</a>
        </div>
      </article>
      <div class="reveal-r" style="position:sticky;top:100px">
        <div style="background:var(--panel);border:1px solid var(--rim);border-top:2px solid var(--cyan);padding:32px;margin-bottom:12px">
          <h4 style="font-family:var(--font-d);font-size:1.4rem;letter-spacing:1px;color:var(--text);margin-bottom:8px">WORK WITH US?</h4>
          <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin-bottom:20px;font-weight:300">Let's build your next big project together.</p>
          <a href="{{ route('contact') }}" class="btn-primary" style="display:flex;width:100%;justify-content:center"><span>Get In Touch</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
        @if(isset($related_posts) && $related_posts->count())
        <div style="background:var(--panel);border:1px solid var(--rim);padding:28px">
          <div style="font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--cyan);margin-bottom:20px;opacity:.8">Related Posts</div>
          <div style="display:flex;flex-direction:column;gap:1px">
            @foreach($related_posts as $rel)
            <a href="{{ route('blog.detail',$rel->slug) }}" style="display:flex;gap:12px;padding:12px;transition:background .2s;cursor:none" onmouseenter="this.style.background='rgba(0,245,255,.03)'" onmouseleave="this.style.background=''">
              <div style="width:60px;height:50px;flex-shrink:0;overflow:hidden;background:var(--void)">@if($rel->featured_image)<img src="{{ asset('storage/'.$rel->featured_image) }}" alt="{{ $rel->title }}" style="width:100%;height:100%;object-fit:cover">@else<div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(0,245,255,.05),rgba(124,58,237,.06));display:flex;align-items:center;justify-content:center;font-size:1.1rem">✍️</div>@endif</div>
              <div><div style="font-family:var(--font-ui);font-size:.85rem;font-weight:700;color:var(--text);line-height:1.25;margin-bottom:3px;letter-spacing:.2px">{{ Str::limit($rel->title,52) }}</div><div style="font-size:11px;color:var(--dim)">{{ $rel->created_at->format('M d, Y') }}</div></div>
            </a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection