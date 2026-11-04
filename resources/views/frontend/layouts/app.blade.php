<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  // SEO Manager > Settings > built-in default.
  $siteName  = $settings['site_name'] ?? 'SK Artistic';
  $seoTitle  = ($settings['seo_title'] ?? '') ?: (($settings['meta_title'] ?? '') ?: $siteName . ' — Beyond Ordinary');
  $seoDesc   = ($settings['seo_description'] ?? '') ?: (($settings['meta_description'] ?? '') ?: (($settings['site_tagline'] ?? '') ?: 'Full-cycle digital agency building the future.'));
  $ogImage   = $settings['og_image'] ?? '';
  $ogImage   = $ogImage && !\Illuminate\Support\Str::startsWith($ogImage, ['http://', 'https://']) ? asset($ogImage) : $ogImage;
  $trackId   = fn ($key) => preg_match('/^[A-Za-z0-9_-]+$/', $settings[$key] ?? '') ? $settings[$key] : null;
  $gaId      = $trackId('google_analytics_id');
  $gtmId     = $trackId('google_tag_manager_id');
  $pixelId   = $trackId('facebook_pixel_id');
  // Motion bundle is optional: a deploy without `npm run build` keeps working, just without the motion layer.
  $manifest    = public_path('build/manifest.json');
  $motionReady = is_file(public_path('hot')) || (is_file($manifest) && str_contains(file_get_contents($manifest), 'resources/js/motion.js'));
@endphp
<title>@yield('title', $seoTitle)</title>
<meta name="description" content="@yield('meta_description', $seoDesc)">
@if(!empty($settings['seo_keywords']))<meta name="keywords" content="{{ $settings['seo_keywords'] }}">@endif
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', ($settings['og_title'] ?? '') ?: $seoTitle)">
<meta property="og:description" content="@yield('meta_description', ($settings['og_description'] ?? '') ?: $seoDesc)">
@if($ogImage)<meta property="og:image" content="{{ $ogImage }}"><meta name="twitter:card" content="summary_large_image">@endif
<meta name="theme-color" content="#6c2bd9" id="meta-theme">
<script>
(function(){
  var d=document.documentElement,rm=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  d.classList.add('js');
  var t=null;try{t=localStorage.getItem('sk-theme')}catch(e){}
  if(t!=='dark'&&t!=='light')t=window.matchMedia&&matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';
  d.setAttribute('data-theme',t);
  @if($motionReady)
  if(!rm) d.classList.add('m-on');
  @endif
})();
</script>
<noscript><style>.reveal,.reveal-l,.reveal-r,.ab-anim,.sv-reveal,.sv-reveal-l,.sv-reveal-r,.sv-reveal-s,.ab-faq-item,.ab-val-item,.ab-founder-row{opacity:1!important;transform:none!important;filter:none!important}</style></noscript>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════
   TOKENS — Light Theme
   #EAE6FF  lavender surface
   #FFFFFF  white card
   #2A2263  deep navy brand
═══════════════════════════════════════════════════════════ */
:root {
  --void:    #ffffff;
  --deep:    #EAE6FF;
  --panel:   #FFFFFF;
  --panel-rgb: 255,255,255;
  --tint-1:  #f5f3ff;
  --tint-2:  #ede9fe;
  --aurora-a: #a855f7;
  --aurora-b: #a78bfa;
  --aurora-c: #EAE6FF;
  --aurora-k: 1;
  --glass:   rgba(var(--brand-rgb),.04);
  --rim:     rgba(var(--brand-rgb),.1);
  --rim2:    rgba(var(--brand-rgb),.18);
  --text:    #1f2937;
  --muted:   rgba(var(--brand-rgb),.6);
  --dim:     rgba(var(--brand-rgb),.38);
  --brand:   #6c2bd9;
  --brand-rgb: 108,43,217;
  --accent-rgb: 168,85,247;
  --ease-out: cubic-bezier(.16,1,.3,1);
  --brand2:  #813ae4;
  --accent:  #a855f7;
  --light:   #EAE6FF;
  --cyan:    #6c2bd9;
  --violet:  #a855f7;
  --magenta: #a78bfa;
  --glow-c:  0 4px 24px rgba(var(--brand-rgb),.18), 0 0 60px rgba(var(--brand-rgb),.06);
  --glow-v:  0 4px 24px rgba(var(--accent-rgb),.22), 0 0 60px rgba(var(--accent-rgb),.08);
  --font-d:  'Bebas Neue', sans-serif;
  --font-ui: 'Inter', sans-serif;
  --font-b:  'Space Grotesk', sans-serif;
  --r:       6px;
  --shadow:  0 2px 24px rgba(var(--brand-rgb),.08);
  --shadow-lg: 0 8px 48px rgba(var(--brand-rgb),.12);
}
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0 }
html { scroll-behavior:smooth; overflow-x:clip }
body { font-family:var(--font-b); color:var(--text); background:var(--void); overflow-x:clip }
a { text-decoration:none; color:inherit }
img { max-width:100%; display:block }
button { font-family:inherit }

/* ═══════════════════════════════════════════════════════════
   CURSOR
═══════════════════════════════════════════════════════════ */
#cur-dot,#cur-ring,#cur-trail { display:none }
#cur-dot {
  position:fixed; width:7px; height:7px; background:var(--brand);
  border-radius:50%; pointer-events:none; z-index:99999;
  transform:translate(-50%,-50%);
  box-shadow:0 0 10px rgba(var(--brand-rgb),.4);
  transition:width .2s, height .2s, background .2s, box-shadow .2s;
}
#cur-ring {
  position:fixed; width:34px; height:34px;
  border:1.5px solid rgba(var(--brand-rgb),.3); border-radius:50%;
  pointer-events:none; z-index:99998;
  transform:translate(-50%,-50%); transition:all .06s linear;
}
#cur-trail {
  position:fixed; width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(var(--brand-rgb),.05) 0%,transparent 70%);
  pointer-events:none; z-index:99997;
  transform:translate(-50%,-50%); transition:all .18s ease;
}
body.hov #cur-dot  { width:13px; height:13px; background:var(--accent); box-shadow:0 0 14px rgba(var(--accent-rgb),.4) }
body.hov #cur-ring { width:52px; height:52px; border-color:rgba(var(--accent-rgb),.4) }

/* ═══════════════════════════════════════════════════════════
   PROGRESS BAR
═══════════════════════════════════════════════════════════ */
#spb {
  position:fixed; top:0; left:0; height:3px; width:0%; z-index:9999;
  background:linear-gradient(90deg,var(--brand),var(--accent),var(--magenta));
  box-shadow:0 0 8px rgba(var(--brand-rgb),.3); transition:width .05s linear;
}

/* ═══════════════════════════════════════════════════════════
   DATA FLOW LINE
═══════════════════════════════════════════════════════════ */
.data-line {
  position:fixed; right:36px; top:50%; transform:translateY(-50%);
  width:1px; height:180px; z-index:100; pointer-events:none;
  background:linear-gradient(to bottom,transparent,var(--rim2),transparent); overflow:hidden;
}
.data-line::after {
  content:''; position:absolute; top:-100%; left:0; right:0;
  height:60px; background:linear-gradient(to bottom,transparent,var(--brand),transparent);
  animation:data-flow 2.5s ease-in-out infinite;
}
@keyframes data-flow { to { top:120% } }

/* ═══════════════════════════════════════════════════════════
   NAV — Floating pill
═══════════════════════════════════════════════════════════ */
.nav {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1000;
  width: calc(100% - 48px);
  max-width: 1100px;
  border-radius: 100px;
  padding: 0 8px 0 20px;
  height: 62px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(var(--panel-rgb),.88);
  backdrop-filter: blur(28px) saturate(180%);
  -webkit-backdrop-filter: blur(28px) saturate(180%);
  border: 1px solid rgba(var(--brand-rgb),.1);
  box-shadow: 0 4px 32px rgba(var(--brand-rgb),.1), 0 1px 0 rgba(var(--panel-rgb),.9) inset;
  animation: nav-drop .8s cubic-bezier(.34,1.56,.64,1) both;
  transition:
    top .4s cubic-bezier(.4,0,.2,1),
    background .4s ease,
    box-shadow .4s ease,
    border-color .4s ease;
}
@keyframes nav-drop {
  from { opacity:0; transform:translateX(-50%) translateY(-24px) scale(.97) }
  to   { opacity:1; transform:translateX(-50%) translateY(0) scale(1) }
}
.nav.scrolled {
  top: 12px;
  background: rgba(var(--panel-rgb),.96);
  box-shadow: 0 8px 40px rgba(var(--brand-rgb),.14), 0 1px 0 rgba(var(--panel-rgb),.9) inset;
  border-color: rgba(var(--brand-rgb),.14);
}
.nav::before {
  content: '';
  position: absolute;
  top: 0; left: 20%; right: 20%;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--brand), var(--accent), transparent);
  border-radius: 50%;
  opacity: 0;
  transition: opacity .5s;
}
.nav.scrolled::before { opacity: .5 }

/* Logo */
.nav-logo {
  font-family: var(--font-d);
  font-size: 1.5rem;
  letter-spacing: 2px;
  background: linear-gradient(135deg, var(--brand), var(--accent));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
  white-space: nowrap;
}
.nav-logo-glyph {
  width: 32px; height: 32px;
  border: 1.5px solid rgba(var(--brand-rgb),.2);
  display: flex; align-items: center; justify-content: center;
  position: relative; flex-shrink: 0;
  animation: spin 10s linear infinite;
  border-radius: 6px;
  background: rgba(var(--brand-rgb),.04);
}
.nav-logo-glyph::before {
  content: '';
  position: absolute; inset: 4px;
  border: 1px solid rgba(var(--accent-rgb),.25);
  animation: spin 6s linear infinite reverse;
  border-radius: 3px;
}
.nav-logo-glyph span {
  font-family: var(--font-d); font-size: .78rem; letter-spacing: 1px;
  background: linear-gradient(135deg, var(--brand), var(--accent));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  position: relative; z-index: 1;
  animation: spin 10s linear infinite reverse;
}
@keyframes spin { to { transform: rotate(360deg) } }

/* Center links */
.nav-center {
  display: flex;
  align-items: center;
  gap: 2px;
  list-style: none;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}
.nav-center li { position: relative }
.nav-center a {
  color: rgba(var(--brand-rgb),.6);
  font-family: var(--font-ui);
  font-size: 13.5px;
  font-weight: 500;
  letter-spacing: .3px;
  padding: 8px 14px;
  border-radius: 8px;
  transition: color .2s, background .2s;
  display: flex;
  align-items: center;
  gap: 5px;
  white-space: nowrap;
}
.nav-center a:hover { color: var(--brand); background: rgba(var(--brand-rgb),.06) }
.nav-center a.active { color: var(--brand); background: rgba(var(--brand-rgb),.08) }
.nav-center a.active::after {
  content: '';
  position: absolute;
  bottom: 2px; left: 50%; transform: translateX(-50%);
  width: 4px; height: 4px;
  background: var(--brand);
  border-radius: 50%;
}
.nav-chevron {
  width: 14px; height: 14px;
  opacity: .45;
  transition: transform .25s, opacity .2s;
}
.nav-center li:hover .nav-chevron { transform: rotate(180deg); opacity: .7 }

