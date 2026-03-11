@extends('frontend.layouts.app')
@section('title', ($settings['site_name'] ?? 'SK Artistic') . ' — Beyond Ordinary')
@section('content')

{{-- ── HERO (Centered, Nyntax-style with animated cycling text) ── --}}
<style>
/* ── Hero Centered ── */
.hero-c {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 160px 40px 120px;
  position: relative;
  overflow: hidden;
  background: var(--void);
  max-width: 100%;
}
.hero-c-grid {
  position: absolute; inset: 0; pointer-events: none;
  background-image:
    linear-gradient(rgba(42,34,99,.055) 1px, transparent 1px),
    linear-gradient(90deg, rgba(42,34,99,.055) 1px, transparent 1px);
  background-size: 60px 60px;
}
.hero-c-orb {
  position: absolute; top: 50%; left: 50%;
  transform: translate(-50%, -55%);
  width: 780px; height: 780px; border-radius: 50%;
  background: radial-gradient(circle, rgba(108,92,231,.11) 0%, rgba(42,34,99,.04) 45%, transparent 68%);
  filter: blur(90px); pointer-events: none;
  animation: orb-breathe 6s ease-in-out infinite;
}

/* Trusted badge */
.hero-trust {
  display: inline-flex; align-items: center; gap: 10px;
  background: rgba(42,34,99,.05); border: 1.5px solid rgba(42,34,99,.11);
  padding: 7px 20px 7px 10px; border-radius: 100px;
  margin-bottom: 40px; animation: fade-up .6s .1s both;
  position: relative; z-index: 2;
}
.hero-trust-avatars { display: flex }
.hero-trust-av {
  width: 28px; height: 28px; border-radius: 50%;
  border: 2px solid #fff; background: var(--deep);
  margin-left: -7px; display: flex; align-items: center; justify-content: center;
  font-size: .75rem; overflow: hidden; flex-shrink: 0;
}
.hero-trust-av:first-child { margin-left: 0 }
.hero-trust-txt { font-family: var(--font-ui); font-size: 12.5px; color: var(--muted); font-weight: 500 }
.hero-trust-txt strong { color: var(--brand) }

/* Static headline */
.hero-static-line {
  font-family: var(--font-ui); font-weight: 700;
  font-size: clamp(1.5rem, 3vw, 2.4rem);
  color: var(--text); letter-spacing: -.3px; line-height: 1;
  margin-bottom: 10px; animation: fade-up .6s .22s both;
  position: relative; z-index: 2;
}

