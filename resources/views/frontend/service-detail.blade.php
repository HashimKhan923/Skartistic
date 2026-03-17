@extends('frontend.layouts.app')
@section('title', ($service->tag_label ?? $service->title).' — '.($settings['site_name'] ?? 'SK Artistic'))
@section('meta_description', $service->short_description)
@php use Illuminate\Support\Str; @endphp

@section('content')
<style>
/* ═══════════════════════════════════════════════════
   SK ARTISTIC — SERVICE DETAIL PAGE
   Prefix: sv-
   ═══════════════════════════════════════════════════ */
:root {
    --sv-purple: #7c3aed;
    --sv-purple-light: rgba(124,58,237,.08);
    --sv-purple-mid: rgba(124,58,237,.15);
    --sv-text: #0f0f0f;
    --sv-text-2: #4b5563;
    --sv-text-3: #9ca3af;
    --sv-border: #e5e7eb;
    --sv-bg: #ffffff;
    --sv-bg-2: #f9fafb;
    --sv-radius: 16px;
    --sv-radius-sm: 10px;
}

/* ── Animations ── */
@keyframes sv-fadeUp   { from{opacity:0;transform:translateY(32px)} to{opacity:1;transform:translateY(0)} }
@keyframes sv-fadeIn   { from{opacity:0} to{opacity:1} }
@keyframes sv-slideL   { from{opacity:0;transform:translateX(-40px)} to{opacity:1;transform:translateX(0)} }
@keyframes sv-slideR   { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }
@keyframes sv-scaleIn  { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
@keyframes sv-lineGrow { from{width:0} to{width:100%} }
@keyframes sv-pulse    { 0%,100%{transform:scale(1)} 50%{transform:scale(1.04)} }

.sv-reveal          { opacity:0; }
.sv-reveal.sv-vis   { animation: sv-fadeUp .7s cubic-bezier(.22,1,.36,1) forwards; }
.sv-reveal-l        { opacity:0; }
.sv-reveal-l.sv-vis { animation: sv-slideL .7s cubic-bezier(.22,1,.36,1) forwards; }
.sv-reveal-r        { opacity:0; }
.sv-reveal-r.sv-vis { animation: sv-slideR .7s cubic-bezier(.22,1,.36,1) forwards; }
.sv-reveal-s        { opacity:0; }
.sv-reveal-s.sv-vis { animation: sv-scaleIn .6s cubic-bezier(.22,1,.36,1) forwards; }

/* ── Layout helpers ── */
.sv-container { max-width:1200px; margin:0 auto; padding:0 40px; }
.sv-section   { padding:100px 0; }
.sv-tag {
    display:inline-block;
    font-size:13px; font-weight:700; color:var(--sv-purple);
    text-transform:uppercase; letter-spacing:1.5px;
    margin-bottom:16px;
}
.sv-section-title {
    font-size:clamp(2rem,4vw,3rem);
    font-weight:900; color:var(--sv-text);
    line-height:1.1; letter-spacing:-1.5px;
    margin:0 0 16px;
}
.sv-section-sub {
    font-size:17px; color:var(--sv-text-2);
    font-weight:600; line-height:1.65;
    max-width:640px;
}

/* ════════════════════════════
   1. HERO
   ════════════════════════════ */
.sv-hero {
    padding:120px 0 100px;
    text-align:center;
    background:#fff;
    position:relative;
    overflow:hidden;
}
.sv-hero::before {
    content:'';
    position:absolute; inset:0;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,.07) 0%, transparent 70%);
    pointer-events:none;
}
.sv-hero-tag {
    display:inline-block;
    font-size:14px; font-weight:700;
    color:var(--sv-purple);
    letter-spacing:1px;
    margin-bottom:20px;
    animation: sv-fadeUp .6s ease forwards;
}
.sv-hero-h1 {
    font-size:clamp(2.6rem,6vw,4.2rem);
    font-weight:900; color:var(--sv-text);
    line-height:1.08; letter-spacing:-2px;
    max-width:800px; margin:0 auto 24px;
    animation: sv-fadeUp .7s .1s ease both;
}
.sv-hero-sub {
    font-size:18px; color:var(--sv-text-2);
    font-weight:500; line-height:1.7;
    max-width:640px; margin:0 auto 40px;
    animation: sv-fadeUp .7s .2s ease both;
}
.sv-hero-btns {
    display:flex; gap:14px; justify-content:center; flex-wrap:wrap;
    animation: sv-fadeUp .7s .3s ease both;
}
.sv-btn-primary {
    display:inline-flex; align-items:center; gap:8px;
    padding:14px 28px; border-radius:100px;
    background:var(--sv-purple); color:#fff;
    font-size:15px; font-weight:700;
    text-decoration:none; border:none; cursor:pointer;
    transition:all .25s;
}
.sv-btn-primary:hover { background:#6d28d9; transform:translateY(-2px); box-shadow:0 8px 24px rgba(124,58,237,.35); }
.sv-btn-secondary {
    display:inline-flex; align-items:center; gap:8px;
    padding:14px 28px; border-radius:100px;
    background:transparent; color:var(--sv-text);
    font-size:15px; font-weight:700;
    text-decoration:none; border:2px solid var(--sv-border);
    transition:all .25s;
}
.sv-btn-secondary:hover { border-color:var(--sv-purple); color:var(--sv-purple); transform:translateY(-2px); }

/* ════════════════════════════
   2. WHAT WE OFFER
   ════════════════════════════ */
.sv-offer { background:#fff; }

/* Default 3-col grid for 1-3 cards */
.sv-offer-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:24px;
    margin-top:60px;
}

/* 4-card: left tall | middle 2 stacked | right tall */
.sv-offer-grid-4 {
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    grid-template-rows:auto auto;
    gap:24px;
    margin-top:60px;
}
.sv-offer-grid-4 .sv-offer-card:nth-child(1) { grid-row: 1 / 3; }  /* left tall */
.sv-offer-grid-4 .sv-offer-card:nth-child(2) { grid-column:2; grid-row:1; }
.sv-offer-grid-4 .sv-offer-card:nth-child(3) { grid-column:3; grid-row:1 / 3; } /* right tall */
.sv-offer-grid-4 .sv-offer-card:nth-child(4) { grid-column:2; grid-row:2; }

.sv-offer-card {
    border:1px solid var(--sv-border);
    border-radius:var(--sv-radius);
    padding:32px 28px;
    background:#fff;
    transition:all .35s;
    position:relative; overflow:hidden;
    display:flex; flex-direction:column;
}
.sv-offer-card::after {
    content:''; position:absolute;
    inset:0; border-radius:inherit;
    background:linear-gradient(135deg,rgba(124,58,237,.04),transparent);
    opacity:0; transition:opacity .3s;
}
.sv-offer-card:hover { box-shadow:0 20px 60px rgba(0,0,0,.09); transform:translateY(-4px); border-color:rgba(124,58,237,.25); }
.sv-offer-card:hover::after { opacity:1; }
.sv-offer-card-title { font-size:17px; font-weight:800; color:var(--sv-text); margin-bottom:10px; }
.sv-offer-card-desc  { font-size:14px; color:var(--sv-text-2); line-height:1.65; margin-bottom:24px; }
.sv-offer-visual {
    border-radius:12px;
    background:var(--sv-bg-2);
    flex:1;
    min-height:160px;
    display:flex; align-items:flex-end; justify-content:center;
    border:1px solid var(--sv-border);
    overflow:hidden;
    transition:transform .4s;
    position:relative;
}
.sv-offer-card:hover .sv-offer-visual { transform:scale(1.01); }

/* ── uploaded image ── */
.sv-offer-visual img { width:100%; height:100%; object-fit:cover; display:block; }

/* ── Phone mockup visual ── */
.sv-vis-phone {
    width:100%; height:100%; min-height:220px;
    display:flex; align-items:flex-end; justify-content:center;
    padding-bottom:0;
    background:linear-gradient(160deg,#f0f4ff 0%,#e8eeff 100%);
}
.sv-vis-phone-device {
    width:170px;
    background:#1a1a2e;
    border-radius:20px 20px 0 0;
    padding:16px 14px 0;
    box-shadow:0 -8px 40px rgba(124,58,237,.2);
}
.sv-vis-phone-topbar {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:12px;
}
.sv-vis-phone-topbar span { font-size:9px; color:#6b7280; font-weight:600; }
.sv-vis-phone-check {
    display:flex; align-items:center; gap:6px;
    background:#252545; border-radius:6px;
    padding:6px 8px; margin-bottom:8px;
}
.sv-vis-phone-check-dot { width:14px; height:14px; border-radius:50%; background:#22c55e; flex-shrink:0; }
.sv-vis-phone-check-text { font-size:8px; color:#fff; font-weight:600; }
.sv-vis-phone-row { display:flex; align-items:center; gap:6px; margin-bottom:7px; }
.sv-vis-phone-avatar { width:18px; height:18px; border-radius:50%; background:#7c3aed; flex-shrink:0; }
.sv-vis-phone-lines { flex:1; display:flex; flex-direction:column; gap:3px; }
.sv-vis-phone-line { height:5px; border-radius:2px; background:#2d2d4e; }
.sv-vis-phone-badges { display:flex; gap:4px; margin-bottom:10px; }
.sv-vis-phone-badge-ip { padding:2px 7px; border-radius:4px; background:rgba(124,58,237,.3); color:#a78bfa; font-size:8px; font-weight:700; }
.sv-vis-phone-badge-lw { padding:2px 7px; border-radius:4px; background:rgba(34,197,94,.3); color:#4ade80; font-size:8px; font-weight:700; }
.sv-vis-phone-desc { background:#252545; border-radius:6px; padding:7px 8px; margin-bottom:0; }
.sv-vis-phone-desc-line { height:5px; border-radius:2px; background:#3d3d5e; margin-bottom:3px; }
.sv-vis-phone-desc-line:last-child { width:70%; margin-bottom:0; }

/* ── Performance bars visual ── */
.sv-vis-perf {
    width:100%; padding:20px 22px;
    background:#fff; min-height:160px;
    display:flex; flex-direction:column; justify-content:center;
    align-items:stretch;
}
.sv-vis-perf-header { display:flex; align-items:baseline; justify-content:space-between; margin-bottom:14px; }
.sv-vis-perf-num { font-size:26px; font-weight:900; color:var(--sv-purple); line-height:1; }
.sv-vis-perf-num sup { font-size:13px; font-weight:500; color:var(--sv-text-3); }
.sv-vis-perf-badge { padding:3px 8px; border-radius:20px; background:#dcfce7; color:#16a34a; font-size:11px; font-weight:700; }
.sv-vis-bars { display:flex; gap:4px; align-items:flex-end; height:56px; }
.sv-vis-bar { flex:1; border-radius:3px 3px 0 0; background:linear-gradient(to top,var(--sv-purple),#a78bfa); }

/* ── Score circles visual ── */
.sv-vis-scores {
    width:100%; padding:20px 16px;
    display:grid; grid-template-columns:1fr 1fr;
    gap:16px 12px; background:#fff;
    min-height:220px; align-content:center;
}
.sv-vis-circle-wrap { display:flex; flex-direction:column; align-items:center; gap:8px; }
.sv-vis-circle {
    width:72px; height:72px; border-radius:50%;
    border:5px solid #22c55e;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:900; color:var(--sv-text);
    background:#fff;
}
.sv-vis-circle-label { font-size:11px; color:var(--sv-text-2); font-weight:600; text-align:center; line-height:1.3; }

/* ── Security icons visual ── */
.sv-vis-security {
    width:100%; padding:18px 20px;
    background:#fff; min-height:100px;
    display:flex; flex-direction:column; justify-content:center;
}
.sv-vis-sec-icons { display:flex; align-items:center; gap:0; margin-top:14px; }
.sv-vis-sec-icon {
    width:46px; height:46px; border-radius:50%;
    background:var(--sv-purple); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; flex-shrink:0;
    box-shadow:0 4px 12px rgba(124,58,237,.3);
}
.sv-vis-sec-icon.mid { background:#5b21b6; width:52px; height:52px; font-size:22px; }
.sv-vis-sec-line { flex:1; height:2px; background:var(--sv-border); }

/* ════════════════════════════
   3. TECH STACK
   ════════════════════════════ */
.sv-tech { background:var(--sv-bg-2); border-top:1px solid var(--sv-border); border-bottom:1px solid var(--sv-border); }
.sv-tech-category { margin-bottom:52px; }
.sv-tech-category:last-child { margin-bottom:0; }
.sv-tech-cat-label {
    font-size:11px; font-weight:800;
    color:var(--sv-text-3);
    letter-spacing:2px; text-transform:uppercase;
    margin-bottom:10px;
}
.sv-tech-cat-line {
    height:2px; background:var(--sv-border);
    position:relative; margin-bottom:22px;
}
.sv-tech-cat-line::before {
    content:''; position:absolute;
    left:0; top:0; height:100%;
    width:32px; background:var(--sv-purple);
}
.sv-tech-pills {
    display:flex; flex-wrap:wrap; gap:10px;
}
.sv-tech-pill {
    display:inline-flex; align-items:center; gap:8px;
    padding:9px 16px;
    border:1.5px solid var(--sv-border);
    border-radius:8px;
    background:#fff;
    font-size:13.5px; font-weight:600; color:var(--sv-text);
    cursor:default;
    transition:all .2s;
}
.sv-tech-pill:hover { border-color:var(--sv-purple); color:var(--sv-purple); background:var(--sv-purple-light); transform:translateY(-1px); }
.sv-tech-pill img, .sv-tech-pill-icon { width:18px; height:18px; object-fit:contain; }

/* ════════════════════════════
   4. WORK PROCESS
   ════════════════════════════ */
.sv-process { background:#fff; }
.sv-process-step {
    display:grid;
    grid-template-columns:280px 1fr;
    gap:80px;
    align-items:start;
    padding:64px 0;
    border-bottom:1px solid var(--sv-border);
    position:relative;
}
.sv-process-step:last-child { border-bottom:none; }
.sv-process-left { position:sticky; top:120px; }
.sv-process-num {
    width:44px; height:44px;
    border-radius:50%;
    border:1.5px solid var(--sv-border);
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:700; color:var(--sv-text-2);
    margin-bottom:20px;
}
.sv-process-title {
    font-size:clamp(1.8rem,3vw,2.5rem);
    font-weight:900; color:var(--sv-text);
    line-height:1.1; letter-spacing:-1px;
}
.sv-process-connector {
    position:absolute; left:22px; top:108px;
    width:1.5px; height:calc(100% - 108px);
    background:linear-gradient(to bottom,var(--sv-purple),transparent);
    opacity:.25;
}
.sv-process-right {
    border:1px solid var(--sv-border);
    border-radius:var(--sv-radius);
    padding:32px 36px;
    background:#fff;
}
.sv-process-desc {
    font-size:16px; color:var(--sv-text-2);
    line-height:1.75; margin-bottom:28px;
}
.sv-process-features {
    display:grid; grid-template-columns:1fr 1fr;
    gap:16px 24px;
}
.sv-process-feat {
    display:flex; align-items:center; gap:12px;
    font-size:14px; color:var(--sv-text-2); font-weight:500;
}
.sv-process-feat-icon {
    font-size:16px; flex-shrink:0;
    width:28px; height:28px;
    display:flex; align-items:center; justify-content:center;
}

/* ════════════════════════════
   5. FEATURED WORK
   ════════════════════════════ */
.sv-work { background:#fff; border-top:1px solid var(--sv-border); }
.sv-work-project {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:80px;
    align-items:center;
    padding:80px 0;
    border-bottom:1px solid var(--sv-border);
}
.sv-work-project:last-child { border-bottom:none; }
.sv-work-project.sv-reverse { direction:rtl; }
.sv-work-project.sv-reverse > * { direction:ltr; }
.sv-work-img-wrap {
    border-radius:var(--sv-radius);
    overflow:hidden;
    position:relative;
    background:var(--sv-bg-2);
    min-height:340px;
}
.sv-work-img-wrap img {
    width:100%; height:100%;
    object-fit:cover; display:block;
    transition:transform .6s ease;
}
.sv-work-project:hover .sv-work-img-wrap img { transform:scale(1.04); }
.sv-work-img-placeholder {
    width:100%; min-height:340px;
    background:linear-gradient(135deg,var(--sv-purple-light),var(--sv-bg-2));
    display:flex; align-items:center; justify-content:center;
    font-size:4rem;
}
.sv-work-title { font-size:1.7rem; font-weight:900; color:var(--sv-text); margin-bottom:12px; letter-spacing:-.5px; }
.sv-work-desc  { font-size:15px; color:var(--sv-text-2); line-height:1.75; margin-bottom:24px; }
.sv-work-meta  { margin-bottom:20px; }
.sv-work-meta-title { font-size:13px; font-weight:800; color:var(--sv-text); margin-bottom:10px; }
.sv-work-meta-grid {
    display:grid; grid-template-columns:1fr 1fr;
    gap:6px 16px;
}
.sv-work-meta-item {
    display:flex; align-items:center; gap:6px;
    font-size:13px; color:var(--sv-text-2);
}
.sv-work-meta-item b { color:var(--sv-text); font-weight:700; }
.sv-work-status {
    display:inline-flex; align-items:center; gap:6px;
    font-size:13px; font-weight:600;
}
.sv-work-status-dot { width:8px; height:8px; border-radius:50%; background:#22c55e; }
.sv-work-features-title { font-size:13px; font-weight:800; color:var(--sv-text); margin:16px 0 10px; }
.sv-work-features { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:24px; }
.sv-work-feature-tag {
    padding:6px 14px;
    border:1.5px solid var(--sv-border);
    border-radius:6px;
    font-size:12.5px; color:var(--sv-text-2); font-weight:500;
    transition:all .2s;
}
.sv-work-feature-tag:hover { border-color:var(--sv-purple); color:var(--sv-purple); }
.sv-work-btns { display:flex; gap:10px; flex-wrap:wrap; }
.sv-btn-live {
    display:inline-flex; align-items:center; gap:7px;
    padding:11px 22px; border-radius:8px;
    background:var(--sv-purple); color:#fff;
    font-size:14px; font-weight:700;
    text-decoration:none;
    transition:all .25s;
}
.sv-btn-live:hover { background:#6d28d9; transform:translateY(-2px); }
.sv-btn-case {
    display:inline-flex; align-items:center; gap:7px;
    padding:11px 22px; border-radius:8px;
    background:transparent; color:var(--sv-text);
    font-size:14px; font-weight:700;
    text-decoration:none; border:1.5px solid var(--sv-border);
    transition:all .25s;
}
.sv-btn-case:hover { border-color:var(--sv-text); transform:translateY(-2px); }

/* ════════════════════════════
   6. CTA BAND
   ════════════════════════════ */
.sv-cta {
    padding:90px 0;
    background:var(--sv-purple);
    text-align:center;
}
.sv-cta h2 { font-size:clamp(1.8rem,3.5vw,2.6rem); font-weight:900; color:#fff; letter-spacing:-1px; margin-bottom:12px; }
.sv-cta p  { font-size:17px; color:rgba(255,255,255,.7); margin-bottom:36px; }
.sv-cta-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
.sv-btn-white {
    display:inline-flex; align-items:center; gap:8px;
    padding:14px 28px; border-radius:100px;
    background:#fff; color:var(--sv-purple);
    font-size:15px; font-weight:700;
    text-decoration:none;
    transition:all .25s;
}
.sv-btn-white:hover { background:#f3f0ff; transform:translateY(-2px); }
.sv-btn-white-out {
    display:inline-flex; align-items:center; gap:8px;
    padding:14px 28px; border-radius:100px;
    background:transparent; color:#fff;
    font-size:15px; font-weight:700;
    text-decoration:none; border:2px solid rgba(255,255,255,.4);
    transition:all .25s;
}
.sv-btn-white-out:hover { border-color:#fff; transform:translateY(-2px); }

/* ── Responsive ── */
@media(max-width:1024px){
    .sv-offer-grid { grid-template-columns:1fr 1fr; }
    .sv-offer-grid-4 { grid-template-columns:1fr 1fr; }
    .sv-offer-grid-4 .sv-offer-card:nth-child(1),
    .sv-offer-grid-4 .sv-offer-card:nth-child(3) { grid-row: auto; }
    .sv-offer-grid-4 .sv-offer-card:nth-child(2),
    .sv-offer-grid-4 .sv-offer-card:nth-child(4) { grid-column: auto; grid-row: auto; }
    .sv-process-step { grid-template-columns:200px 1fr; gap:40px; }
    .sv-work-project { gap:48px; }
}
@media(max-width:768px){
    .sv-container { padding:0 20px; }
    .sv-section { padding:64px 0; }
    .sv-hero { padding:80px 0 64px; }
    .sv-offer-grid,
    .sv-offer-grid-4 { grid-template-columns:1fr; }
    .sv-offer-grid-4 .sv-offer-card { grid-column:auto !important; grid-row:auto !important; }
    .sv-process-step { grid-template-columns:1fr; gap:24px; }
    .sv-process-left { position:static; }
    .sv-process-connector { display:none; }
    .sv-process-features { grid-template-columns:1fr; }
    .sv-work-project,
    .sv-work-project.sv-reverse { grid-template-columns:1fr; direction:ltr; }
    .sv-work-meta-grid { grid-template-columns:1fr; }
}
</style>

{{-- ═══════════════════════════════════════════
     SECTION 1 · HERO
     ═══════════════════════════════════════════ --}}
<section class="sv-hero">
    <div class="sv-container">
        @if($service->tag_label)
        <div class="sv-hero-tag">{{ $service->tag_label }}</div>
        @endif
        <h1 class="sv-hero-h1">{{ $service->hero_headline ?? $service->title }}</h1>
        @if($service->hero_subtitle)
        <p class="sv-hero-sub">{{ $service->hero_subtitle }}</p>
        @endif
        <div class="sv-hero-btns">
            <a href="{{ route('contact') }}" class="sv-btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                {{ $service->hero_cta_primary ?? 'Get in touch' }}
            </a>
            <a href="#offer" class="sv-btn-secondary">
                {{ $service->hero_cta_secondary ?? 'Learn more' }}
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($service->banner_image)
        <div style="margin-top:56px;border-radius:20px;overflow:hidden;border:1px solid var(--sv-border);box-shadow:0 24px 80px rgba(0,0,0,.10);animation:sv-fadeUp .8s .4s ease both;">
            <img src="{{ asset($service->banner_image) }}"
                 alt="{{ $service->title }}"
                 style="width:100%;max-height:500px;object-fit:cover;display:block;">
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 2 · WHAT WE OFFER
     ═══════════════════════════════════════════ --}}
@if($service->offer_title || ($service->offer_features && count($service->offer_features)))
<section class="sv-offer sv-section" id="offer">
    <div class="sv-container">
        <div class="sv-reveal">
            @if($service->offer_tag)
            <div class="sv-tag">{{ $service->offer_tag }}</div>
            @endif
            @if($service->offer_title)
            <h2 class="sv-section-title">{{ $service->offer_title }}</h2>
            @endif
            @if($service->offer_subtitle)
            <p class="sv-section-sub">{{ $service->offer_subtitle }}</p>
            @endif
        </div>

        @if($service->offer_features && count($service->offer_features))
        @php $featCount = count($service->offer_features); @endphp
        <div class="{{ $featCount === 4 ? 'sv-offer-grid-4' : 'sv-offer-grid' }}">
            @foreach($service->offer_features as $i => $feat)
            <div class="sv-offer-card sv-reveal-s" style="animation-delay:{{ $i * 0.1 }}s">
                <div class="sv-offer-card-title">{{ $feat['title'] ?? '' }}</div>
                <div class="sv-offer-card-desc">{{ $feat['description'] ?? '' }}</div>
                <div class="sv-offer-visual">
                    @if(!empty($feat['image']))
                        <img src="{{ asset($feat['image']) }}" alt="{{ $feat['title'] }}">
                    @elseif($i % 4 === 0)
                        {{-- Phone mockup --}}
                        <div class="sv-vis-phone">
                            <div class="sv-vis-phone-device">
                                <div class="sv-vis-phone-topbar">
                                    <span>✓ Mark Completed</span>
                                    <span>✕</span>
                                </div>
                                <div class="sv-vis-phone-check">
                                    <div class="sv-vis-phone-check-dot"></div>
                                    <div class="sv-vis-phone-check-text">Schedule Me An Appointment</div>
                                </div>
                                <div class="sv-vis-phone-row">
                                    <div class="sv-vis-phone-avatar"></div>
                                    <div class="sv-vis-phone-lines">
                                        <div class="sv-vis-phone-line" style="width:80%"></div>
                                        <div class="sv-vis-phone-line" style="width:55%"></div>
                                    </div>
                                </div>
                                <div class="sv-vis-phone-badges">
                                    <span class="sv-vis-phone-badge-ip">In Progress</span>
                                    <span class="sv-vis-phone-badge-lw">Low</span>
                                </div>
                                <div class="sv-vis-phone-desc">
                                    <div class="sv-vis-phone-desc-line"></div>
                                    <div class="sv-vis-phone-desc-line"></div>
                                </div>
                            </div>
                        </div>
                    @elseif($i % 4 === 1)
                        {{-- Performance bars --}}
                        <div class="sv-vis-perf">
                            <div class="sv-vis-perf-header">
                                <div class="sv-vis-perf-num">1.04<sup>s</sup></div>
                                <span class="sv-vis-perf-badge">-22%</span>
                            </div>
                            <div class="sv-vis-bars">
                                @foreach([100,92,96,88,100,82,95,90,72,62,56,50,44,38,32] as $h)
                                <div class="sv-vis-bar" style="height:{{ $h }}%;opacity:{{ $h > 65 ? 1 : max(0.25, $h/100) }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($i % 4 === 2)
                        {{-- Score circles --}}
                        <div class="sv-vis-scores">
                            @foreach([['Performance','100%'],['Accessibility','100%'],['Best Practices','100%'],['SEO','100%']] as $sc)
                            <div class="sv-vis-circle-wrap">
                                <div class="sv-vis-circle">{{ $sc[1] }}</div>
                                <div class="sv-vis-circle-label">{{ $sc[0] }}</div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Security icons --}}
                        <div class="sv-vis-security">
                            <div class="sv-vis-sec-icons">
                                <div class="sv-vis-sec-icon">🌐</div>
                                <div class="sv-vis-sec-line"></div>
                                <div class="sv-vis-sec-icon mid">🛡️</div>
                                <div class="sv-vis-sec-line"></div>
                                <div class="sv-vis-sec-icon">🔐</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 3 · TECH STACK
     ═══════════════════════════════════════════ --}}
@if($service->tech_categories && count($service->tech_categories))
<section class="sv-tech sv-section">
    <div class="sv-container">
        <div class="sv-reveal" style="margin-bottom:60px">
            @if($service->techstack_tag)
            <div class="sv-tag">{{ $service->techstack_tag }}</div>
            @endif
            @if($service->techstack_title)
            <h2 class="sv-section-title">{{ $service->techstack_title }}</h2>
            @endif
            @if($service->techstack_subtitle)
            <p class="sv-section-sub">{{ $service->techstack_subtitle }}</p>
            @endif
        </div>

        @foreach($service->tech_categories as $i => $cat)
        <div class="sv-tech-category sv-reveal" style="animation-delay:{{ $i * 0.08 }}s">
            <div class="sv-tech-cat-label">{{ $cat['name'] ?? '' }}</div>
            <div class="sv-tech-cat-line"></div>
            <div class="sv-tech-pills">
                @foreach(($cat['items'] ?? []) as $item)
                <div class="sv-tech-pill">
                    @if(!empty($item['icon_url']))
                    <img src="{{ $item['icon_url'] }}" alt="{{ $item['name'] }}" class="sv-tech-pill-icon">
                    @elseif(!empty($item['emoji']))
                    <span>{{ $item['emoji'] }}</span>
                    @endif
                    {{ $item['name'] }}
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 4 · WORK PROCESS
     ═══════════════════════════════════════════ --}}
@if($service->process_steps && count($service->process_steps))
<section class="sv-process sv-section">
    <div class="sv-container">
        <div class="sv-reveal" style="margin-bottom:16px">
            @if($service->process_tag)
            <div class="sv-tag">{{ $service->process_tag }}</div>
            @endif
            @if($service->process_title)
            <h2 class="sv-section-title">{{ $service->process_title }}</h2>
            @endif
            @if($service->process_subtitle)
            <p class="sv-section-sub">{{ $service->process_subtitle }}</p>
            @endif
        </div>

        @foreach($service->process_steps as $i => $step)
        <div class="sv-process-step">
            <div class="sv-process-left sv-reveal-l" style="animation-delay:{{ $i * 0.1 }}s">
                <div class="sv-process-num">{{ $i + 1 }}</div>
                <div class="sv-process-title">{{ $step['title'] ?? '' }}</div>
                @if($i < count($service->process_steps) - 1)
                <div class="sv-process-connector"></div>
                @endif
            </div>
            <div class="sv-process-right sv-reveal-r" style="animation-delay:{{ $i * 0.1 + 0.1 }}s">
                @if(!empty($step['description']))
                <div class="sv-process-desc">{{ $step['description'] }}</div>
                @endif
                @if(!empty($step['features']) && count($step['features']))
                <div class="sv-process-features">
                    @foreach($step['features'] as $feat)
                    <div class="sv-process-feat">
                        <span class="sv-process-feat-icon">{{ $feat['icon'] ?? '◆' }}</span>
                        <span>{{ $feat['label'] ?? '' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 5 · FEATURED WORK
     ═══════════════════════════════════════════ --}}
@if($service->featured_projects && count($service->featured_projects))
<section class="sv-work sv-section">
    <div class="sv-container">
        <div class="sv-reveal" style="margin-bottom:16px">
            @if($service->work_tag)
            <div class="sv-tag">{{ $service->work_tag }}</div>
            @endif
            @if($service->work_title)
            <h2 class="sv-section-title">{{ $service->work_title }}</h2>
            @endif
            @if($service->work_subtitle)
            <p class="sv-section-sub">{{ $service->work_subtitle }}</p>
            @endif
        </div>

        @foreach($service->featured_projects as $i => $proj)
        <div class="sv-work-project {{ $i % 2 == 1 ? 'sv-reverse' : '' }}">
            {{-- Image --}}
            <div class="sv-work-img-wrap sv-reveal-s" style="animation-delay:{{ $i * 0.12 }}s">
                @if(!empty($proj['image']))
                    <img src="{{ asset($proj['image']) }}" alt="{{ $proj['title'] ?? '' }}">
                @else
                    <div class="sv-work-img-placeholder">🎨</div>
                @endif
            </div>

            {{-- Content --}}
            <div class="sv-reveal" style="animation-delay:{{ $i * 0.12 + 0.1 }}s">
                <h3 class="sv-work-title">{{ $proj['title'] ?? '' }}</h3>
                @if(!empty($proj['description']))
                <p class="sv-work-desc">{{ $proj['description'] }}</p>
                @endif

                @if(!empty($proj['client']) || !empty($proj['role']) || !empty($proj['year']))
                <div class="sv-work-meta">
                    <div class="sv-work-meta-title">Project Details:</div>
                    <div class="sv-work-meta-grid">
                        @if(!empty($proj['client']))
                        <div class="sv-work-meta-item">🌐 Client: <b>{{ $proj['client'] }}</b></div>
                        @endif
                        @if(!empty($proj['role']))
                        <div class="sv-work-meta-item">✦ Role: <b>{{ $proj['role'] }}</b></div>
                        @endif
                        @if(!empty($proj['year']))
                        <div class="sv-work-meta-item">📅 Year: <b>{{ $proj['year'] }}</b></div>
                        @endif
                        @if(!empty($proj['duration']))
                        <div class="sv-work-meta-item">⏱ Duration: <b>{{ $proj['duration'] }}</b></div>
                        @endif
                        @if(!empty($proj['team']))
                        <div class="sv-work-meta-item">👥 Team: <b>{{ $proj['team'] }}</b></div>
                        @endif
                    </div>
                    @if(!empty($proj['status']))
                    <div class="sv-work-status" style="margin-top:10px">
                        <span class="sv-work-status-dot"></span> Status: <b>{{ $proj['status'] }}</b>
                    </div>
                    @endif
                </div>
                @endif

                @if(!empty($proj['features']) && count($proj['features']))
                <div class="sv-work-features-title">Key Features:</div>
                <div class="sv-work-features">
                    @foreach($proj['features'] as $feat)
                    <span class="sv-work-feature-tag">{{ $feat }}</span>
                    @endforeach
                </div>
                @endif

                <div class="sv-work-btns">
                    @if(!empty($proj['live_url']))
                    <a href="{{ $proj['live_url'] }}" target="_blank" class="sv-btn-live">
                        View Live
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </a>
                    @endif
                    @if(!empty($proj['case_study_url']))
                    <a href="{{ $proj['case_study_url'] }}" class="sv-btn-case">
                        Case Study →
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     CTA BAND
     ═══════════════════════════════════════════ --}}
<section class="sv-cta sv-reveal">
    <div class="sv-container">
        <h2>{{ $service->cta_title ?? 'Ready to Start Your Project?' }}</h2>
        <p>{{ $service->cta_subtitle ?? "Let's build something extraordinary together." }}</p>
        <div class="sv-cta-btns">
            <a href="{{ route('contact') }}" class="sv-btn-white">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                Get Started
            </a>
            <a href="{{ route('services') }}" class="sv-btn-white-out">All Services →</a>
        </div>
    </div>
</section>

<script>
(function(){
    // Intersection Observer for scroll animations
    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                e.target.classList.add('sv-vis');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.sv-reveal, .sv-reveal-l, .sv-reveal-r, .sv-reveal-s').forEach(function(el){
        io.observe(el);
    });

    // Smooth scroll for hero "Learn more"
    document.querySelectorAll('a[href^="#"]').forEach(function(a){
        a.addEventListener('click', function(e){
            var t = document.querySelector(this.getAttribute('href'));
            if(t){ e.preventDefault(); t.scrollIntoView({behavior:'smooth', block:'start'}); }
        });
    });
})();
</script>
@endsection