/* Dropdown */
.nav-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  left: 50%;
  transform: translateX(-50%) translateY(8px);
  min-width: 210px;
  background:var(--panel);
  border: 1px solid rgba(var(--brand-rgb),.1);
  border-radius: 16px;
  padding: 8px;
  opacity: 0;
  pointer-events: none;
  transition: opacity .2s, transform .25s cubic-bezier(.34,1.56,.64,1);
  box-shadow: 0 16px 48px rgba(var(--brand-rgb),.14);
  z-index: 100;
}
.nav-dropdown::before {
  content: '';
  position: absolute;
  top: -5px; left: 50%;
  width: 10px; height: 10px;
  background:var(--panel);
  border-left: 1px solid rgba(var(--brand-rgb),.1);
  border-top: 1px solid rgba(var(--brand-rgb),.1);
  transform: translateX(-50%) rotate(45deg);
}
.nav-center li:hover .nav-dropdown {
  opacity: 1; pointer-events: all;
  transform: translateX(-50%) translateY(0);
}
.nav-dropdown a {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; border-radius: 10px;
  color: var(--muted); font-size: 13px; background: none;
}
.nav-dropdown a:hover { color: var(--brand); background: rgba(var(--brand-rgb),.05) }
.nav-dropdown-dot {
  width: 5px; height: 5px; border-radius: 50%;
  background: var(--brand); opacity: .3; flex-shrink: 0;
  transition: opacity .2s;
}
.nav-dropdown a:hover .nav-dropdown-dot { opacity: .9 }

/* CTA */
.nav-cta {
  font-family: var(--font-ui) !important;
  font-size: 13px !important;
  font-weight: 600 !important;
  letter-spacing: .3px !important;
  color: #fff !important;
  background: var(--brand) !important;
  padding: 10px 22px !important;
  border-radius: 100px !important;
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  flex-shrink: 0;
  white-space: nowrap;
  transition: background .3s, box-shadow .3s, transform .2s !important;
  box-shadow: 0 4px 18px rgba(var(--brand-rgb),.3) !important;
  position: relative; overflow: hidden;
  
}
.nav-cta::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.1), transparent);
  border-radius: inherit;
}
.nav-cta:hover {
  background: var(--brand2) !important;
  box-shadow: 0 6px 28px rgba(var(--brand-rgb),.38) !important;
  transform: translateY(-1px) !important;
}
.nav-cta-arrow {
  width: 20px; height: 20px;
  background: rgba(255,255,255,.2);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px;
  transition: transform .25s;
}
.nav-cta:hover .nav-cta-arrow { transform: rotate(45deg) }

/* Hamburger */
.ham {
  display: none; flex-direction: column; gap: 4px;
  background: none; border: none; cursor: pointer; padding: 8px;
  border-radius: 8px; transition: background .2s;
}
.ham:hover { background: rgba(var(--brand-rgb),.06) }
.ham span {
  display: block; width: 20px; height: 1.5px;
  background: var(--brand); border-radius: 2px;
  transition: transform .3s, opacity .3s;
}
.ham.open span:nth-child(1) { transform: translateY(5.5px) rotate(45deg) }
.ham.open span:nth-child(2) { opacity: 0; transform: scaleX(0) }
.ham.open span:nth-child(3) { transform: translateY(-5.5px) rotate(-45deg) }

/* Mobile menu */
@media (max-width: 1080px) {
  .nav { width: calc(100% - 32px); padding: 0 8px 0 16px; top: 16px; gap: 8px }
  .nav-logo { margin-right: auto }
  .ham { order: 3 }
  .nav-center {
    position: absolute; top: calc(100% + 10px); left: 0; right: 0; transform: none;
    flex-direction: column; align-items: stretch; gap: 2px;
    background:var(--panel);
    border: 1px solid rgba(var(--brand-rgb),.1);
    border-radius: 20px; padding: 12px;
    box-shadow: 0 24px 60px rgba(var(--brand-rgb),.14);
    max-height: calc(100vh - 110px); overflow-y: auto;
    visibility: hidden; opacity: 0;
    clip-path: circle(0 at calc(100% - 40px) 0);
    transition: clip-path .6s var(--ease-out), opacity .3s, visibility 0s .6s;
  }
  .nav-center.open {
    visibility: visible; opacity: 1;
    clip-path: circle(150% at calc(100% - 40px) 0);
    transition: clip-path .7s var(--ease-out), opacity .2s, visibility 0s;
  }
  .nav-center a { padding: 12px 16px; border-radius: 12px; font-size: 15px }
  .nav-center a.active::after { display: none }
  .nav-chevron { display: none }
  .nav-dropdown {
    position: static; transform: none; opacity: 1; pointer-events: all;
    background: rgba(var(--brand-rgb),.03); border: none; border-radius: 10px;
    margin: 2px 0 6px 12px; padding: 4px; box-shadow: none; min-width: 0;
  }
  .nav-center li:hover .nav-dropdown { transform: none }
  .nav-dropdown::before { display: none }
  .nav-dropdown a { font-size: 13.5px; padding: 9px 12px }
  .ham { display: flex }
}
@media (max-width: 420px) { .nav-cta { padding: 9px 14px !important } .nav-cta-arrow { display: none !important } }

/* ═══════════════════════════════════════════════════════════
   PAGE WRAPPER / CANVAS
═══════════════════════════════════════════════════════════ */
#bg-canvas { position:fixed; inset:0; z-index:0; opacity:.5; pointer-events:none }
.page-wrap { position:relative; z-index:1 }