/* Animated word display */
.hero-ticker-wrap {
  margin-bottom: 36px;
  animation: fade-up .6s .34s both;
  position: relative; z-index: 2;
  width: 100%;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
}
.hero-ticker-word {
  font-family: var(--font-ui); font-weight: 800;
  font-size: clamp(2.8rem, 4vw, 5.5rem);
  letter-spacing: -2px; line-height: 1.1;
  display: inline-block; text-align: center; padding: 4px 8px;
  white-space: nowrap; color: var(--accent);
}
/* Nyntax letter spans — gradient applied per-letter so it actually renders */
.nt-chunk { display: inline-block; white-space: nowrap }
.nt-letter {
  display: inline-block;
  background: linear-gradient(135deg, var(--accent) 0%, var(--magenta) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.nt-space { display: inline-block; width: .28em; -webkit-text-fill-color: transparent }

/* Subtext */
.hero-sub-c {
  font-size: clamp(15px, 1.5vw, 17px); font-weight: 300;
  color: var(--muted); line-height: 1.8; max-width: 580px;
  margin: 0 auto 44px; animation: fade-up .6s .46s both;
  position: relative; z-index: 2;
}

/* Buttons */
.hero-c-btns {
  display: flex; align-items: center; justify-content: center;
  gap: 12px; flex-wrap: wrap;
  animation: fade-up .6s .58s both;
  position: relative; z-index: 2;
}
.btn-schedule {
  display: inline-flex; align-items: center; gap: 9px;
  background: var(--brand); color: #fff;
  font-family: var(--font-ui); font-size: 13px; font-weight: 600;
  letter-spacing: .2px; padding: 14px 28px; border-radius: 100px;
  cursor: none; border: none;
  transition: box-shadow .3s, transform .3s, background .3s;
}
.btn-schedule:hover { box-shadow: 0 8px 32px rgba(42,34,99,.28); transform: translateY(-2px); background: var(--brand2) }
.btn-contact-out {
  display: inline-flex; align-items: center; gap: 9px;
  background: transparent; color: var(--text);
  font-family: var(--font-ui); font-size: 13px; font-weight: 600;
  letter-spacing: .2px; padding: 13px 28px; border-radius: 100px;
  border: 1.5px solid rgba(42,34,99,.18); cursor: none;
  transition: all .25s;
}
.btn-contact-out:hover { border-color: var(--brand); color: var(--brand); background: rgba(42,34,99,.03) }

/* Scroll pill */
.hero-scroll-pill {
  position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  animation: fade-up .6s .8s both; z-index: 2;
}
.scroll-pill-ring {
  width: 22px; height: 36px;
  border: 1.5px solid rgba(42,34,99,.2); border-radius: 100px;
  display: flex; align-items: flex-start; justify-content: center; padding-top: 5px;
}
.scroll-pill-dot { width: 4px; height: 8px; background: var(--brand); border-radius: 100px; animation: sp-fall 1.7s ease-in-out infinite }
@keyframes sp-fall { 0%{transform:translateY(0);opacity:1} 75%{transform:translateY(14px);opacity:0} 100%{transform:translateY(0);opacity:0} }
.scroll-pill-lbl { font-family: var(--font-ui); font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: var(--dim) }
</style>

<section class="hero-c">
  <div class="hero-c-grid"></div>
  <div class="hero-c-orb"></div>

  {{-- Trusted badge --}}
  <div class="hero-trust">
    <div class="hero-trust-avatars">
      <div class="hero-trust-av">😊</div>
      <div class="hero-trust-av">👩</div>
      <div class="hero-trust-av">👨</div>
    </div>
    <span class="hero-trust-txt">Trusted by <strong>{{ $settings['stats_clients'] ?? '70+' }}</strong> {{ $settings['hero_trust_text'] ?? 'clients worldwide.' }}</span>
  </div>

  {{-- Static headline --}}
  <div class="hero-static-line">{{ $settings['hero_line1'] ?? 'Transforming Visions Into' }}</div>

  {{-- Animated word display (Nyntax per-letter blur+fade) --}}
  <div class="hero-ticker-wrap" id="heroTicker">
    <div class="hero-ticker-word" id="heroWord"></div>
  </div>

  {{-- Subheading --}}
  <p class="hero-sub-c">{{ $settings['hero_subtitle'] ?? "SK Artistic is where ideas turn into innovation. As a full-cycle design and development agency, we create digital products that unlock growth, elevate brands, and deliver unforgettable user experiences." }}</p>

  {{-- CTAs --}}
  <div class="hero-c-btns">
    <a href="{{ route('contact') }}" class="btn-schedule">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      <span>{{ $settings['hero_btn1'] ?? 'Schedule a Meeting' }}</span>
    </a>
    <a href="{{ route('portfolio') }}" class="btn-contact-out">
      <span>{{ $settings['hero_btn2'] ?? 'Our Work' }}</span>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>

  {{-- Scroll hint --}}
  <div class="hero-scroll-pill">
    <div class="scroll-pill-ring"><div class="scroll-pill-dot"></div></div>
    <span class="scroll-pill-lbl">Scroll</span>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  @php
    $animWordsRaw = $settings['hero_animated_words'] ?? 'Seamless Designs,Digital Creation,Innovative Ideas,Impactful Solutions';
    $animWordsArr = array_values(array_filter(array_map('trim', explode(',', $animWordsRaw))));
  @endphp
  var WORDS = {!! json_encode($animWordsArr) !!};
  var container = document.getElementById('heroWord');
  if (!container) return;

  var current = 0;

  /* Build letter spans exactly like Nyntax:
     each word is split into <span class="word-chunk">
     each character is a <span class="letter"> */
  function buildWord(text) {
    // Split by spaces so we can wrap each chunk in whitespace-nowrap span
    var chunks = text.split(' ');
    var html = '';
    chunks.forEach(function (chunk, ci) {
      html += '<span class="nt-chunk">';
      for (var i = 0; i < chunk.length; i++) {
        html += '<span class="nt-letter">' + chunk[i] + '</span>';
      }
      // Add non-breaking space after every chunk except the last
      if (ci < chunks.length - 1) html += '<span class="nt-space">&nbsp;</span>';
      html += '</span>';
    });
    return html;
  }

  /* Animate letters IN — staggered blur(8px)→blur(0) + translateY(12px)→0 + opacity 0→1 */
  function animateIn(el) {
    var letters = el.querySelectorAll('.nt-letter');
    // First pass: set all to hidden immediately, no transition
    letters.forEach(function (l) {
      l.style.transition = 'none';
      l.style.opacity    = '0';
      l.style.filter     = 'blur(12px)';
      l.style.transform  = 'translateY(14px)';
    });
    // Second pass: after browser paints hidden state, animate each letter in with stagger
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        letters.forEach(function (l, i) {
          (function (letter, delay) {
            setTimeout(function () {
              letter.style.transition = 'opacity .42s ease, filter .42s ease, transform .42s ease';
              letter.style.opacity    = '1';
              letter.style.filter     = 'blur(0px)';
              letter.style.transform  = 'translateY(0)';
            }, delay);
          })(l, i * 35);
        });
      });
    });
  }

  /* Animate letters OUT — staggered blur(0)→blur(6px) + translateY(0)→-10px + opacity 1→0 */
  function animateOut(el, cb) {
    var letters = el.querySelectorAll('.nt-letter');
    var total   = letters.length;
    letters.forEach(function (l, i) {
      (function (letter, delay) {
        setTimeout(function () {
          letter.style.transition = 'opacity .28s ease, filter .28s ease, transform .28s ease';
          letter.style.opacity    = '0';
          letter.style.filter     = 'blur(6px)';
          letter.style.transform  = 'translateY(-10px)';
        }, delay);
      })(l, i * 22);
    });
    // Fire callback after all letters finish animating out
    setTimeout(cb, total * 22 + 300);
  }

  /* Initial render */
  container.innerHTML = buildWord(WORDS[current]);
  animateIn(container);

  /* Cycle every 2.8s */
  setInterval(function () {
    animateOut(container, function () {
      current = (current + 1) % WORDS.length;
      container.innerHTML = buildWord(WORDS[current]);
      animateIn(container);
    });
  }, 2800);
});
</script>

