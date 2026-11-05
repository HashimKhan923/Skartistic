@if($instagram['url'] || $instagram['posts'] || $instagram['embeds'])
<section class="section" id="instagram" style="padding-top:0">
  <div class="container">
    <div class="ig-head reveal">
      <div>
        <div class="s-tag">Instagram</div>
        <h2 class="s-h" style="margin-bottom:0">Follow our <span class="g">latest posts.</span></h2>
      </div>
      @if($instagram['url'])
      <a href="{{ $instagram['url'] }}" class="ig-follow" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r=".6" fill="currentColor"/></svg>
        {{ $instagram['handle'] ? '@'.$instagram['handle'] : 'Follow us' }}
      </a>
      @endif
    </div>
    @if($instagram['posts'])
    <div class="ig-grid">
      @foreach($instagram['posts'] as $post)
      <a class="ig-post" href="{{ $post['permalink'] }}" target="_blank" rel="noopener" aria-label="{{ \Illuminate\Support\Str::limit($post['caption'], 90) ?: 'View post on Instagram' }}">
        <img src="{{ $post['image'] }}" alt="" loading="lazy" referrerpolicy="no-referrer">
        <span aria-hidden="true"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/></svg></span>
      </a>
      @endforeach
    </div>
    @elseif($instagram['embeds'])
    <div class="ig-grid is-embeds">
      @foreach($instagram['embeds'] as $embed)
      <div class="ig-embed"><iframe src="{{ $embed }}" title="Instagram post" loading="lazy" scrolling="no"></iframe></div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