/* ═══════════════════════════════════════════════════════════
   SECTION COMMONS
═══════════════════════════════════════════════════════════ */
.section { padding:130px 24px; position:relative; z-index:1 }
.section-alt { background:var(--deep) }
.container { max-width:1100px; margin:0 auto; width:100%; padding:0 24px }
.s-tag {
  font-family:var(--font-ui); font-size:11px; font-weight:700;
  letter-spacing:4px; text-transform:uppercase; color:var(--brand);
  display:flex; align-items:center; gap:12px; margin-bottom:16px;
  opacity:.75;
}
.s-tag::before { content:''; width:28px; height:2px; background:var(--brand); opacity:.4; border-radius:2px }
.s-h {
  font-family:var(--font-d);
  font-size:clamp(3rem,6vw,7rem);
  letter-spacing:2px; line-height:.9; color:var(--text); margin-bottom:20px;
}
.s-h .g {
  background:linear-gradient(135deg,var(--brand),var(--accent));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.s-h .g2 {
  background:linear-gradient(135deg,var(--accent),var(--magenta));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.s-h .g3 {
  background:linear-gradient(135deg,var(--brand),#8b5cf6);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.s-body { font-size:16px; font-weight:300; color:var(--muted); line-height:1.85; max-width:480px }

/* ═══════════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════════ */
.btn-primary {
  font-family:var(--font-ui); font-size:11.5px; font-weight:700;
  letter-spacing:2.5px; text-transform:uppercase;
  color:#fff; background:var(--brand);
  padding:18px 48px; position:relative; overflow:hidden;
  border:none; display:inline-flex; align-items:center; gap:12px;
  transition:box-shadow .3s, transform .3s, background .3s;
  clip-path:polygon(0 0,calc(100% - 10px) 0,100% 10px,100% 100%,10px 100%,0 calc(100% - 10px));
}
.btn-primary::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  opacity:0; transition:opacity .3s;
}
.btn-primary:hover { box-shadow:var(--glow-c); transform:translateY(-3px) }
.btn-primary:hover::before { opacity:1 }
.btn-primary span { position:relative; z-index:1 }
.btn-primary svg { position:relative; z-index:1 }

.btn-outline {
  font-family:var(--font-ui); font-size:11px; font-weight:600;
  letter-spacing:3px; text-transform:uppercase;
  padding:16px 40px; border:1.5px solid rgba(var(--brand-rgb),.25);
  color:var(--brand); display:inline-flex; align-items:center; gap:10px;
  transition:all .3s; position:relative; overflow:hidden;
  background:transparent;
}
.btn-outline::before {
  content:''; position:absolute; inset:0;
  background:rgba(var(--brand-rgb),.04); opacity:0; transition:opacity .3s;
}
.btn-outline:hover { border-color:var(--brand); box-shadow:var(--glow-c) }
.btn-outline:hover::before { opacity:1 }

.btn-ghost {
  font-family:var(--font-ui); font-size:11px; font-weight:600;
  letter-spacing:2.5px; text-transform:uppercase;
  color:var(--muted); display:inline-flex; align-items:center; gap:10px;
  transition:color .25s;
}
.btn-ghost:hover { color:var(--brand) }
.btn-ghost .arr {
  width:36px; height:36px; border:1.5px solid var(--rim2);
  display:flex; align-items:center; justify-content:center;
  transition:all .3s; font-size:.95rem; border-radius:4px;
}
.btn-ghost:hover .arr { border-color:var(--brand); color:var(--brand); transform:translateX(5px) }

/* ═══════════════════════════════════════════════════════════
   PAGE HERO (inner pages)
═══════════════════════════════════════════════════════════ */
.page-hero {
  padding:150px 24px 90px; position:relative; overflow:hidden;
  background:linear-gradient(135deg, var(--deep) 0%, var(--panel) 100%);
  border-bottom:1px solid var(--rim);
}
.page-hero-grid {
  position:absolute; inset:0; pointer-events:none;
  background-image:
    linear-gradient(rgba(var(--brand-rgb),.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(var(--brand-rgb),.04) 1px,transparent 1px);
  background-size:80px 80px;
  mask-image:radial-gradient(ellipse 70% 80% at 50% 50%,black,transparent);
}
.page-hero-orb {
  position:absolute; right:-200px; top:50%; transform:translateY(-50%);
  width:600px; height:600px; border-radius:50%; pointer-events:none;
  background:radial-gradient(circle,rgba(var(--accent-rgb),.1) 0%,rgba(var(--brand-rgb),.04) 40%,transparent 70%);
  filter:blur(60px); animation:orb-breathe 5s ease-in-out infinite;
}
@keyframes orb-breathe {
  0%,100% { transform:translateY(-50%) scale(1); opacity:.7 }
  50%      { transform:translateY(-50%) scale(1.12); opacity:1 }
}
.breadcrumb {
  display:flex; align-items:center; gap:10px;
  font-family:var(--font-ui); font-size:11px; font-weight:500;
  letter-spacing:2.5px; text-transform:uppercase; color:var(--dim);
  margin-bottom:28px; animation:fade-up .7s both;
}
.breadcrumb a { color:var(--brand); transition:opacity .2s; opacity:.7 }
.breadcrumb a:hover { opacity:1 }
.breadcrumb .sep { opacity:.3 }
.page-hero h1 {
  font-family:var(--font-d);
  font-size:clamp(4rem,9vw,9rem);
  letter-spacing:2px; line-height:.88; color:var(--text);
  animation:fade-up .8s .1s both;
}
.page-hero h1 .g  { background:linear-gradient(135deg,var(--brand),var(--accent)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text }
.page-hero h1 .g2 { background:linear-gradient(135deg,var(--accent),var(--magenta)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text }
.page-hero p {
  font-size:17px; font-weight:300; color:var(--muted); line-height:1.8;
  margin-top:20px; max-width:520px; animation:fade-up .8s .2s both;
}
@keyframes fade-up { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:none} }

/* ═══════════════════════════════════════════════════════════
   MARQUEE
═══════════════════════════════════════════════════════════ */
.marquee-section {
  overflow:hidden; padding:16px 0;
  background:var(--brand);
  border-top:none; border-bottom:none;
  position:relative; z-index:1;
}
.marquee-track { display:flex; width:max-content; animation:marquee 26s linear infinite }
.marquee-track:hover { animation-play-state:paused }
.m-word {
  font-family:var(--font-d); font-size:1.05rem; letter-spacing:4px;
  text-transform:uppercase; padding:0 32px; white-space:nowrap;
  color:rgba(255,255,255,.55); transition:color .2s;
}
.m-word:hover { color:#fff }
.m-dot { color:var(--magenta); opacity:.6; font-size:.65rem; align-self:center; flex-shrink:0 }
@keyframes marquee { to { transform:translateX(-50%) } }

/* ═══════════════════════════════════════════════════════════
   HERO SECTION (home only)
═══════════════════════════════════════════════════════════ */
.hero {
  min-height:100vh; display:flex; align-items:center;
  padding:100px 24px 80px; position:relative; overflow:hidden;
  background:linear-gradient(150deg, var(--panel) 0%, var(--deep) 60%, var(--tint-2) 100%);
}
.hero-grid {
  position:absolute; inset:0;
  background-image:
    linear-gradient(rgba(var(--brand-rgb),.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(var(--brand-rgb),.04) 1px,transparent 1px);
  background-size:80px 80px;
  mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black,transparent);
}
.hero-grid::before {
  content:''; position:absolute; inset:0;
  background-image:
    linear-gradient(rgba(var(--accent-rgb),.05) 1px,transparent 1px),
    linear-gradient(90deg,rgba(var(--accent-rgb),.05) 1px,transparent 1px);
  background-size:400px 400px;
}
.orbit-wrap {
  position:absolute; right:-200px; top:50%; transform:translateY(-50%);
  width:700px; height:700px; pointer-events:none;
}
.orbit { position:absolute; border-radius:50%; border:1px solid transparent }
.orbit-1 { inset:0;   border-color:rgba(var(--brand-rgb),.08); animation:orb 20s linear infinite }
.orbit-2 { inset:60px; border-color:rgba(var(--accent-rgb),.07); animation:orb 14s linear infinite reverse }
.orbit-3 { inset:130px; border-color:rgba(var(--brand-rgb),.05); animation:orb 9s linear infinite }
.orbit-4 { inset:200px; border-color:rgba(var(--accent-rgb),.04); animation:orb 6s linear infinite reverse }
.orbit-5 { inset:270px; border-color:rgba(var(--brand-rgb),.03); animation:orb 4s linear infinite }
.orbit-dot {
  position:absolute; width:6px; height:6px; border-radius:50%;
  top:calc(50% - 3px); left:calc(50% - 3px);
}
.orbit-dot-c { background:var(--brand); box-shadow:0 0 8px rgba(var(--brand-rgb),.4); animation:orbit-t1 20s linear infinite }
.orbit-dot-v { background:var(--accent); box-shadow:0 0 8px rgba(var(--accent-rgb),.5); animation:orbit-t2 14s linear infinite reverse }
@keyframes orb { to { transform:rotate(360deg) } }
@keyframes orbit-t1 {
  0%   { transform:translate(349px,0) } 25%  { transform:translate(0,349px) }
  50%  { transform:translate(-349px,0) } 75%  { transform:translate(0,-349px) }
  100% { transform:translate(349px,0) }
}
@keyframes orbit-t2 {
  0%   { transform:translate(289px,0) } 25%  { transform:translate(0,289px) }
  50%  { transform:translate(-289px,0) } 75%  { transform:translate(0,-289px) }
  100% { transform:translate(289px,0) }
}
.hero-orb {
  position:absolute; right:-60px; top:50%; transform:translateY(-50%);
  width:550px; height:550px; border-radius:50%; pointer-events:none;
  background:radial-gradient(circle,rgba(var(--accent-rgb),.12) 0%,rgba(var(--brand-rgb),.05) 40%,transparent 70%);
  filter:blur(60px); animation:orb-breathe 5s ease-in-out infinite;
}
.hero-inner { max-width:1100px; margin:0 auto; width:100%; position:relative; z-index:2 }
.hero-status {
  display:inline-flex; align-items:center; gap:10px;
  border:1.5px solid rgba(var(--brand-rgb),.15); background:rgba(var(--brand-rgb),.05);
  padding:8px 20px; margin-bottom:48px;
  font-family:var(--font-ui); font-size:10.5px; letter-spacing:3px; text-transform:uppercase;
  color:var(--brand); animation:fade-up .8s .1s both; border-radius:100px;
}
.status-dot {
  width:7px; height:7px; background:var(--brand); border-radius:50%;
  box-shadow:0 0 8px rgba(var(--brand-rgb),.4); animation:blink 1.5s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }
.hero-h {
  font-family:var(--font-d);
  font-size:clamp(5rem,11vw,12rem);
  line-height:.88; letter-spacing:2px; color:var(--text); margin-bottom:0;
}
.hero-h .line { display:block; overflow:hidden }
.hero-h .line-inner { display:block; animation:line-in .9s both }
.hero-h .line:nth-child(1) .line-inner { animation-delay:.2s }
.hero-h .line:nth-child(2) .line-inner { animation-delay:.35s }
.hero-h .line:nth-child(3) .line-inner { animation-delay:.5s }
@keyframes line-in { from{transform:translateY(110%)} to{transform:none} }
.hero-h .c1 {
  background:linear-gradient(135deg,var(--brand),var(--accent));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.hero-h .c2 { -webkit-text-stroke:1.5px rgba(var(--brand-rgb),.15); color:transparent }
.hero-h .c2 em { color:var(--accent); -webkit-text-fill-color:var(--accent); -webkit-text-stroke:0; font-style:normal }
.hero-sub {
  font-size:17px; font-weight:300; color:var(--muted); line-height:1.85;
  max-width:500px; margin:36px 0 52px; animation:fade-up .8s .65s both;
}
.hero-actions { display:flex; align-items:center; gap:20px; flex-wrap:wrap; animation:fade-up .8s .8s both }
.hero-stats {
  position:absolute; right:24px; bottom:60px; z-index:2;
  display:flex; flex-direction:column; gap:4px; animation:fade-up .8s 1s both;
}
.h-stat {
  background:rgba(var(--panel-rgb),.85); border:1px solid rgba(var(--brand-rgb),.1);
  backdrop-filter:blur(12px); padding:16px 24px;
  display:flex; align-items:center; gap:20px;
  transition:border-color .3s, transform .3s, box-shadow .3s; min-width:220px;
  border-radius:8px;
}
.h-stat:hover { border-color:rgba(var(--brand-rgb),.22); transform:translateX(-6px); box-shadow:var(--shadow) }
.h-stat-n {
  font-family:var(--font-d); font-size:2.4rem;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
  letter-spacing:1px; line-height:1;
}
.h-stat-l { font-size:10.5px; letter-spacing:2.5px; text-transform:uppercase; color:var(--dim) }
.scroll-ind {
  position:absolute; bottom:40px; left:24px;
  display:flex; align-items:center; gap:14px; animation:fade-up .8s 1.2s both;
}
.scroll-line {
  width:1px; height:60px;
  background:linear-gradient(to bottom,transparent,var(--brand));
  position:relative; overflow:hidden;
}
.scroll-line::after {
  content:''; position:absolute; top:-100%; left:0; right:0; bottom:0;
  background:var(--brand); animation:scroll-drip 1.8s ease-in-out infinite;
}
@keyframes scroll-drip { to { top:100%; bottom:-100% } }
.scroll-txt {
  font-family:var(--font-ui); font-size:10px; letter-spacing:3px;
  text-transform:uppercase; color:var(--dim);
  writing-mode:vertical-rl; transform:rotate(180deg);
}

/* ═══════════════════════════════════════════════════════════
   SERVICE GRID
═══════════════════════════════════════════════════════════ */
.svc-grid {
  display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
  gap:1px; background:var(--rim); border:1px solid var(--rim); margin-top:80px;
  border-radius:12px; overflow:hidden;
}
.svc-cell {
  background:var(--panel); padding:52px 44px;
  position:relative; overflow:hidden; transition:background .4s;
}
.svc-cell::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,rgba(var(--brand-rgb),.04),rgba(var(--accent-rgb),.04));
  opacity:0; transition:opacity .5s;
}
.svc-cell:hover::before { opacity:1 }
.svc-cell:hover { background:var(--deep) }
.svc-bar {
  position:absolute; top:0; left:0; right:0; height:3px;
  background:linear-gradient(90deg,var(--brand),var(--accent));
  transform:scaleX(0); transform-origin:left;
  transition:transform .5s cubic-bezier(.25,.46,.45,.94);
}
.svc-cell:hover .svc-bar { transform:scaleX(1) }
.svc-cell::after {
  content:''; position:absolute; bottom:20px; right:20px;
  width:18px; height:18px;
  border-right:1.5px solid rgba(var(--brand-rgb),.2); border-bottom:1.5px solid rgba(var(--brand-rgb),.2);
  opacity:0; transition:opacity .4s;
}
.svc-cell:hover::after { opacity:1 }
.svc-num {
  font-family:var(--font-d); font-size:11px; letter-spacing:4px;
  color:rgba(var(--brand-rgb),.2); margin-bottom:28px;
}
.svc-icon-wrap {
  width:56px; height:56px; margin-bottom:28px;
  display:flex; align-items:center; justify-content:center; position:relative;
}
.svc-icon-wrap::before {
  content:''; position:absolute; inset:0;
  border:1.5px solid rgba(var(--brand-rgb),.12); transform:rotate(45deg);
  transition:border-color .3s, transform .5s;
}
.svc-cell:hover .svc-icon-wrap::before { border-color:rgba(var(--brand-rgb),.3); transform:rotate(90deg) }
.svc-icon { font-size:1.6rem; position:relative; z-index:1; transition:transform .3s }
.svc-cell:hover .svc-icon { transform:scale(1.2) }
.svc-cell h3 {
  font-family:var(--font-ui); font-size:1.1rem; font-weight:700;
  color:var(--text); margin-bottom:12px; letter-spacing:.4px;
}
.svc-cell p { font-size:14px; color:var(--muted); line-height:1.8; font-weight:300 }
.svc-link {
  display:inline-flex; align-items:center; gap:8px; margin-top:28px;
  font-family:var(--font-ui); font-size:11px; font-weight:600;
  letter-spacing:2.5px; text-transform:uppercase; color:var(--brand);
  transition:gap .3s var(--ease-out);
}
.svc-cell:hover .svc-link { gap:14px }
/* A lone service reads as a wide banner instead of a tall empty card */
@media(min-width:760px){
  .svc-grid:has(> .svc-cell:only-child) .svc-cell { display:grid; grid-template-columns:auto 1fr auto; column-gap:36px; align-items:center; padding:44px 48px }
  .svc-grid:has(> .svc-cell:only-child) .svc-num { display:none }
  .svc-grid:has(> .svc-cell:only-child) .svc-icon-wrap { grid-column:1; grid-row:1 / span 2; margin:0 }
  .svc-grid:has(> .svc-cell:only-child) h3 { grid-column:2; grid-row:1; margin-bottom:6px }
  .svc-grid:has(> .svc-cell:only-child) p { grid-column:2; grid-row:2 }
  .svc-grid:has(> .svc-cell:only-child) .svc-link { grid-column:3; grid-row:1 / span 2; margin:0 }
}
.svc-link:hover { gap:14px }

/* ═══════════════════════════════════════════════════════════
   PROCESS
═══════════════════════════════════════════════════════════ */
.proc-row {
  display:grid; grid-template-columns:repeat(3,1fr);
  gap:0; margin-top:80px;
}
.proc-item {
  padding:60px 48px; position:relative;
  border-right:1px solid var(--rim); transition:padding-left .3s;
}
.proc-item:last-child { border-right:none }
.proc-item:hover { padding-left:60px }
.proc-n {
  font-family:var(--font-d); font-size:5.5rem;
  color:rgba(var(--brand-rgb),.05); letter-spacing:-2px; line-height:1;
  margin-bottom:28px; transition:color .4s;
}
.proc-item:hover .proc-n { color:rgba(var(--brand-rgb),.1) }
.proc-circle {
  width:64px; height:64px; border-radius:50%;
  border:1.5px solid rgba(var(--brand-rgb),.2);
  display:flex; align-items:center; justify-content:center;
  font-family:var(--font-ui); font-size:1.1rem; font-weight:700;
  color:var(--brand); margin-bottom:28px; position:relative;
  transition:border-color .3s, box-shadow .3s;
}
.proc-circle::before {
  content:''; position:absolute; inset:-7px; border-radius:50%;
  border:1px solid rgba(var(--brand-rgb),.06); transition:border-color .3s;
}
.proc-item:hover .proc-circle { border-color:rgba(var(--brand-rgb),.4); box-shadow:0 0 24px rgba(var(--brand-rgb),.1) }
.proc-item:hover .proc-circle::before { border-color:rgba(var(--brand-rgb),.12) }
.proc-item h3 {
  font-family:var(--font-ui); font-size:1.15rem; font-weight:700;
  color:var(--text); margin-bottom:12px; letter-spacing:.4px;
}
.proc-item p { font-size:14px; color:var(--muted); line-height:1.8; font-weight:300; max-width:300px }

/* ═══════════════════════════════════════════════════════════
   STATS ROW
═══════════════════════════════════════════════════════════ */
.stats-row {
  display:grid; grid-template-columns:repeat(4,1fr);
  gap:1px; background:var(--rim); border:1px solid var(--rim); margin-top:80px;
  border-radius:12px; overflow:hidden;
}
.stat-box {
  background:var(--panel); padding:48px 40px; text-align:center;
  
  border-top:3px solid transparent; transition:border-color .3s, background .3s;
}
.stat-box:hover { background:var(--deep); border-color:var(--brand) }
.stat-box-n {
  font-family:var(--font-d); font-size:4rem; letter-spacing:-1px; line-height:1;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
  margin-bottom:10px;
}
.stat-box-l { font-size:11px; letter-spacing:3px; text-transform:uppercase; color:var(--dim) }

/* ═══════════════════════════════════════════════════════════
   TESTIMONIALS
═══════════════════════════════════════════════════════════ */
.testi-wrap {
  display:grid; grid-template-columns:repeat(3,1fr);
  gap:1px; background:var(--rim); border:1px solid var(--rim); margin-top:80px;
  border-radius:12px; overflow:hidden;
}
.testi-block {
  background:var(--panel); padding:52px 44px;
  position:relative; overflow:hidden; transition:background .4s;
}
.testi-block::before {
  content:'"'; position:absolute; top:-24px; right:20px;
  font-family:var(--font-d); font-size:12rem; color:rgba(var(--brand-rgb),.04);
  line-height:1; pointer-events:none; transition:color .4s;
}
.testi-block:hover { background:var(--deep) }
.testi-block:hover::before { color:rgba(var(--brand-rgb),.07) }
.testi-stars { display:flex; gap:4px; margin-bottom:24px }
.testi-stars span { color:var(--accent); font-size:13px }
.testi-text {
  font-size:15px; color:var(--muted); line-height:1.85;
  font-weight:300; font-style:italic; margin-bottom:32px;
}
.testi-sep { width:24px; height:2px; background:linear-gradient(90deg,var(--brand),transparent); margin-bottom:20px; border-radius:2px }
.testi-name { font-family:var(--font-ui); font-size:.9rem; font-weight:700; color:var(--text); margin-bottom:4px; letter-spacing:.4px }
.testi-role { font-size:11px; color:var(--dim); letter-spacing:2px; text-transform:uppercase }

/* ═══════════════════════════════════════════════════════════
   PRICING
═══════════════════════════════════════════════════════════ */
.price-wrap { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:80px }
.price-card {
  background:var(--panel); border:1.5px solid var(--rim); padding:52px 44px;
  position:relative; overflow:hidden; transition:all .4s;
  border-radius:16px;
}
.price-card::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,rgba(var(--brand-rgb),.02),rgba(var(--accent-rgb),.03));
  opacity:0; transition:opacity .4s; border-radius:inherit;
}
.price-card:hover::before { opacity:1 }
.price-card:hover { border-color:rgba(var(--brand-rgb),.2); transform:translateY(-8px); box-shadow:var(--shadow-lg) }
.price-card.featured {
  border-color:rgba(var(--brand-rgb),.18);
  background:linear-gradient(160deg,rgba(var(--brand-rgb),.03) 0%,rgba(var(--accent-rgb),.05) 100%);
}
.price-card.featured::after {
  content:''; position:absolute; top:0; left:0; right:0; height:3px;
  background:linear-gradient(90deg,var(--brand),var(--accent));
  box-shadow:0 0 12px rgba(var(--brand-rgb),.2);
}
.price-hot {
  position:absolute; top:30px; right:-58px; width:210px; text-align:center;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  color:#fff; font-family:var(--font-ui); font-size:9px; font-weight:700;
  letter-spacing:2px; text-transform:uppercase; padding:6px 0; transform:rotate(45deg); z-index:2;
}
.price-tier { font-family:var(--font-ui); font-size:10px; font-weight:600; letter-spacing:4px; text-transform:uppercase; color:var(--brand); margin-bottom:14px; opacity:.7 }
.price-name { font-family:var(--font-d); font-size:2.2rem; letter-spacing:2px; color:var(--text); margin-bottom:8px }
.price-tagline { font-size:13px; color:var(--dim); margin-bottom:36px }
.price-amount {
  font-family:var(--font-d); font-size:5rem; letter-spacing:-2px; line-height:1;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; margin-bottom:40px;
}
.price-features { list-style:none; margin-bottom:40px; border-top:1px solid var(--rim) }
.price-features li {
  padding:13px 0; border-bottom:1px solid var(--rim);
  font-size:14px; color:var(--muted); display:flex; align-items:center; gap:12px; font-weight:300;
}
.price-features li::before {
  content:''; width:5px; height:5px; background:var(--brand); border-radius:50%;
  flex-shrink:0; opacity:.6;
}
.btn-price {
  display:block; width:100%; text-align:center;
  font-family:var(--font-ui); font-size:11px; font-weight:700;
  letter-spacing:3px; text-transform:uppercase; padding:17px;
  transition:all .3s; border:none; border-radius:8px;
}
.btn-price-fill { background:linear-gradient(135deg,var(--brand),var(--accent)); color:#fff }
.btn-price-fill:hover { box-shadow:var(--glow-c); transform:translateY(-2px) }
.btn-price-out { border:1.5px solid rgba(var(--brand-rgb),.2); color:var(--brand); background:transparent }
.btn-price-out:hover { background:rgba(var(--brand-rgb),.04); border-color:var(--brand) }

/* ═══════════════════════════════════════════════════════════
   FAQ
═══════════════════════════════════════════════════════════ */
.faq-list { max-width:820px; margin:80px auto 0 }
.faq-item { border-bottom:1px solid var(--rim); overflow:hidden; transition:border-color .3s }
.faq-item.active { border-color:rgba(var(--brand-rgb),.2) }
.faq-q-btn {
  width:100%; background:none; border:none; text-align:left;
  padding:28px 0; display:flex; justify-content:space-between; align-items:center;
  gap:20px; transition:padding-left .2s;
}
.faq-q-btn:hover { padding-left:8px }
.faq-question {
  font-family:var(--font-ui); font-size:1rem; font-weight:600;
  color:var(--muted); transition:color .25s; letter-spacing:.3px;
}
.faq-item.active .faq-question { color:var(--text) }
.faq-icon-wrap {
  width:32px; height:32px; flex-shrink:0; border:1.5px solid var(--rim2);
  display:flex; align-items:center; justify-content:center;
  color:var(--brand); font-size:1.1rem;
  transition:transform .35s, background .3s, border-color .3s; border-radius:6px;
}
.faq-item.active .faq-icon-wrap { transform:rotate(45deg); background:rgba(var(--brand-rgb),.06); border-color:rgba(var(--brand-rgb),.3) }
.faq-answer { display:grid; grid-template-rows:0fr; transition:grid-template-rows .55s var(--ease-out) }
.faq-answer > * { overflow:hidden; min-height:0 }
.faq-answer p { padding:0 0 28px; font-size:15px; color:var(--muted); line-height:1.85; font-weight:300 }
.faq-item.active .faq-answer { grid-template-rows:1fr }

/* ═══════════════════════════════════════════════════════════
   CTA BLOCK
═══════════════════════════════════════════════════════════ */
.cta-block {
  margin:0; position:relative; overflow:hidden;
  border:1.5px solid rgba(var(--brand-rgb),.12); background:var(--brand);
  padding:120px 80px; text-align:center; border-radius:20px;
}
.cta-block::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),rgba(167,139,250,.6),transparent) }
.cta-block::after  { content:''; position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,rgba(var(--accent-rgb),.6),rgba(167,139,250,.4),transparent) }
.cta-corner { position:absolute; width:40px; height:40px }
.cta-corner.tl { top:20px;left:20px; border-top:1.5px solid rgba(255,255,255,.2); border-left:1.5px solid rgba(255,255,255,.2) }
.cta-corner.tr { top:20px;right:20px; border-top:1.5px solid rgba(255,255,255,.2); border-right:1.5px solid rgba(255,255,255,.2) }
.cta-corner.bl { bottom:20px;left:20px; border-bottom:1.5px solid rgba(167,139,250,.3); border-left:1.5px solid rgba(167,139,250,.3) }
.cta-corner.br { bottom:20px;right:20px; border-bottom:1.5px solid rgba(167,139,250,.3); border-right:1.5px solid rgba(167,139,250,.3) }
.cta-glow1 { position:absolute;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 65%);top:-100px;left:-100px;filter:blur(40px);pointer-events:none;animation:blob 8s ease-in-out infinite alternate }
.cta-glow2 { position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(167,139,250,.12) 0%,transparent 65%);bottom:-80px;right:-80px;filter:blur(40px);pointer-events:none;animation:blob 10s ease-in-out infinite alternate-reverse }
@keyframes blob { to { transform:translate(30px,-20px) scale(1.05) } }
.cta-block h2 {
  font-family:var(--font-d); font-size:clamp(3.5rem,6vw,7rem);
  letter-spacing:2px; line-height:.9; color:#FFFFFF;
  margin-bottom:24px; position:relative; z-index:1;
}
.cta-block p { font-size:17px; color:rgba(255,255,255,.65); font-weight:300; margin-bottom:56px; position:relative; z-index:1 }
.cta-btns { display:flex; gap:16px; justify-content:center; flex-wrap:wrap; position:relative; z-index:1 }
/* Override btn-primary inside cta */
.cta-block .btn-primary { background:#FFFFFF; color:var(--brand) }
.cta-block .btn-primary span { color:var(--brand) }
.cta-block .btn-primary::before { background:linear-gradient(135deg,var(--light),#FFFFFF) }
.cta-block .btn-ghost { color:rgba(255,255,255,.7) }
.cta-block .btn-ghost:hover { color:#FFFFFF }
.cta-block .btn-ghost .arr { border-color:rgba(255,255,255,.25); color:rgba(255,255,255,.6) }
.cta-block .btn-ghost:hover .arr { border-color:rgba(255,255,255,.5); color:#FFFFFF }

/* ═══════════════════════════════════════════════════════════
   TEAM CARDS
═══════════════════════════════════════════════════════════ */
.team-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1px; background:var(--rim); border:1px solid var(--rim); border-radius:12px; overflow:hidden }
.team-card { background:var(--panel); overflow:hidden; transition:background .3s }
.team-card:hover { background:var(--deep) }
.team-img { height:280px; overflow:hidden; background:var(--deep); position:relative }
.team-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s }
.team-card:hover .team-img img { transform:scale(1.05) }
.team-img-ph { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:4rem;background:linear-gradient(135deg,rgba(var(--brand-rgb),.05),rgba(var(--accent-rgb),.05)) }
.team-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(var(--brand-rgb),.88) 0%,transparent 55%);opacity:0;transition:opacity .3s }
.team-card:hover .team-overlay { opacity:1 }
.team-socials { position:absolute;bottom:-50px;left:0;right:0;padding:16px;display:flex;gap:8px;justify-content:center;transition:bottom .3s }
.team-card:hover .team-socials { bottom:0 }
.team-soc { padding:7px 14px;border:1.5px solid rgba(255,255,255,.3);color:#fff;font-family:var(--font-ui);font-size:10px;font-weight:500;letter-spacing:1.5px;transition:background .2s;border-radius:4px }
.team-soc:hover { background:rgba(255,255,255,.15) }
.team-info { padding:22px 28px; border-top:1px solid var(--rim) }
.team-name { font-family:var(--font-ui);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:4px;letter-spacing:.3px }
.team-pos { font-size:11px;color:var(--brand);letter-spacing:2px;text-transform:uppercase;opacity:.7 }

/* ═══════════════════════════════════════════════════════════
   BLOG CARDS
═══════════════════════════════════════════════════════════ */
.blog-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:1px;background:var(--rim);border:1px solid var(--rim);border-radius:12px;overflow:hidden }
.blog-card { background:var(--panel);display:block;transition:background .3s;overflow:hidden }
.blog-card:hover { background:var(--deep) }
.blog-img { height:220px;overflow:hidden;background:var(--deep);position:relative }
.blog-img img { width:100%;height:100%;object-fit:cover;transition:transform .5s }
.blog-card:hover .blog-img img { transform:scale(1.05) }
.blog-img-ph { width:100%;height:100%;background:linear-gradient(135deg,rgba(var(--brand-rgb),.05),rgba(var(--accent-rgb),.06));display:flex;align-items:center;justify-content:center;font-size:3rem }
.blog-cat-tag { position:absolute;top:16px;left:16px;background:var(--brand);color:#fff;font-family:var(--font-ui);font-size:9.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 12px;border-radius:4px }
.blog-body { padding:32px }
.blog-meta { font-size:11px;color:var(--dim);letter-spacing:2px;text-transform:uppercase;margin-bottom:12px }
.blog-title { font-family:var(--font-ui);font-size:1.2rem;font-weight:700;color:var(--text);line-height:1.2;margin-bottom:10px;transition:color .2s;letter-spacing:.3px }
.blog-card:hover .blog-title { color:var(--brand) }
.blog-excerpt { font-size:14px;color:var(--muted);line-height:1.78;margin-bottom:24px;font-weight:300 }
.blog-more { font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:var(--brand);display:inline-flex;align-items:center;gap:8px;transition:gap .2s }
.blog-card:hover .blog-more { gap:14px }

/* ═══════════════════════════════════════════════════════════
   PORTFOLIO GRID
═══════════════════════════════════════════════════════════ */
.port-grid { display:grid;grid-template-columns:repeat(12,1fr);gap:1px;background:var(--rim);border:1px solid var(--rim);grid-auto-rows:200px;border-radius:12px;overflow:hidden }
.port-item { background:var(--deep);overflow:hidden;position:relative;display:block;transition:opacity .3s }
.port-item:hover { opacity:.93 }
.port-item img { width:100%;height:100%;object-fit:cover;transition:transform .6s }
.port-item:hover img { transform:scale(1.05) }
.port-ph { width:100%;height:100%;background:linear-gradient(135deg,rgba(var(--brand-rgb),.06),rgba(var(--accent-rgb),.08));display:flex;align-items:center;justify-content:center;font-size:3.5rem }
.port-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(var(--brand-rgb),.92) 0%,rgba(var(--brand-rgb),.15) 40%,transparent 65%);opacity:0;transition:opacity .35s }
.port-item:hover .port-overlay { opacity:1 }
.port-info { position:absolute;bottom:0;left:0;right:0;padding:24px;transform:translateY(20px);opacity:0;transition:all .35s }
.port-item:hover .port-info { transform:none;opacity:1 }
.port-cat { font-family:var(--font-ui);font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;background:#fff;color:var(--brand);padding:4px 10px;display:inline-block;margin-bottom:8px;border-radius:3px }
.port-title { font-family:var(--font-d);font-size:1.3rem;letter-spacing:1px;color:#fff }

/* ═══════════════════════════════════════════════════════════
   CONTACT FORM
═══════════════════════════════════════════════════════════ */
.field-wrap { margin-bottom:16px }
.field-wrap label { font-family:var(--font-ui);font-size:10.5px;font-weight:600;letter-spacing:3.5px;text-transform:uppercase;color:var(--brand);display:block;margin-bottom:8px;opacity:.7 }
.field-input {
  width:100%;background:var(--void);border:1.5px solid var(--rim2);
  padding:15px 18px;color:var(--text);font-family:var(--font-b);font-size:14px;
  outline:none;transition:border-color .25s,background .25s;
  border-radius:8px;
}
.field-input:focus { border-color:rgba(var(--brand-rgb),.45);background:var(--panel);box-shadow:0 0 0 3px rgba(var(--brand-rgb),.06) }
.field-input::placeholder { color:var(--dim) }
.field-error { font-size:12px;color:#e55;margin-top:5px;display:block }
select.field-input { -webkit-appearance:none;appearance:none;color:var(--muted);padding-right:44px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236c2bd9' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 16px center }

/* ═══════════════════════════════════════════════════════════
   INFO CARDS (contact info)
═══════════════════════════════════════════════════════════ */
.info-row { display:flex;flex-direction:column;gap:8px }
.info-card {
  background:var(--panel);border:1.5px solid var(--rim);padding:22px 26px;
  display:flex;align-items:center;gap:18px;
  transition:border-color .3s,background .3s;border-radius:10px;
  box-shadow:var(--shadow);
}
.info-card:hover { border-color:rgba(var(--brand-rgb),.25);background:var(--deep) }
.info-icon { width:46px;height:46px;border:1.5px solid rgba(var(--brand-rgb),.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;transition:border-color .3s;border-radius:8px;background:var(--deep) }
.info-card:hover .info-icon { border-color:rgba(var(--brand-rgb),.3) }
.info-label { font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--brand);margin-bottom:4px;opacity:.7 }
.info-value { font-size:14.5px;color:var(--text);font-weight:400 }
.info-arr { margin-left:auto;color:var(--brand);opacity:.35;font-size:1.1rem;transition:opacity .2s,transform .2s }
.info-card:hover .info-arr { opacity:.8;transform:translateX(4px) }

/* ═══════════════════════════════════════════════════════════
   CAREER LISTING
═══════════════════════════════════════════════════════════ */
.job-list { display:flex;flex-direction:column;gap:8px;margin-top:60px }
.job-card {
  background:var(--panel);border:1.5px solid var(--rim);overflow:hidden;
  transition:border-color .3s;border-radius:12px;box-shadow:var(--shadow);
}
.job-card:hover { border-color:rgba(var(--brand-rgb),.22) }
.job-head { padding:28px 36px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;cursor:pointer }
.job-title { font-family:var(--font-ui);font-size:1.2rem;font-weight:700;color:var(--text);margin-bottom:6px;letter-spacing:.3px }
.job-tags { display:flex;gap:8px;flex-wrap:wrap }
.job-tag { font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:5px 12px;border:1.5px solid var(--rim2);color:var(--dim);border-radius:100px }
.job-tag.remote { border-color:rgba(var(--brand-rgb),.2);color:var(--brand) }
.job-body { display:grid;grid-template-rows:0fr;transition:grid-template-rows .6s var(--ease-out) }
.job-body > * { overflow:hidden;min-height:0 }
.job-body-inner { padding:0 36px 32px;border-top:1px solid var(--rim) }
.job-body-inner p { font-size:14.5px;color:var(--muted);line-height:1.8;font-weight:300;margin:20px 0 28px }
.job-card.open .job-body { grid-template-rows:1fr }
.job-head:focus-visible { outline-offset:-3px }

/* ═══════════════════════════════════════════════════════════
   REVEAL ANIMATIONS — cinematic blur-in
═══════════════════════════════════════════════════════════ */
html.js:not(.m-on) .reveal   { opacity:0;transform:translateY(32px);transition:opacity .8s,transform .8s }
html.js:not(.m-on) .reveal-l { opacity:0;transform:translateX(-32px);transition:opacity .8s .1s,transform .8s .1s }
html.js:not(.m-on) .reveal-r { opacity:0;transform:translateX(32px);transition:opacity .8s .1s,transform .8s .1s }
html.js:not(.m-on) .reveal.vis,html.js:not(.m-on) .reveal-l.vis,html.js:not(.m-on) .reveal-r.vis { opacity:1;transform:none }
@media(prefers-reduced-motion:reduce){
  html { scroll-behavior:auto }
  *,*::before,*::after { animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important }
}

/* ═══════════════════════════════════════════════════════════
   PAGE CURTAIN — driven by the motion layer. It lifts itself if the bundle never arrives.
═══════════════════════════════════════════════════════════ */
#curtain { display:none; position:fixed; inset:0; z-index:100000; pointer-events:none; color:#fff }
html.m-on #curtain { display:block; animation:cu-failsafe 0s 5s forwards }
@keyframes cu-failsafe { to { visibility:hidden } }
.cu-cols { position:absolute; inset:0; display:flex }
.cu-cols i { flex:1; height:100%; margin-right:-1px; background:var(--brand) }
.cu-stage { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:18px; text-align:center; padding:0 24px }
.cu-word { font-family:var(--font-d); font-size:clamp(3rem,10vw,8rem); letter-spacing:6px; line-height:1; display:flex; flex-wrap:wrap; justify-content:center; overflow:hidden; visibility:hidden }
.cu-word span { display:inline-block; white-space:pre }
.cu-count { font-family:var(--font-ui); font-size:12px; font-weight:600; letter-spacing:5px; opacity:.55; visibility:hidden }
.cu-label { font-family:var(--font-d); font-size:clamp(2.2rem,6vw,5rem); letter-spacing:4px; line-height:1; visibility:hidden }

/* ═══════════════════════════════════════════════════════════
   GRAIN TEXTURE — subtle editorial noise overlay
═══════════════════════════════════════════════════════════ */
#grain {
  position:fixed; inset:-60px; z-index:9996; pointer-events:none;
  opacity:.035; mix-blend-mode:overlay;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
}
@media(max-width:900px){ #grain{ display:none } }

/* ═══════════════════════════════════════════════════════════
   SCROLL TO TOP
═══════════════════════════════════════════════════════════ */
#to-top {
  position:fixed; bottom:28px; right:28px; width:48px; height:48px;
  border-radius:50%; background:var(--panel); border:1.5px solid var(--rim2);
  display:flex; align-items:center; justify-content:center; z-index:500;
  opacity:0; visibility:hidden; transform:translateY(14px) scale(.9);
  transition:opacity .3s, transform .3s, visibility .3s, border-color .3s, box-shadow .3s;
  box-shadow:var(--shadow); padding:0; font-family:inherit;
}
#to-top.show { opacity:1; visibility:visible; transform:none }
#to-top:hover { border-color:var(--brand); box-shadow:var(--glow-c) }
#to-top svg { width:17px; height:17px; color:var(--brand); transition:transform .25s }
#to-top:hover svg { transform:translateY(-2px) }
@media(max-width:900px){ #to-top{ right:16px; bottom:16px; width:42px; height:42px } }

/* ═══════════════════════════════════════════════════════════
   BUTTON SHINE SWEEP
═══════════════════════════════════════════════════════════ */
.btn-primary,.btn-price-fill { isolation:isolate; position:relative; overflow:hidden }
.btn-primary::after,.btn-price-fill::after {
  content:''; position:absolute; top:0; left:-60%; width:35%; height:100%;
  background:linear-gradient(115deg, transparent, rgba(255,255,255,.4), transparent);
  transform:skewX(-18deg); transition:left .65s cubic-bezier(.25,.46,.45,.94); pointer-events:none; z-index:2;
}
.btn-primary:hover::after,.btn-price-fill:hover::after { left:130% }

/* ═══════════════════════════════════════════════════════════
   CUSTOM SCROLLBAR
═══════════════════════════════════════════════════════════ */
html { scrollbar-width:thin; scrollbar-color:var(--brand) var(--void) }
::-webkit-scrollbar { width:10px; height:10px }
::-webkit-scrollbar-track { background:var(--void) }
::-webkit-scrollbar-thumb { background:linear-gradient(var(--brand),var(--accent)); border-radius:10px; border:2px solid var(--void) }
::-webkit-scrollbar-thumb:hover { background:var(--brand) }

/* ═══════════════════════════════════════════════════════════
   FOOTER
═══════════════════════════════════════════════════════════ */
footer {
  background:var(--brand); padding:90px 24px 44px;
  border-top:none; position:relative; z-index:1;
}
.footer-inner { max-width:1400px;margin:0 auto }
.footer-top { display:grid;grid-template-columns:2fr repeat(3,1fr);gap:60px;margin-bottom:72px;padding-bottom:72px;border-bottom:1px solid rgba(255,255,255,.1) }
.footer-brand {
  font-family:var(--font-d);font-size:2.4rem;letter-spacing:3px;
  color:#FFFFFF; display:block;margin-bottom:14px;
}
.footer-tagline { font-size:14px;font-weight:300;color:rgba(255,255,255,.5);line-height:1.8;max-width:280px;margin-bottom:32px }
.footer-socials { display:flex;gap:8px;flex-wrap:wrap }
.f-soc { font-family:var(--font-ui);font-size:10px;letter-spacing:2.5px;text-transform:uppercase;padding:9px 18px;border:1.5px solid rgba(255,255,255,.15);color:rgba(255,255,255,.55);transition:all .25s;border-radius:6px }
.f-soc:hover { border-color:rgba(255,255,255,.4);color:#fff }
.footer-col h5 { font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:24px }
.footer-col ul { list-style:none;display:flex;flex-direction:column;gap:12px }
.footer-col ul li a { font-size:14px;font-weight:300;color:rgba(255,255,255,.5);transition:color .2s }
.footer-col ul li a:hover { color:#fff }
.footer-bottom { display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;font-size:12px;color:rgba(255,255,255,.35) }
.footer-bottom a { color:rgba(255,255,255,.35);transition:color .2s }
.footer-bottom a:hover { color:rgba(255,255,255,.75) }

/* ═══════════════════════════════════════════════════════════
   ABOUT VISUAL
═══════════════════════════════════════════════════════════ */
.about-visual { position:relative;height:360px;overflow:hidden;border:1px solid var(--rim);border-radius:12px }
.about-visual-inner { position:absolute;inset:0;background:linear-gradient(135deg,rgba(var(--brand-rgb),.04),rgba(var(--accent-rgb),.06));display:flex;align-items:center;justify-content:center;overflow:hidden }
.circuit-line { position:absolute;background:rgba(var(--brand-rgb),.15) }
.circuit-line.h { height:1px;animation:circ-h 4s ease-in-out infinite }
.circuit-line.v { width:1px;animation:circ-v 3s ease-in-out infinite }
@keyframes circ-h { 0%,100%{left:0;right:100%;opacity:0} 20%{opacity:1} 50%{left:0;right:0;opacity:.5} 80%{opacity:1} to{left:100%;right:0;opacity:0} }
@keyframes circ-v { 0%,100%{top:0;bottom:100%;opacity:0} 20%{opacity:1} 50%{top:0;bottom:0;opacity:.5} 80%{opacity:1} to{top:100%;bottom:0;opacity:0} }
.about-big-num { font-family:var(--font-d);font-size:9rem;letter-spacing:-4px;line-height:.8;background:linear-gradient(135deg,rgba(var(--brand-rgb),.14),rgba(var(--accent-rgb),.12));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text }

.a-value { padding:28px 0;border-bottom:1px solid var(--rim);display:flex;align-items:flex-start;gap:20px;transition:padding-left .3s }
.a-value:hover { padding-left:14px }
.a-value-icon { width:40px;height:40px;flex-shrink:0;border:1.5px solid var(--rim2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;transition:border-color .3s,background .3s;border-radius:8px;background:var(--panel) }
.a-value:hover .a-value-icon { border-color:rgba(var(--brand-rgb),.25);background:var(--deep) }
.a-value h4 { font-family:var(--font-ui);font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:5px;letter-spacing:.3px }
.a-value p { font-size:13.5px;color:var(--muted);line-height:1.7;font-weight:300 }

/* Benefit box */
.benefit-box {
  background:var(--panel);border:1px solid var(--rim);padding:36px 32px;
  border-top:3px solid transparent;transition:all .3s;
}
.benefit-box:hover { background:var(--deep);border-color:rgba(var(--brand-rgb),.15);border-top-color:var(--brand) }
.benefit-icon { font-size:1.8rem;margin-bottom:16px }
.benefit-box h4 { font-family:var(--font-ui);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:8px;letter-spacing:.3px }
.benefit-box p { font-size:13.5px;color:var(--muted);line-height:1.75;font-weight:300 }

/* Audit items */
.audit-item { background:var(--panel);border:1.5px solid var(--rim);padding:24px 28px;display:flex;gap:18px;align-items:flex-start;transition:border-color .3s,transform .3s;border-radius:10px;box-shadow:var(--shadow) }
.audit-item:hover { border-color:rgba(var(--brand-rgb),.25);transform:translateX(6px) }
.audit-item-icon { width:44px;height:44px;flex-shrink:0;border:1.5px solid rgba(var(--brand-rgb),.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;transition:border-color .3s;border-radius:8px;background:var(--deep) }
.audit-item:hover .audit-item-icon { border-color:rgba(var(--brand-rgb),.3) }
.audit-item h4 { font-family:var(--font-ui);font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:5px;letter-spacing:.3px }
.audit-item p { font-size:13.5px;color:var(--muted);line-height:1.7;font-weight:300 }

/* Blog content styles */
.blog-content h2,.blog-content h3 { font-family:var(--font-d);text-transform:uppercase;font-weight:normal;color:var(--text);margin:40px 0 16px;letter-spacing:1px }
.blog-content h2 { font-size:2.2rem;letter-spacing:1.5px }
.blog-content h3 { font-size:1.6rem }
.blog-content p { margin-bottom:20px;font-size:16px;color:var(--muted);line-height:1.9;font-weight:300 }
.blog-content strong { color:var(--text);font-weight:600 }
.blog-content em { color:var(--brand);font-style:italic }
.blog-content a { color:var(--brand);border-bottom:1px solid rgba(var(--brand-rgb),.2);transition:border-color .2s }
.blog-content a:hover { border-color:var(--brand) }
.blog-content ul,.blog-content ol { margin:20px 0 20px 20px }
.blog-content li { color:var(--muted);line-height:1.85;margin-bottom:8px;font-size:15px;font-weight:300 }
.blog-content blockquote { border-left:3px solid var(--brand);padding:20px 28px;background:var(--deep);margin:32px 0;font-style:italic;font-size:17px;color:var(--text);font-weight:300;border-radius:0 8px 8px 0 }
.blog-content img { max-width:100%;margin:28px 0;border:1px solid var(--rim);border-radius:8px }
.blog-content code { background:var(--deep);border:1px solid rgba(var(--brand-rgb),.12);padding:2px 8px;font-size:13px;color:var(--brand);border-radius:4px }
.blog-content pre { background:var(--brand);border:none;padding:24px;overflow-x:auto;margin:24px 0;border-radius:10px }
.blog-content pre code { background:none;border:none;padding:0;color:rgba(255,255,255,.85);font-size:13.5px }
.blog-content hr { border:none;border-top:1px solid var(--rim);margin:48px 0 }

/* Alert/Flash */
.flash-success { background:rgba(var(--brand-rgb),.06);border:1.5px solid rgba(var(--brand-rgb),.15);padding:16px 22px;display:flex;gap:12px;align-items:center;margin-bottom:28px;border-radius:8px }
.flash-success span.icon { color:var(--brand);font-size:1.2rem }
.flash-success span.msg { font-size:14px;color:var(--text);font-weight:400 }

/* Pagination */
.pagi { display:flex;justify-content:center;gap:6px;margin-top:60px }
.pagi a,.pagi span { padding:11px 18px;border:1.5px solid var(--rim);font-family:var(--font-ui);font-size:12px;font-weight:500;letter-spacing:1px;transition:all .2s;border-radius:8px }
.pagi a { color:var(--muted);background:var(--panel) }
.pagi a:hover { border-color:rgba(var(--brand-rgb),.3);color:var(--brand) }
.pagi .current { border-color:rgba(var(--brand-rgb),.3);color:var(--brand);background:var(--deep) }
.pagi .disabled { color:var(--dim);opacity:.4 }

/* ═══════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════ */
@media(max-width:1100px) {
  .price-wrap { grid-template-columns:1fr 1fr }
  .testi-wrap { grid-template-columns:1fr 1fr }
  .footer-top { grid-template-columns:1fr 1fr 1fr }
  .footer-top>div:first-child { grid-column:1/-1 }
  .stats-row { grid-template-columns:repeat(2,1fr) }
}
@media(max-width:900px) {
  .section { padding:90px 16px }
  .hero { padding:100px 16px 80px }
  .page-hero { padding:130px 16px 70px }
  .proc-row { grid-template-columns:1fr }
  .proc-item { border-right:none;border-bottom:1px solid var(--rim) }
  .price-wrap,.testi-wrap { grid-template-columns:1fr }
  .cta-block { padding:80px 32px }
  footer { padding:60px 16px 32px }
  .data-line { display:none }
  #cur-dot,#cur-ring,#cur-trail { display:none }
  body { cursor:auto }
  .hero-stats { position:relative;right:auto;bottom:auto;flex-direction:row;flex-wrap:wrap;margin-top:40px }
  .scroll-ind { display:none }
  .orbit-wrap { right:-350px }
}
@media(max-width:640px) {
  .footer-top { grid-template-columns:1fr 1fr }
  .stats-row { grid-template-columns:1fr 1fr }
  .hero-stats { flex-direction:column }
  .blog-grid { grid-template-columns:1fr }
  .team-grid { grid-template-columns:1fr }
}

/* ═══════════════════════════════════════════════════════════
   LAYOUT HELPERS — replace inline grids that never collapsed on phones
═══════════════════════════════════════════════════════════ */
.split { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:start }
.split-end { display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:end; margin-bottom:56px }
.split-side { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:64px; align-items:start }
.split-band { display:grid; grid-template-columns:1fr auto; gap:32px; align-items:center }
.form-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px }
.form-card { background:var(--panel); border:1px solid var(--rim); border-top:3px solid var(--brand); padding:48px; border-radius:14px; box-shadow:var(--shadow) }
.sticky-side { position:sticky; top:100px }
.hp-field { position:absolute!important; left:-9999px; width:1px; height:1px; overflow:hidden }
@media(max-width:900px){
  .split,.split-end,.split-side,.split-band { grid-template-columns:minmax(0,1fr); gap:40px }
  .sticky-side { position:static }
  .form-card { padding:28px 20px }
  .text-end-md { text-align:left!important }
  .text-end-md .s-body { margin-left:0!important }
  .port-grid { grid-auto-rows:220px }
  .port-item { grid-column:1/-1!important }
  .feat-post { grid-template-columns:minmax(0,1fr)!important }
  .band-pad { padding:32px 24px!important }
}
@media(max-width:560px){ .form-2 { grid-template-columns:minmax(0,1fr); gap:0 } }

/* Filter pills */
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:56px }
.filter-pill {
  font-family:var(--font-ui); font-size:11px; font-weight:600; letter-spacing:2.5px; text-transform:uppercase;
  padding:10px 22px; border:1.5px solid var(--rim2); border-radius:100px; color:var(--muted); transition:all .3s var(--ease-out);
}
.filter-pill:hover { border-color:var(--brand); color:var(--brand); transform:translateY(-2px) }
.filter-pill.on { background:var(--brand); border-color:var(--brand); color:#fff }

/* Empty states */
.empty-state { background:var(--panel); border:1.5px dashed var(--rim2); border-radius:18px; padding:72px 32px; text-align:center }
.empty-state .ico { font-size:3rem; margin-bottom:16px; display:inline-block; animation:empty-float 3.2s ease-in-out infinite }
.empty-state h3 { font-family:var(--font-d); font-size:2rem; letter-spacing:1px; color:var(--text); margin-bottom:12px }
.empty-state p { font-size:15px; color:var(--muted); max-width:420px; margin:0 auto 28px; font-weight:300; line-height:1.7 }
@keyframes empty-float { 0%,100%{transform:translateY(0) rotate(-4deg)} 50%{transform:translateY(-10px) rotate(4deg)} }

/* Stats band / statement / featured work / client logos (home) */
.stats-band { background:var(--deep); border-top:1px solid var(--rim); border-bottom:1px solid var(--rim); position:relative; z-index:1; padding:80px 0 }
.stats-band .stats-row { margin-top:0 }
.statement { padding:150px 24px; position:relative; z-index:1 }
.statement p { max-width:1020px; margin:0 auto; text-align:center; font-family:var(--font-ui); font-weight:700; font-size:clamp(1.7rem,4.2vw,3.6rem); line-height:1.2; letter-spacing:-.5px; color:var(--text) }
.work-section { padding:130px 0 100px; position:relative; z-index:1; overflow:hidden }
.work-head { max-width:1100px; margin:0 auto 56px; padding:0 48px; display:flex; align-items:flex-end; justify-content:space-between; gap:24px; flex-wrap:wrap }
.work-track { display:flex; gap:24px; padding:0 48px; width:max-content }
.work-card { width:min(560px,78vw); flex-shrink:0; display:block; border-radius:18px; overflow:hidden; background:var(--deep); position:relative; aspect-ratio:4/3 }
.work-card img { width:100%; height:100%; object-fit:cover }
.work-ph { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:4rem; background:linear-gradient(135deg,rgba(var(--brand-rgb),.08),rgba(var(--accent-rgb),.14)) }
.work-info { position:absolute; inset:auto 0 0 0; padding:28px; background:linear-gradient(to top,rgba(var(--brand-rgb),.9),transparent); color:#fff }
.work-info span { font-family:var(--font-ui); font-size:10px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase; opacity:.8; display:block; margin-bottom:6px }
.work-info b { font-family:var(--font-d); font-weight:400; font-size:1.9rem; letter-spacing:1px; line-height:1 }
@media(max-width:1023px){ .work-track { width:auto; overflow-x:auto; scroll-snap-type:x mandatory; padding:0 24px 16px } .work-card { scroll-snap-align:center } .work-head { padding:0 24px } }
.clients { padding:64px 0; position:relative; z-index:1; overflow:hidden; border-top:1px solid var(--rim); border-bottom:1px solid var(--rim) }
.clients-label { text-align:center; font-family:var(--font-ui); font-size:11px; font-weight:600; letter-spacing:4px; text-transform:uppercase; color:var(--dim); margin-bottom:32px }
.clients-track { display:flex; flex-wrap:wrap; justify-content:center; gap:40px 72px; align-items:center; padding:0 24px }
.clients-track.is-loop { flex-wrap:nowrap; justify-content:flex-start; width:max-content; padding:0 72px 0 0 }
.clients-track img { height:40px; width:auto; max-width:170px; object-fit:contain; filter:grayscale(1); opacity:.55; transition:filter .3s,opacity .3s }
.clients-track a:hover img,.clients-track img:hover { filter:none; opacity:1 }
html[data-theme="dark"] .clients-track img { filter:grayscale(1) brightness(0) invert(1); opacity:.6 }
html[data-theme="dark"] .clients-track a:hover img,html[data-theme="dark"] .clients-track img:hover { filter:grayscale(1) brightness(0) invert(1); opacity:1 }

/* Footer wordmark */
.footer-wordmark { font-family:var(--font-d); font-size:clamp(4rem,17.5vw,17rem); line-height:.8; letter-spacing:2px; color:rgba(255,255,255,.13); text-align:center; white-space:nowrap; overflow:hidden; margin-top:56px; user-select:none }
.footer-wordmark span { display:inline-block; white-space:pre }

/* Accessibility */
.skip-link { position:fixed; top:-60px; left:16px; z-index:100001; background:var(--brand); color:#fff; padding:10px 18px; border-radius:8px; font-family:var(--font-ui); font-size:13px; transition:top .2s }
.skip-link:focus { top:12px }
a:focus-visible,button:focus-visible,[role="button"]:focus-visible,select:focus-visible,summary:focus-visible { outline:2.5px solid var(--accent); outline-offset:3px; border-radius:4px }
.cta-block h2 .g,.g-light { background:linear-gradient(135deg,var(--light),var(--magenta)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text }
.lb-close { position:absolute; top:20px; right:20px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.3); color:#fff; width:44px; height:44px; border-radius:50%; font-size:1.1rem; cursor:pointer }
.gallery-btn { display:block; width:100%; padding:0; border:0; background:none; aspect-ratio:16/10; overflow:hidden; cursor:zoom-in }
.gallery-btn img { width:100%; height:100%; object-fit:cover; transition:transform .5s }
.gallery-btn:hover img { transform:scale(1.05) }
@media(hover:none){ .svc-link { opacity:1!important; transform:none!important } .port-overlay,.port-info { opacity:1!important; transform:none!important } }
.svc-link::after { content:''; position:absolute; inset:0; z-index:2 }

/* ═══════════════════════════════════════════════════════════
   DARK MODE
═══════════════════════════════════════════════════════════ */
html[data-theme="dark"] {
  color-scheme: dark;
  --void: #0d0a18; --deep: #151026; --panel: #1a1430; --panel-rgb: 26,20,48;
  --tint-1: #151026; --tint-2: #211a3d;
  --text: #f1eefc; --muted: rgba(226,219,250,.74); --dim: rgba(226,219,250,.52);
  --brand: #8b5cf6; --brand-rgb: 139,92,246; --brand2: #9d74f8;
  --accent: #a78bfa; --accent-rgb: 167,139,250; --cyan: #a78bfa; --violet: #a78bfa; --magenta: #c4b5fd;
  --rim: rgba(196,181,253,.13); --rim2: rgba(196,181,253,.24);
  --shadow: 0 2px 24px rgba(0,0,0,.35); --shadow-lg: 0 8px 48px rgba(0,0,0,.5);
  --aurora-a: #7c3aed; --aurora-b: #6d28d9; --aurora-c: #4c1d95; --aurora-k: 1.25;
  --sv-text: #f1eefc; --sv-text-2: rgba(226,219,250,.76); --sv-text-3: rgba(226,219,250,.52);
  --sv-border: rgba(196,181,253,.14); --sv-bg: #0d0a18; --sv-bg-2: #151026;
}
html[data-theme="dark"] .ab-page {
  --ab-muted: rgba(226,219,250,.74); --ab-border: rgba(196,181,253,.14); --ab-bg: #0d0a18; --ab-bg2: #151026;
  --ab-text: #f1eefc; --ab-fill: #211a3d; --ab-fill2: #1a1430;
}
html[data-theme="dark"] .nav-logo img { filter: brightness(0) invert(1) }
html[data-theme="dark"] .nav-center a { color: var(--muted) }
html[data-theme="dark"] .nav-center a:hover, html[data-theme="dark"] .nav-center a.active { color: var(--text) }
html[data-theme="dark"] .nav-center a.active::after { background: var(--accent) }
html[data-theme="dark"] .ham span { background: var(--text) }
html[data-theme="dark"] .s-tag, html[data-theme="dark"] .price-tier, html[data-theme="dark"] .team-pos, html[data-theme="dark"] .field-wrap label { color: var(--accent); opacity: 1 }
html[data-theme="dark"] .btn-outline, html[data-theme="dark"] .btn-price-out, html[data-theme="dark"] .breadcrumb a, html[data-theme="dark"] .blog-content em, html[data-theme="dark"] .blog-content a { color: var(--accent) }
html[data-theme="dark"] select.field-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23c4b5fd' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") }
html[data-theme="dark"] footer { background: #120c24; border-top: 1px solid var(--rim) }
html[data-theme="dark"] .marquee-section { background: #1c1238 }
html[data-theme="dark"] .cta-block { background: linear-gradient(135deg,#3b1d8f,#5b21b6); border-color: rgba(196,181,253,.2) }
html[data-theme="dark"] .ab-cta-box, html[data-theme="dark"] .sv-cta { background: linear-gradient(135deg,#3b1d8f,#5b21b6) }
html[data-theme="dark"] .sv-btn-primary, html[data-theme="dark"] .sv-btn-live { background: var(--brand) }
html[data-theme="dark"] .sv-btn-primary:hover, html[data-theme="dark"] .sv-btn-live:hover { background: var(--brand2) }
html[data-theme="dark"] .sv-btn-white { color: #5b21b6 }
html[data-theme="dark"] .cu-cols i { background: #1c1238 }
html[data-theme="dark"] .cta-block .btn-primary span, html[data-theme="dark"] .cta-block .btn-primary { color: #5b21b6 }
html[data-theme="dark"] .blog-content pre { background: #151026; border: 1px solid var(--rim) }
html[data-theme="dark"] .job-tag.remote { color: var(--accent) }
html[data-theme="dark"] .btn-ghost .arr { color: var(--text) }
html[data-theme="dark"] .ab-founder-link, html[data-theme="dark"] .ab-team-soc { background: var(--panel); color: var(--text) }

/* Theme toggle */
.theme-btn {
  width: 40px; height: 40px; flex-shrink: 0; margin-left: auto; margin-right: 8px; padding: 0;
  position: relative; display: flex; align-items: center; justify-content: center; cursor: pointer;
  border-radius: 50%; border: 1px solid var(--rim2); background: transparent; color: var(--brand);
  transition: background .3s, border-color .3s, transform .3s var(--ease-out);
}
.theme-btn:hover { background: rgba(var(--brand-rgb),.1); transform: rotate(14deg) }
html[data-theme="dark"] .theme-btn { color: #fde9ff }
.theme-btn svg { position: absolute; width: 18px; height: 18px; transition: transform .55s var(--ease-out), opacity .3s }
.theme-btn .i-moon { opacity: 1; transform: none }
.theme-btn .i-sun { opacity: 0; transform: rotate(-90deg) scale(.4) }
html[data-theme="dark"] .theme-btn .i-moon { opacity: 0; transform: rotate(90deg) scale(.4) }
html[data-theme="dark"] .theme-btn .i-sun { opacity: 1; transform: none }
::view-transition-old(root), ::view-transition-new(root) { animation: none; mix-blend-mode: normal }
::view-transition-new(root) { z-index: 9999 }
@media (max-width: 420px) { .theme-btn { width: 36px; height: 36px; margin-right: 2px } .nav-logo img { height: 28px !important } .nav-logo { font-size: 1.2rem } .nav-logo-glyph { display: none } }
</style>
</head>
<body>
<a href="#main" class="skip-link" data-no-curtain>Skip to content</a>

<!-- PAGE CURTAIN -->
<div id="curtain" aria-hidden="true">
  <div class="cu-cols"><i></i><i></i><i></i><i></i><i></i></div>
  <div class="cu-stage">
    <div class="cu-word" data-word="{{ strtoupper($settings['site_name'] ?? 'SK Artistic') }}"></div>
    <div class="cu-count">000</div>
    <div class="cu-label"></div>
  </div>
</div>

<!-- GRAIN TEXTURE -->
<div id="grain"></div>

<!-- CURSOR -->
<div id="cur-dot"></div>
<div id="cur-ring"><span id="cur-label"></span></div>
<div id="cur-trail"></div>
<!-- SCROLL PROGRESS -->
<div id="spb"></div>
<!-- DATA FLOW LINE -->
<div class="data-line"></div>
<!-- CANVAS BG -->
<canvas id="bg-canvas" aria-hidden="true"></canvas>

<!-- SCROLL TO TOP -->
<button id="to-top" aria-label="Scroll to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
</button>

<!-- ═══ NAV ═══ -->
<nav class="nav" id="navbar">
  <a href="{{ route('home') }}" class="nav-logo">
    
    @if(!empty($settings['site_logo']))
      <img src="{{ asset($settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'SK Artistic' }}" style="-webkit-text-fill-color:unset;background:none;height:36px;">
    @else
    <div class="nav-logo-glyph"><span>SK</span></div>
      {{ $settings['site_name'] ?? 'SK ARTISTIC' }}
    @endif
  </a>

  <button class="ham" id="ham" aria-label="Menu" aria-expanded="false" aria-controls="nl"><span></span><span></span><span></span></button>

  <ul class="nav-center" id="nl" data-lenis-prevent>
    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
    <li>
      <a href="{{ route('services') }}" class="{{ request()->routeIs('services*') ? 'active' : '' }}">
        Services
        <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </a>
      <div class="nav-dropdown">
        @foreach($services_nav as $s)
          <a href="{{ route('service.detail',$s->slug) }}"><span class="nav-dropdown-dot"></span>{{ $s->title }}</a>
        @endforeach
      </div>
    </li>
    <li><a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio*') ? 'active' : '' }}">Portfolio</a></li>
    <li><a href="{{ route('pricing') }}" class="{{ request()->routeIs('pricing') ? 'active' : '' }}">Pricing</a></li>
    <li><a href="{{ route('team') }}" class="{{ request()->routeIs('team') ? 'active' : '' }}">Team</a></li>
    <li>
      <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">
        Blog
        <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </a>
      <div class="nav-dropdown">
        <a href="{{ route('blog') }}"><span class="nav-dropdown-dot"></span>Latest Articles</a>
        <a href="{{ route('blog',['category'=>'case-studies']) }}"><span class="nav-dropdown-dot"></span>Case Studies</a>
      </div>
    </li>
    <li><a href="{{ route('careers') }}" class="{{ request()->routeIs('careers*') ? 'active' : '' }}">Careers</a></li>
  </ul>

  <button type="button" class="theme-btn" id="theme-btn" aria-label="Switch colour theme">
    <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
    <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
  </button>

  <a href="{{ route('contact') }}" class="nav-cta">
    Contact
    <span class="nav-cta-arrow">↗</span>
  </a>
</nav>

<!-- ═══ MAIN ═══ -->
<main class="page-wrap" id="main">
  @yield('content')
</main>

<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <span class="footer-brand">{{ $settings['site_name'] ?? 'SK ARTISTIC' }}</span>
        <p class="footer-tagline">{{ $settings['site_tagline'] ?? 'Designing without borders. Full-cycle digital agency building the internet\'s next chapter.' }}</p>
        <div class="footer-socials">
          @if(!empty($settings['social_instagram']))<a rel="noopener" href="{{ $settings['social_instagram'] }}" class="f-soc" target="_blank">Instagram</a>@endif
          @if(!empty($settings['social_whatsapp']))<a rel="noopener" href="{{ $settings['social_whatsapp'] }}" class="f-soc" target="_blank">WhatsApp</a>@endif
          @if(!empty($settings['social_pinterest']))<a rel="noopener" href="{{ $settings['social_pinterest'] }}" class="f-soc" target="_blank">Pinterest</a>@endif
          @if(!empty($settings['social_youtube']))<a rel="noopener" href="{{ $settings['social_youtube'] }}" class="f-soc" target="_blank">YouTube</a>@endif
          @if(!empty($settings['social_linkedin']))<a rel="noopener" href="{{ $settings['social_linkedin'] }}" class="f-soc" target="_blank">LinkedIn</a>@endif
          @if(!empty($settings['social_twitter']))<a rel="noopener" href="{{ $settings['social_twitter'] }}" class="f-soc" target="_blank">X / Twitter</a>@endif
        </div>
      </div>
      <div class="footer-col">
        <h5>Services</h5>
        <ul>@foreach($services_nav as $s)<li><a href="{{ route('service.detail',$s->slug) }}">{{ $s->title }}</a></li>@endforeach</ul>
      </div>
      <div class="footer-col">
        <h5>Company</h5>
        <ul>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('portfolio') }}">Portfolio</a></li>
          <li><a href="{{ route('pricing') }}">Pricing</a></li>
          <li><a href="{{ route('blog') }}">Blog</a></li>
          <li><a href="{{ route('team') }}">Our Team</a></li>
          <li><a href="{{ route('careers') }}">Careers</a></li>
          @foreach($menu_pages ?? [] as $mp)
          <li><a href="{{ route('page', $mp->slug) }}">{{ $mp->title }}</a></li>
          @endforeach
        </ul>
      </div>
      <div class="footer-col">
        <h5>Contact</h5>
        <ul>
          <li><a href="{{ route('contact') }}">Get In Touch</a></li>
          <li><a href="{{ route('free-audit') }}" style="color:var(--magenta)">Free Audit ✦</a></li>
          @if(!empty($settings['site_email']))<li><a href="mailto:{{ $settings['site_email'] }}">{{ $settings['site_email'] }}</a></li>@endif
          @if(!empty($settings['site_phone']))<li><a href="tel:{{ $settings['site_phone'] }}">{{ $settings['site_phone'] }}</a></li>@endif
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>{!! $settings['footer_copyright'] ?? '© 2026 SK Artistic. All rights reserved.' !!}</span>
      <div style="display:flex;gap:20px">
        <a href="{{ route('page', 'terms-conditions') }}">Terms</a>
        <a href="{{ route('page', 'privacy-policy') }}">Privacy</a>
        <a href="{{ route('free-audit') }}" style="color:var(--magenta)">Free Audit</a>
      </div>
    </div>
    <div class="footer-wordmark" aria-hidden="true">{{ strtoupper($settings['site_name'] ?? 'SK Artistic') }}</div>
  </div>
</footer>

<!-- ═══ GLOBAL SCRIPTS ═══ -->
<script>
(function(){
  var root=document.documentElement,
      all=function(s){return Array.prototype.slice.call(document.querySelectorAll(s))};

  /* ── NAV SCROLL + PROGRESS ── */
  var nav=document.getElementById('navbar'),spb=document.getElementById('spb'),top=document.getElementById('to-top');
  function onScroll(){
    var max=document.documentElement.scrollHeight-window.innerHeight,y=window.scrollY;
    spb.style.width=(max>0?y/max*100:0)+'%';
    nav.classList.toggle('scrolled',y>40);
    if(top)top.classList.toggle('show',y>700);
  }
  window.addEventListener('scroll',onScroll,{passive:true});onScroll();
  if(top)top.addEventListener('click',function(){window.skScrollTo?window.skScrollTo(0):window.scrollTo({top:0,behavior:'smooth'})});

  /* ── HAMBURGER ── */
  var ham=document.getElementById('ham'),nl=document.getElementById('nl');
  function setMenu(open){ham.classList.toggle('open',open);nl.classList.toggle('open',open);ham.setAttribute('aria-expanded',open?'true':'false')}
  ham.addEventListener('click',function(){setMenu(!nl.classList.contains('open'))});
  nl.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){setMenu(false)})});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'){setMenu(false);window.closeLightbox()}});

  /* ── FAQ ── */
  all('.faq-q-btn').forEach(function(btn){
    btn.setAttribute('aria-expanded','false');
    btn.addEventListener('click',function(){
      var item=btn.closest('.faq-item'),open=!item.classList.contains('active');
      all('.faq-item.active').forEach(function(o){o.classList.remove('active');o.querySelector('.faq-q-btn').setAttribute('aria-expanded','false')});
      item.classList.toggle('active',open);btn.setAttribute('aria-expanded',open?'true':'false');
    });
  });

  /* ── JOB ACCORDION ── */
  all('.job-head').forEach(function(h){
    h.setAttribute('role','button');h.setAttribute('tabindex','0');h.setAttribute('aria-expanded','false');
    function toggle(){
      var c=h.closest('.job-card'),open=!c.classList.contains('open');
      all('.job-card.open').forEach(function(o){o.classList.remove('open');o.querySelector('.job-head').setAttribute('aria-expanded','false')});
      c.classList.toggle('open',open);h.setAttribute('aria-expanded',open?'true':'false');
    }
    h.addEventListener('click',toggle);
    h.addEventListener('keydown',function(e){if((e.key==='Enter'||e.key===' ')&&e.target===h){e.preventDefault();toggle()}});
  });

  /* ── LIGHTBOX ── */
  window.openLightbox=function(src){var lb=document.getElementById('lightbox');if(lb){document.getElementById('lb-img').src=src;lb.style.display='flex'}};
  window.closeLightbox=function(){var lb=document.getElementById('lightbox');if(lb)lb.style.display='none'};

  /* ── THEME TOGGLE ── */
  var themeBtn=document.getElementById('theme-btn'),metaTheme=document.getElementById('meta-theme');
  function paintTheme(t){
    root.setAttribute('data-theme',t);
    if(metaTheme)metaTheme.content=t==='dark'?'#0d0a18':'#6c2bd9';
    themeBtn.setAttribute('aria-label',t==='dark'?'Switch to light mode':'Switch to dark mode');
    window.dispatchEvent(new Event('sk-theme'));
  }
  paintTheme(root.getAttribute('data-theme')||'light');
  themeBtn.addEventListener('click',function(e){
    var next=root.getAttribute('data-theme')==='dark'?'light':'dark';
    try{localStorage.setItem('sk-theme',next)}catch(err){}
    var still=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if(!document.startViewTransition||still){paintTheme(next);return}
    var r=themeBtn.getBoundingClientRect(),x=r.left+r.width/2,y=r.top+r.height/2,
        far=Math.hypot(Math.max(x,innerWidth-x),Math.max(y,innerHeight-y));
    document.startViewTransition(function(){paintTheme(next)}).ready.then(function(){
      root.animate({clipPath:['circle(0px at '+x+'px '+y+'px)','circle('+far+'px at '+x+'px '+y+'px)']},{duration:750,easing:'cubic-bezier(.16,1,.3,1)',pseudoElement:'::view-transition-new(root)'});
    }).catch(function(){});
  });
  // Follow the device setting until the visitor has made a choice
  if(window.matchMedia)matchMedia('(prefers-color-scheme: dark)').addEventListener('change',function(e){
    var saved=null;try{saved=localStorage.getItem('sk-theme')}catch(err){}
    if(!saved)paintTheme(e.matches?'dark':'light');
  });

  /* ── REVEAL (no motion bundle, or reduced motion) ── */
  function plainReveal(){
    var sel='.reveal,.reveal-l,.reveal-r',els=all(sel);
    if(!('IntersectionObserver' in window)){els.forEach(function(el){el.classList.add('vis')});return}
    var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('vis');obs.unobserve(e.target)}})},{threshold:.07});
    els.forEach(function(el){obs.observe(el)});
  }
  if(!root.classList.contains('m-on'))plainReveal();
  else setTimeout(function(){ // bundle blocked or failed: fall back rather than leave content hidden
    if(!window.__skMotion){root.classList.remove('m-on');plainReveal()}
  },4500);
})();
</script>
@if($motionReady)
@vite(['resources/css/motion.css', 'resources/js/motion.js'])
@endif
@if($gtmId)
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i;f.parentNode.insertBefore(j,f)})(window,document,'script','dataLayer',@json($gtmId));</script>
@endif
@if($gaId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json($gaId));</script>
@endif
@if($pixelId)
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',@json($pixelId));fbq('track','PageView');</script>
@endif
</body>
</html>