{{-- ── DEVICE MOCKUP / VIDEO SECTION ── --}}
<style>
.device-section {
  position: relative; z-index: 1;
  padding: 0 40px 80px;
  background: var(--void);
  display: flex; align-items: center; justify-content: center;
}
.device-frame {
  width: 100%; max-width: 1100px;
  aspect-ratio: 16/9;
  border-radius: 24px;
  border: 3px solid rgba(42,34,99,.13);
  background: #fff;
  padding: 8px;
  box-shadow:
    0 2px 0 rgba(42,34,99,.06) inset,
    0 40px 100px rgba(42,34,99,.16),
    0 8px 32px rgba(42,34,99,.09);
  transform: perspective(1400px) rotateX(8deg) scale(1.01);
  transform-origin: center top;
  animation: device-in 1s .4s both;
  will-change: transform;
}
@keyframes device-in {
  from { opacity:0; transform: perspective(1400px) rotateX(20deg) scale(.95) translateY(50px); }
  to   { opacity:1; transform: perspective(1400px) rotateX(8deg) scale(1.01); }
}
/* Disable CSS hover transform while JS scroll is controlling it */
.device-frame.js-scroll:hover {
  transform: none; /* JS handles it */
  box-shadow: 0 40px 100px rgba(42,34,99,.16), 0 8px 32px rgba(42,34,99,.09);
}.device-inner {
  width: 100%; height: 100%;
  border-radius: 18px;
  background: rgba(42,34,99,.03);
  padding: 8px;
}
.device-screen {
  position: relative; width: 100%; height: 100%;
  border-radius: 12px; overflow: hidden;
  background: var(--deep);
}
.device-screen video {
  width: 100%; height: 100%; object-fit: cover; display: block;
}
/* Fallback animated mockup */
.device-fallback {
  width: 100%; height: 100%;
  background: linear-gradient(135deg, #f5f3ff 0%, #fff 60%, #ede9fe 100%);
  display: flex; align-items: center; justify-content: center;
  gap: 16px; padding: 28px; box-sizing: border-box;
}
.mock-card {
  background: #fff; border-radius: 16px; padding: 20px;
  box-shadow: 0 4px 24px rgba(42,34,99,.1);
  border: 1px solid rgba(42,34,99,.07);
  flex: 1; display: flex; flex-direction: column; gap: 10px;
  overflow: hidden; min-width: 0;
}
.mock-card:nth-child(2) { margin-top: -24px; margin-bottom: 24px }
.mock-card-tag {
  display: inline-block; padding: 3px 10px; border-radius: 100px;
  background: rgba(108,92,231,.1);
  font-family: var(--font-ui); font-size: 9px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase; color: var(--accent); width: fit-content;
}
.mock-card-img {
  height: 70px; border-radius: 10px;
  background: linear-gradient(135deg, rgba(108,92,231,.1), rgba(167,139,250,.15));
  display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
}
.mock-bar { height: 8px; border-radius: 100px; background: var(--deep) }
.mock-bar.w60 { width:60%; background:linear-gradient(90deg,var(--accent),var(--magenta)) }
.mock-bar.w75 { width:75% }
.mock-bar.w40 { width:40% }
.mock-bar.w50 { width:50%; background:linear-gradient(90deg,var(--brand),var(--accent)) }
.mock-bar.w35 { width:35%; background:linear-gradient(90deg,var(--magenta),var(--accent)) }
</style>

<div class="device-section">
  <div class="device-frame">
    <div class="device-inner">
      <div class="device-screen">
        @if(!empty($settings['promo_video']))
          {{-- YouTube embed via iframe --}}
          @php
            // Extract YouTube video ID from any YouTube URL format
            $yt_url = $settings['promo_video'];
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $yt_url, $yt_match);
            $yt_id = $yt_match[1] ?? null;
          @endphp
          @if($yt_id)
            <iframe
              src="https://www.youtube.com/embed/{{ $yt_id }}?autoplay=1&mute=1&loop=1&playlist={{ $yt_id }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1"
              frameborder="0"
              allow="autoplay; encrypted-media"
              allowfullscreen
              style="width:100%;height:100%;border:none;display:block">
            </iframe>
          @else
            {{-- Fallback: direct video file --}}
            <video autoplay loop muted playsinline
              poster="{{ !empty($settings['promo_poster']) ? asset('storage/'.$settings['promo_poster']) : '' }}"
              style="width:100%;height:100%;object-fit:cover;display:block">
              <source src="{{ asset('storage/'.$settings['promo_video']) }}" type="video/mp4">
            </video>
          @endif
        @elseif(!empty($settings['promo_image']))
          <img src="{{ asset('storage/'.$settings['promo_image']) }}" alt="SK Artistic work preview" style="width:100%;height:100%;object-fit:cover;display:block">
        @else
          {{-- Animated UI mockup fallback --}}
          <div class="device-fallback">
            <div class="mock-card" style="animation:fade-up .5s .6s both">
              <div class="mock-card-tag">UI Design</div>
              <div class="mock-card-img">🎨</div>
              <div class="mock-bar w60"></div>
              <div class="mock-bar w75"></div>
              <div class="mock-bar w40"></div>
            </div>
            <div class="mock-card" style="animation:fade-up .5s .75s both">
              <div class="mock-card-tag">Development</div>
              <div class="mock-card-img">⚡</div>
              <div class="mock-bar w50"></div>
              <div class="mock-bar w40"></div>
              <div class="mock-bar w75"></div>
            </div>
            <div class="mock-card" style="animation:fade-up .5s .9s both">
              <div class="mock-card-tag">Strategy</div>
              <div class="mock-card-img">🚀</div>
              <div class="mock-bar w35"></div>
              <div class="mock-bar w75"></div>
              <div class="mock-bar w60"></div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  var frame = document.querySelector('.device-frame');
  if (!frame) return;

  // Mark frame so CSS hover is overridden by JS
  frame.classList.add('js-scroll');

  var MAX_ROTATE = 8;    // degrees at top (same as Nyntax initial state)
  var MIN_ROTATE = 0;    // degrees when fully scrolled into view
  var MAX_SCALE  = 1.02;
  var MIN_SCALE  = 1.00;

  function update() {
    var rect     = frame.getBoundingClientRect();
    var vh       = window.innerHeight;

    // progress: 0 = element top just entered viewport bottom
    //           1 = element fully scrolled past viewport center
    var progress = 1 - (rect.top / vh);
    progress = Math.max(0, Math.min(1, progress));

    // Ease: slow start, quick flatten
    var eased = progress < 0.5
      ? 2 * progress * progress
      : 1 - Math.pow(-2 * progress + 2, 2) / 2;

    var rotateX = MAX_ROTATE - (MAX_ROTATE - MIN_ROTATE) * eased;
    var scale   = MAX_SCALE  - (MAX_SCALE  - MIN_SCALE)  * eased;

    // Match Nyntax: scale(x) rotateX(ydeg)
    frame.style.transform = 'perspective(1400px) scale(' + scale.toFixed(5) + ') rotateX(' + rotateX.toFixed(4) + 'deg)';
  }

  // Initial call
  update();

  // Throttled scroll listener using rAF
  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(function () {
        update();
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });

  // Also update on resize
  window.addEventListener('resize', update, { passive: true });
})();
</script>

{{-- MARQUEE --}}
<div class="marquee-section">
  <div class="marquee-track">
    @php
      $marqueeRaw  = $settings['marquee_words'] ?? 'Web Development,UI / UX Design,Mobile Apps,Brand Identity,Backend APIs,Motion Design,Graphic Design,Integrations';
      $marqueeList = array_values(array_filter(array_map('trim', explode(',', $marqueeRaw))));
      $marqueeLoop = array_merge($marqueeList, $marqueeList); // duplicate for seamless scroll
    @endphp
    @foreach($marqueeLoop as $w)<span class="m-word">{{ $w }}</span><span class="m-dot">◆</span>@endforeach
  </div>
</div>

{{-- SERVICES --}}
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:end;margin-bottom:56px">
      <div class="reveal-l">
        <div class="s-tag">Our Expertise</div>
        <h2 class="s-h">SERVICES<br>THAT <span class="g">HIT.</span></h2>
      </div>
      <div class="reveal-r" style="text-align:right">
        <p class="s-body" style="margin-left:auto">Every service delivered with obsessive attention to craft — from pixel-perfect design to production-grade code that performs.</p>
        <a href="{{ route('services') }}" class="btn-ghost" style="display:inline-flex;margin-top:24px">View All Services <span class="arr">→</span></a>
      </div>
    </div>
    <div class="svc-grid">
      @foreach($services as $i => $service)
      <div class="svc-cell">
        <div class="svc-bar"></div>
        <div class="svc-num">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
        <div class="svc-icon-wrap"><span class="svc-icon">{{ $service->icon ?? '⚡' }}</span></div>
        <h3>{{ $service->title }}</h3>
        <p>{{ $service->short_description }}</p>
        <a href="{{ route('service.detail',$service->slug) }}" class="svc-link">Explore →</a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- STATS BAND --}}
<div style="background:var(--deep);border-top:1px solid var(--rim);border-bottom:1px solid var(--rim);position:relative;z-index:1">
  <div class="container">
    <div class="stats-row">
      <div class="stat-box"><div class="stat-box-n" data-count="{{ preg_replace('/\D/','',$settings['stats_projects']??'65') }}">{{ $settings['stats_projects'] ?? '65+' }}</div><div class="stat-box-l">Projects</div></div>
      <div class="stat-box"><div class="stat-box-n" data-count="{{ preg_replace('/\D/','',$settings['stats_clients']??'70') }}">{{ $settings['stats_clients'] ?? '70+' }}</div><div class="stat-box-l">Clients</div></div>
      <div class="stat-box"><div class="stat-box-n" data-count="{{ preg_replace('/\D/','',$settings['stats_reviews']??'60') }}">{{ $settings['stats_reviews'] ?? '60+' }}</div><div class="stat-box-l">5-Star Reviews</div></div>
      <div class="stat-box"><div class="stat-box-n" data-count="{{ preg_replace('/\D/','',$settings['stats_revenue']??'46') }}">{{ $settings['stats_revenue'] ?? '46+' }}</div><div class="stat-box-l">Revenue (M)</div></div>
    </div>
  </div>
</div>

{{-- PROCESS --}}
<section class="section section-alt">
  <div class="container">
    <div style="text-align:center;max-width:560px;margin:0 auto 64px" class="reveal">
      <div class="s-tag" style="justify-content:center">How It Works</div>
      <h2 class="s-h">THREE STEPS.<br><span class="g">ZERO FLUFF.</span></h2>
    </div>
    <div class="proc-row">
      <div class="proc-item reveal"><div class="proc-n">01</div><div class="proc-circle">I</div><h3>Discovery</h3><p>We deep-dive into your goals, audience, and competitive landscape before a single pixel is placed.</p></div>
      <div class="proc-item reveal" style="transition-delay:.15s"><div class="proc-n">02</div><div class="proc-circle" style="color:var(--accent)">II</div><h3>Design & Build</h3><p>High-fidelity prototypes, rapid iteration, production-grade code. You're involved at every checkpoint.</p></div>
      <div class="proc-item reveal" style="transition-delay:.3s"><div class="proc-n">03</div><div class="proc-circle" style="color:var(--magenta)">III</div><h3>Launch & Grow</h3><p>Rigorous testing, on-time delivery, and we stay post-launch — measuring and refining for real growth.</p></div>
    </div>
  </div>
</section>

{{-- TESTIMONIALS --}}
<section class="section">
  <div class="container">
    <div style="text-align:center;max-width:560px;margin:0 auto 64px" class="reveal">
      <div class="s-tag" style="justify-content:center">Client Voices</div>
      <h2 class="s-h">THEY SAID IT.<br><span class="g2">WE DID IT.</span></h2>
    </div>
    <div class="testi-wrap">
      @foreach($testimonials as $t)
      <div class="testi-block">
        <div class="testi-stars">@for($i=0;$i<$t->rating;$i++)<span>★</span>@endfor</div>
        <p class="testi-text">"{{ $t->review }}"</p>
        <div class="testi-sep"></div>
        <div class="testi-name">{{ $t->client_name }}</div>
        <div class="testi-role">{{ $t->client_position }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- PRICING --}}
<section class="section section-alt">
  <div class="container">
    <div style="text-align:center;max-width:560px;margin:0 auto 64px" class="reveal">
      <div class="s-tag" style="justify-content:center">Investment</div>
      <h2 class="s-h">CLEAR<br><span class="g">PRICING.</span></h2>
      <p class="s-body" style="text-align:center;margin:16px auto 0">No surprises. Honest numbers for serious work.</p>
    </div>
    <div class="price-wrap">
      @foreach($pricing_plans as $plan)
      <div class="price-card {{ $plan->is_featured ? 'featured' : '' }}">
        @if($plan->is_featured)<div class="price-hot">Most Popular</div>@endif
        <div class="price-tier">{{ $plan->badge }}</div>
        <div class="price-name">{{ strtoupper($plan->name) }}</div>
        <div class="price-tagline">{{ $plan->tagline }}</div>
        @if($plan->price)<div class="price-amount">{{ $plan->price }}</div>@endif
        <ul class="price-features">@foreach($plan->features as $f)<li>{{ $f->feature }}</li>@endforeach</ul>
        <a href="{{ route('contact') }}?plan={{ urlencode($plan->name) }}" class="btn-price {{ $plan->is_featured ? 'btn-price-fill' : 'btn-price-out' }}">Get Started →</a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="section">
  <div class="container">
    <div style="text-align:center;max-width:560px;margin:0 auto 64px" class="reveal">
      <div class="s-tag" style="justify-content:center">Questions</div>
      <h2 class="s-h">GOT<br><span class="g2">QUESTIONS?</span></h2>
    </div>
    <div class="faq-list">
      @foreach($faqs as $faq)
      <div class="faq-item">
        <button class="faq-q-btn"><span class="faq-question">{{ $faq->question }}</span><span class="faq-icon-wrap">+</span></button>
        <div class="faq-answer"><p>{{ $faq->answer }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="section" style="padding-top:0;padding-bottom:80px">
  <div class="container">
    <div class="cta-block reveal">
      <div class="cta-glow1"></div><div class="cta-glow2"></div>
      <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
      <div style="font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:20px;position:relative;z-index:1">Ready when you are →</div>
      <h2>BUILD SOMETHING<br><span style="background:linear-gradient(135deg,var(--light),var(--magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">EXTRAORDINARY.</span></h2>
      <p>Join {{ $settings['stats_clients'] ?? '70+' }} businesses who trusted SK Artistic.</p>
      <div class="cta-btns">
        <a href="{{ route('contact') }}" class="btn-primary"><span>Start Your Project</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        <a href="{{ route('free-audit') }}" class="btn-ghost">Free Audit <span class="arr">→</span></a>
      </div>
    </div>
  </div>
</section>

@endsection