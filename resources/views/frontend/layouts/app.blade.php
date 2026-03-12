<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', ($settings['site_name'] ?? 'SK Artistic') . ' — Beyond Ordinary')</title>
<meta name="description" content="@yield('meta_description', $settings['site_tagline'] ?? 'Full-cycle digital agency building the future.')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════
   TOKENS — Light Theme
   #EAE6FF  lavender surface
   #FFFFFF  white card
   #2A2263  deep navy brand
═══════════════════════════════════════════════════════════ */
:root {
  --void:    #f5f3ff;
  --deep:    #EAE6FF;
  --panel:   #FFFFFF;
  --glass:   rgba(42,34,99,.04);
  --rim:     rgba(42,34,99,.1);
  --rim2:    rgba(42,34,99,.18);
  --text:    #1a1540;
  --muted:   rgba(42,34,99,.6);
  --dim:     rgba(42,34,99,.38);
  --brand:   #2A2263;
  --brand2:  #3d30a3;
  --accent:  #6c5ce7;
  --light:   #EAE6FF;
  --cyan:    #2A2263;
  --violet:  #6c5ce7;
  --magenta: #a78bfa;
  --glow-c:  0 4px 24px rgba(42,34,99,.18), 0 0 60px rgba(42,34,99,.06);
  --glow-v:  0 4px 24px rgba(108,92,231,.22), 0 0 60px rgba(108,92,231,.08);
  --font-d:  'Bebas Neue', sans-serif;
  --font-ui: 'Syne', sans-serif;
  --font-b:  'Space Grotesk', sans-serif;
  --r:       6px;
  --shadow:  0 2px 24px rgba(42,34,99,.08);
  --shadow-lg: 0 8px 48px rgba(42,34,99,.12);
}
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0 }
html { scroll-behavior:smooth; overflow-x:hidden }
body { font-family:var(--font-b); color:var(--text); background:var(--void); overflow-x:hidden; cursor:none }
a { text-decoration:none; color:inherit }
img { max-width:100%; display:block }
button { font-family:inherit }

/* ═══════════════════════════════════════════════════════════
   CURSOR
═══════════════════════════════════════════════════════════ */
#cur-dot {
  position:fixed; width:7px; height:7px; background:var(--brand);
  border-radius:50%; pointer-events:none; z-index:99999;
  transform:translate(-50%,-50%);
  box-shadow:0 0 10px rgba(42,34,99,.4);
  transition:width .2s, height .2s, background .2s, box-shadow .2s;
}
#cur-ring {
  position:fixed; width:34px; height:34px;
  border:1.5px solid rgba(42,34,99,.3); border-radius:50%;
  pointer-events:none; z-index:99998;
  transform:translate(-50%,-50%); transition:all .06s linear;
}
#cur-trail {
  position:fixed; width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(42,34,99,.05) 0%,transparent 70%);
  pointer-events:none; z-index:99997;
  transform:translate(-50%,-50%); transition:all .18s ease;
}
body.hov #cur-dot  { width:13px; height:13px; background:var(--accent); box-shadow:0 0 14px rgba(108,92,231,.4) }
body.hov #cur-ring { width:52px; height:52px; border-color:rgba(108,92,231,.4) }

/* ═══════════════════════════════════════════════════════════
   PROGRESS BAR
═══════════════════════════════════════════════════════════ */
#spb {
  position:fixed; top:0; left:0; height:3px; width:0%; z-index:9999;
  background:linear-gradient(90deg,var(--brand),var(--accent),var(--magenta));
  box-shadow:0 0 8px rgba(42,34,99,.3); transition:width .05s linear;
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
  background: rgba(255,255,255,.88);
  backdrop-filter: blur(28px) saturate(180%);
  -webkit-backdrop-filter: blur(28px) saturate(180%);
  border: 1px solid rgba(42,34,99,.1);
  box-shadow: 0 4px 32px rgba(42,34,99,.1), 0 1px 0 rgba(255,255,255,.9) inset;
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
  background: rgba(255,255,255,.96);
  box-shadow: 0 8px 40px rgba(42,34,99,.14), 0 1px 0 rgba(255,255,255,.9) inset;
  border-color: rgba(42,34,99,.14);
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
  border: 1.5px solid rgba(42,34,99,.2);
  display: flex; align-items: center; justify-content: center;
  position: relative; flex-shrink: 0;
  animation: spin 10s linear infinite;
  border-radius: 6px;
  background: rgba(42,34,99,.04);
}
.nav-logo-glyph::before {
  content: '';
  position: absolute; inset: 4px;
  border: 1px solid rgba(108,92,231,.25);
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
  color: rgba(42,34,99,.6);
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
.nav-center a:hover { color: var(--brand); background: rgba(42,34,99,.06) }
.nav-center a.active { color: var(--brand); background: rgba(42,34,99,.08) }
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
  background: #FFFFFF;
  border: 1px solid rgba(42,34,99,.1);
  border-radius: 16px;
  padding: 8px;
  opacity: 0;
  pointer-events: none;
  transition: opacity .2s, transform .25s cubic-bezier(.34,1.56,.64,1);
  box-shadow: 0 16px 48px rgba(42,34,99,.14);
  z-index: 100;
}
.nav-dropdown::before {
  content: '';
  position: absolute;
  top: -5px; left: 50%;
  width: 10px; height: 10px;
  background: #FFFFFF;
  border-left: 1px solid rgba(42,34,99,.1);
  border-top: 1px solid rgba(42,34,99,.1);
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
.nav-dropdown a:hover { color: var(--brand); background: rgba(42,34,99,.05) }
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
  box-shadow: 0 4px 18px rgba(42,34,99,.3) !important;
  position: relative; overflow: hidden;
  cursor: none !important;
}
.nav-cta::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.1), transparent);
  border-radius: inherit;
}
.nav-cta:hover {
  background: var(--brand2) !important;
  box-shadow: 0 6px 28px rgba(42,34,99,.38) !important;
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
.ham:hover { background: rgba(42,34,99,.06) }
.ham span {
  display: block; width: 20px; height: 1.5px;
  background: var(--brand); border-radius: 2px;
  transition: transform .3s, opacity .3s;
}
.ham.open span:nth-child(1) { transform: translateY(5.5px) rotate(45deg) }
.ham.open span:nth-child(2) { opacity: 0; transform: scaleX(0) }
.ham.open span:nth-child(3) { transform: translateY(-5.5px) rotate(-45deg) }

/* Mobile menu */
@media (max-width: 860px) {
  .nav { width: calc(100% - 32px); padding: 0 8px 0 16px; top: 16px }
  .nav-center {
    display: none; position: fixed;
    top: 92px; left: 50%; transform: translateX(-50%);
    width: calc(100% - 32px); max-width: 500px;
    flex-direction: column; gap: 2px;
    background: rgba(255,255,255,.98); backdrop-filter: blur(32px);
    border: 1px solid rgba(42,34,99,.1);
    border-radius: 20px; padding: 12px;
    box-shadow: 0 24px 60px rgba(42,34,99,.14);
    animation: none;
  }
  .nav-center.open { display: flex }
  .nav-center a { padding: 12px 16px; border-radius: 12px; font-size: 15px }
  .nav-center a.active::after { display: none }
  .nav-dropdown { position: static; transform: none; opacity: 1; pointer-events: all;
    background: rgba(42,34,99,.03); border: none; border-radius: 10px;
    margin-top: 2px; display: none; box-shadow: none }
  .nav-dropdown::before { display: none }
  .ham { display: flex }
}

/* ═══════════════════════════════════════════════════════════
   PAGE WRAPPER / CANVAS
═══════════════════════════════════════════════════════════ */
#bg-canvas { position:fixed; inset:0; z-index:0; opacity:.35; pointer-events:none }
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
  cursor:none; border:none; display:inline-flex; align-items:center; gap:12px;
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
  padding:16px 40px; border:1.5px solid rgba(42,34,99,.25);
  color:var(--brand); display:inline-flex; align-items:center; gap:10px;
  cursor:none; transition:all .3s; position:relative; overflow:hidden;
  background:transparent;
}
.btn-outline::before {
  content:''; position:absolute; inset:0;
  background:rgba(42,34,99,.04); opacity:0; transition:opacity .3s;
}
.btn-outline:hover { border-color:var(--brand); box-shadow:var(--glow-c) }
.btn-outline:hover::before { opacity:1 }

.btn-ghost {
  font-family:var(--font-ui); font-size:11px; font-weight:600;
  letter-spacing:2.5px; text-transform:uppercase;
  color:var(--muted); display:inline-flex; align-items:center; gap:10px;
  cursor:none; transition:color .25s;
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
  background:linear-gradient(135deg, var(--deep) 0%, #FFFFFF 100%);
  border-bottom:1px solid var(--rim);
}
.page-hero-grid {
  position:absolute; inset:0; pointer-events:none;
  background-image:
    linear-gradient(rgba(42,34,99,.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(42,34,99,.04) 1px,transparent 1px);
  background-size:80px 80px;
  mask-image:radial-gradient(ellipse 70% 80% at 50% 50%,black,transparent);
}
.page-hero-orb {
  position:absolute; right:-200px; top:50%; transform:translateY(-50%);
  width:600px; height:600px; border-radius:50%; pointer-events:none;
  background:radial-gradient(circle,rgba(108,92,231,.1) 0%,rgba(42,34,99,.04) 40%,transparent 70%);
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
  color:rgba(255,255,255,.55); transition:color .2s; cursor:none;
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
  background:linear-gradient(150deg, #FFFFFF 0%, var(--deep) 60%, #f0edff 100%);
}
.hero-grid {
  position:absolute; inset:0;
  background-image:
    linear-gradient(rgba(42,34,99,.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(42,34,99,.04) 1px,transparent 1px);
  background-size:80px 80px;
  mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black,transparent);
}
.hero-grid::before {
  content:''; position:absolute; inset:0;
  background-image:
    linear-gradient(rgba(108,92,231,.05) 1px,transparent 1px),
    linear-gradient(90deg,rgba(108,92,231,.05) 1px,transparent 1px);
  background-size:400px 400px;
}
.orbit-wrap {
  position:absolute; right:-200px; top:50%; transform:translateY(-50%);
  width:700px; height:700px; pointer-events:none;
}
.orbit { position:absolute; border-radius:50%; border:1px solid transparent }
.orbit-1 { inset:0;   border-color:rgba(42,34,99,.08); animation:orb 20s linear infinite }
.orbit-2 { inset:60px; border-color:rgba(108,92,231,.07); animation:orb 14s linear infinite reverse }
.orbit-3 { inset:130px; border-color:rgba(42,34,99,.05); animation:orb 9s linear infinite }
.orbit-4 { inset:200px; border-color:rgba(108,92,231,.04); animation:orb 6s linear infinite reverse }
.orbit-5 { inset:270px; border-color:rgba(42,34,99,.03); animation:orb 4s linear infinite }
.orbit-dot {
  position:absolute; width:6px; height:6px; border-radius:50%;
  top:calc(50% - 3px); left:calc(50% - 3px);
}
.orbit-dot-c { background:var(--brand); box-shadow:0 0 8px rgba(42,34,99,.4); animation:orbit-t1 20s linear infinite }
.orbit-dot-v { background:var(--accent); box-shadow:0 0 8px rgba(108,92,231,.5); animation:orbit-t2 14s linear infinite reverse }
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
  background:radial-gradient(circle,rgba(108,92,231,.12) 0%,rgba(42,34,99,.05) 40%,transparent 70%);
  filter:blur(60px); animation:orb-breathe 5s ease-in-out infinite;
}
.hero-inner { max-width:1100px; margin:0 auto; width:100%; position:relative; z-index:2 }
.hero-status {
  display:inline-flex; align-items:center; gap:10px;
  border:1.5px solid rgba(42,34,99,.15); background:rgba(42,34,99,.05);
  padding:8px 20px; margin-bottom:48px;
  font-family:var(--font-ui); font-size:10.5px; letter-spacing:3px; text-transform:uppercase;
  color:var(--brand); animation:fade-up .8s .1s both; border-radius:100px;
}
.status-dot {
  width:7px; height:7px; background:var(--brand); border-radius:50%;
  box-shadow:0 0 8px rgba(42,34,99,.4); animation:blink 1.5s ease-in-out infinite;
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
.hero-h .c2 { -webkit-text-stroke:1.5px rgba(42,34,99,.15); color:transparent }
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
  background:rgba(255,255,255,.85); border:1px solid rgba(42,34,99,.1);
  backdrop-filter:blur(12px); padding:16px 24px;
  display:flex; align-items:center; gap:20px;
  transition:border-color .3s, transform .3s, box-shadow .3s; cursor:none; min-width:220px;
  border-radius:8px;
}
.h-stat:hover { border-color:rgba(42,34,99,.22); transform:translateX(-6px); box-shadow:var(--shadow) }
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
  display:grid; grid-template-columns:repeat(3,1fr);
  gap:1px; background:var(--rim); border:1px solid var(--rim); margin-top:80px;
  border-radius:12px; overflow:hidden;
}
.svc-cell {
  background:#FFFFFF; padding:52px 44px;
  position:relative; overflow:hidden; cursor:none; transition:background .4s;
}
.svc-cell::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,rgba(42,34,99,.04),rgba(108,92,231,.04));
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
  border-right:1.5px solid rgba(42,34,99,.2); border-bottom:1.5px solid rgba(42,34,99,.2);
  opacity:0; transition:opacity .4s;
}
.svc-cell:hover::after { opacity:1 }
.svc-num {
  font-family:var(--font-d); font-size:11px; letter-spacing:4px;
  color:rgba(42,34,99,.2); margin-bottom:28px;
}
.svc-icon-wrap {
  width:56px; height:56px; margin-bottom:28px;
  display:flex; align-items:center; justify-content:center; position:relative;
}
.svc-icon-wrap::before {
  content:''; position:absolute; inset:0;
  border:1.5px solid rgba(42,34,99,.12); transform:rotate(45deg);
  transition:border-color .3s, transform .5s;
}
.svc-cell:hover .svc-icon-wrap::before { border-color:rgba(42,34,99,.3); transform:rotate(90deg) }
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
  opacity:0; transform:translateX(-10px);
  transition:opacity .3s, transform .3s, gap .2s;
}
.svc-cell:hover .svc-link { opacity:1; transform:none }
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
  color:rgba(42,34,99,.05); letter-spacing:-2px; line-height:1;
  margin-bottom:28px; transition:color .4s;
}
.proc-item:hover .proc-n { color:rgba(42,34,99,.1) }
.proc-circle {
  width:64px; height:64px; border-radius:50%;
  border:1.5px solid rgba(42,34,99,.2);
  display:flex; align-items:center; justify-content:center;
  font-family:var(--font-ui); font-size:1.1rem; font-weight:700;
  color:var(--brand); margin-bottom:28px; position:relative;
  transition:border-color .3s, box-shadow .3s;
}
.proc-circle::before {
  content:''; position:absolute; inset:-7px; border-radius:50%;
  border:1px solid rgba(42,34,99,.06); transition:border-color .3s;
}
.proc-item:hover .proc-circle { border-color:rgba(42,34,99,.4); box-shadow:0 0 24px rgba(42,34,99,.1) }
.proc-item:hover .proc-circle::before { border-color:rgba(42,34,99,.12) }
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
  background:#FFFFFF; padding:48px 40px; text-align:center;
  cursor:none;
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
  background:#FFFFFF; padding:52px 44px;
  position:relative; overflow:hidden; cursor:none; transition:background .4s;
}
.testi-block::before {
  content:'"'; position:absolute; top:-24px; right:20px;
  font-family:var(--font-d); font-size:12rem; color:rgba(42,34,99,.04);
  line-height:1; pointer-events:none; transition:color .4s;
}
.testi-block:hover { background:var(--deep) }
.testi-block:hover::before { color:rgba(42,34,99,.07) }
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
  background:#FFFFFF; border:1.5px solid var(--rim); padding:52px 44px;
  position:relative; overflow:hidden; cursor:none; transition:all .4s;
  border-radius:16px;
}
.price-card::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,rgba(42,34,99,.02),rgba(108,92,231,.03));
  opacity:0; transition:opacity .4s; border-radius:inherit;
}
.price-card:hover::before { opacity:1 }
.price-card:hover { border-color:rgba(42,34,99,.2); transform:translateY(-8px); box-shadow:var(--shadow-lg) }
.price-card.featured {
  border-color:rgba(42,34,99,.18);
  background:linear-gradient(160deg,rgba(42,34,99,.03) 0%,rgba(108,92,231,.05) 100%);
}
.price-card.featured::after {
  content:''; position:absolute; top:0; left:0; right:0; height:3px;
  background:linear-gradient(90deg,var(--brand),var(--accent));
  box-shadow:0 0 12px rgba(42,34,99,.2);
}
.price-hot {
  position:absolute; top:24px; right:-32px;
  background:linear-gradient(135deg,var(--brand),var(--accent));
  color:#fff; font-family:var(--font-ui); font-size:9px; font-weight:700;
  letter-spacing:2px; text-transform:uppercase; padding:6px 44px; transform:rotate(45deg);
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
  transition:all .3s; cursor:none; border:none; border-radius:8px;
}
.btn-price-fill { background:linear-gradient(135deg,var(--brand),var(--accent)); color:#fff }
.btn-price-fill:hover { box-shadow:var(--glow-c); transform:translateY(-2px) }
.btn-price-out { border:1.5px solid rgba(42,34,99,.2); color:var(--brand); background:transparent }
.btn-price-out:hover { background:rgba(42,34,99,.04); border-color:var(--brand) }

/* ═══════════════════════════════════════════════════════════
   FAQ
═══════════════════════════════════════════════════════════ */
.faq-list { max-width:820px; margin:80px auto 0 }
.faq-item { border-bottom:1px solid var(--rim); overflow:hidden; transition:border-color .3s }
.faq-item.active { border-color:rgba(42,34,99,.2) }
.faq-q-btn {
  width:100%; background:none; border:none; text-align:left;
  padding:28px 0; display:flex; justify-content:space-between; align-items:center;
  cursor:none; gap:20px; transition:padding-left .2s;
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
.faq-item.active .faq-icon-wrap { transform:rotate(45deg); background:rgba(42,34,99,.06); border-color:rgba(42,34,99,.3) }
.faq-answer { max-height:0; overflow:hidden; transition:max-height .45s cubic-bezier(.4,0,.2,1) }
.faq-answer p { padding:0 0 28px; font-size:15px; color:var(--muted); line-height:1.85; font-weight:300 }
.faq-item.active .faq-answer { max-height:400px }

/* ═══════════════════════════════════════════════════════════
   CTA BLOCK
═══════════════════════════════════════════════════════════ */
.cta-block {
  margin:0; position:relative; overflow:hidden;
  border:1.5px solid rgba(42,34,99,.12); background:var(--brand);
  padding:120px 80px; text-align:center; border-radius:20px;
}
.cta-block::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),rgba(167,139,250,.6),transparent) }
.cta-block::after  { content:''; position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,rgba(108,92,231,.6),rgba(167,139,250,.4),transparent) }
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
.team-card { background:#FFFFFF; overflow:hidden; cursor:none; transition:background .3s }
.team-card:hover { background:var(--deep) }
.team-img { height:280px; overflow:hidden; background:var(--deep); position:relative }
.team-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s }
.team-card:hover .team-img img { transform:scale(1.05) }
.team-img-ph { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:4rem;background:linear-gradient(135deg,rgba(42,34,99,.05),rgba(108,92,231,.05)) }
.team-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(42,34,99,.88) 0%,transparent 55%);opacity:0;transition:opacity .3s }
.team-card:hover .team-overlay { opacity:1 }
.team-socials { position:absolute;bottom:-50px;left:0;right:0;padding:16px;display:flex;gap:8px;justify-content:center;transition:bottom .3s }
.team-card:hover .team-socials { bottom:0 }
.team-soc { padding:7px 14px;border:1.5px solid rgba(255,255,255,.3);color:#fff;font-family:var(--font-ui);font-size:10px;font-weight:500;letter-spacing:1.5px;transition:background .2s;cursor:none;border-radius:4px }
.team-soc:hover { background:rgba(255,255,255,.15) }
.team-info { padding:22px 28px; border-top:1px solid var(--rim) }
.team-name { font-family:var(--font-ui);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:4px;letter-spacing:.3px }
.team-pos { font-size:11px;color:var(--brand);letter-spacing:2px;text-transform:uppercase;opacity:.7 }

/* ═══════════════════════════════════════════════════════════
   BLOG CARDS
═══════════════════════════════════════════════════════════ */
.blog-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:1px;background:var(--rim);border:1px solid var(--rim);border-radius:12px;overflow:hidden }
.blog-card { background:#FFFFFF;display:block;cursor:none;transition:background .3s;overflow:hidden }
.blog-card:hover { background:var(--deep) }
.blog-img { height:220px;overflow:hidden;background:var(--deep);position:relative }
.blog-img img { width:100%;height:100%;object-fit:cover;transition:transform .5s }
.blog-card:hover .blog-img img { transform:scale(1.05) }
.blog-img-ph { width:100%;height:100%;background:linear-gradient(135deg,rgba(42,34,99,.05),rgba(108,92,231,.06));display:flex;align-items:center;justify-content:center;font-size:3rem }
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
.port-grid { display:grid;grid-template-columns:repeat(12,1fr);gap:1px;background:var(--rim);border:1px solid var(--rim);auto-rows:200px;border-radius:12px;overflow:hidden }
.port-item { background:var(--deep);overflow:hidden;position:relative;display:block;cursor:none;transition:opacity .3s }
.port-item:hover { opacity:.93 }
.port-item img { width:100%;height:100%;object-fit:cover;transition:transform .6s }
.port-item:hover img { transform:scale(1.05) }
.port-ph { width:100%;height:100%;background:linear-gradient(135deg,rgba(42,34,99,.06),rgba(108,92,231,.08));display:flex;align-items:center;justify-content:center;font-size:3.5rem }
.port-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(42,34,99,.92) 0%,rgba(42,34,99,.15) 40%,transparent 65%);opacity:0;transition:opacity .35s }
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
  outline:none;transition:border-color .25s,background .25s;cursor:none;
  border-radius:8px;
}
.field-input:focus { border-color:rgba(42,34,99,.45);background:#FFFFFF;box-shadow:0 0 0 3px rgba(42,34,99,.06) }
.field-input::placeholder { color:var(--dim) }
.field-error { font-size:12px;color:#e55;margin-top:5px;display:block }
select.field-input { -webkit-appearance:none;appearance:none;color:var(--muted) }

/* ═══════════════════════════════════════════════════════════
   INFO CARDS (contact info)
═══════════════════════════════════════════════════════════ */
.info-row { display:flex;flex-direction:column;gap:8px }
.info-card {
  background:#FFFFFF;border:1.5px solid var(--rim);padding:22px 26px;
  display:flex;align-items:center;gap:18px;
  transition:border-color .3s,background .3s;cursor:none;border-radius:10px;
  box-shadow:var(--shadow);
}
.info-card:hover { border-color:rgba(42,34,99,.25);background:var(--deep) }
.info-icon { width:46px;height:46px;border:1.5px solid rgba(42,34,99,.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;transition:border-color .3s;border-radius:8px;background:var(--deep) }
.info-card:hover .info-icon { border-color:rgba(42,34,99,.3) }
.info-label { font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--brand);margin-bottom:4px;opacity:.7 }
.info-value { font-size:14.5px;color:var(--text);font-weight:400 }
.info-arr { margin-left:auto;color:var(--brand);opacity:.35;font-size:1.1rem;transition:opacity .2s,transform .2s }
.info-card:hover .info-arr { opacity:.8;transform:translateX(4px) }

/* ═══════════════════════════════════════════════════════════
   CAREER LISTING
═══════════════════════════════════════════════════════════ */
.job-list { display:flex;flex-direction:column;gap:8px;margin-top:60px }
.job-card {
  background:#FFFFFF;border:1.5px solid var(--rim);overflow:hidden;
  transition:border-color .3s;border-radius:12px;box-shadow:var(--shadow);
}
.job-card:hover { border-color:rgba(42,34,99,.22) }
.job-head { padding:28px 36px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;cursor:pointer }
.job-title { font-family:var(--font-ui);font-size:1.2rem;font-weight:700;color:var(--text);margin-bottom:6px;letter-spacing:.3px }
.job-tags { display:flex;gap:8px;flex-wrap:wrap }
.job-tag { font-family:var(--font-ui);font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:5px 12px;border:1.5px solid var(--rim2);color:var(--dim);border-radius:100px }
.job-tag.remote { border-color:rgba(42,34,99,.2);color:var(--brand) }
.job-body { max-height:0;overflow:hidden;transition:max-height .4s cubic-bezier(.4,0,.2,1) }
.job-body-inner { padding:0 36px 32px;border-top:1px solid var(--rim) }
.job-body-inner p { font-size:14.5px;color:var(--muted);line-height:1.8;font-weight:300;margin:20px 0 28px }
.job-card.open .job-body { max-height:500px }

/* ═══════════════════════════════════════════════════════════
   REVEAL ANIMATIONS
═══════════════════════════════════════════════════════════ */
.reveal   { opacity:0;transform:translateY(32px);transition:opacity .8s,transform .8s }
.reveal-l { opacity:0;transform:translateX(-32px);transition:opacity .8s .1s,transform .8s .1s }
.reveal-r { opacity:0;transform:translateX(32px);transition:opacity .8s .1s,transform .8s .1s }
.reveal.vis,.reveal-l.vis,.reveal-r.vis { opacity:1;transform:none }

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
.f-soc { font-family:var(--font-ui);font-size:10px;letter-spacing:2.5px;text-transform:uppercase;padding:9px 18px;border:1.5px solid rgba(255,255,255,.15);color:rgba(255,255,255,.55);transition:all .25s;cursor:none;border-radius:6px }
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
.about-visual-inner { position:absolute;inset:0;background:linear-gradient(135deg,rgba(42,34,99,.04),rgba(108,92,231,.06));display:flex;align-items:center;justify-content:center;overflow:hidden }
.circuit-line { position:absolute;background:rgba(42,34,99,.15) }
.circuit-line.h { height:1px;animation:circ-h 4s ease-in-out infinite }
.circuit-line.v { width:1px;animation:circ-v 3s ease-in-out infinite }
@keyframes circ-h { 0%,100%{left:0;right:100%;opacity:0} 20%{opacity:1} 50%{left:0;right:0;opacity:.5} 80%{opacity:1} to{left:100%;right:0;opacity:0} }
@keyframes circ-v { 0%,100%{top:0;bottom:100%;opacity:0} 20%{opacity:1} 50%{top:0;bottom:0;opacity:.5} 80%{opacity:1} to{top:100%;bottom:0;opacity:0} }
.about-big-num { font-family:var(--font-d);font-size:9rem;letter-spacing:-4px;line-height:.8;background:linear-gradient(135deg,rgba(42,34,99,.14),rgba(108,92,231,.12));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text }

.a-value { padding:28px 0;border-bottom:1px solid var(--rim);display:flex;align-items:flex-start;gap:20px;transition:padding-left .3s;cursor:none }
.a-value:hover { padding-left:14px }
.a-value-icon { width:40px;height:40px;flex-shrink:0;border:1.5px solid var(--rim2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;transition:border-color .3s,background .3s;border-radius:8px;background:#FFFFFF }
.a-value:hover .a-value-icon { border-color:rgba(42,34,99,.25);background:var(--deep) }
.a-value h4 { font-family:var(--font-ui);font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:5px;letter-spacing:.3px }
.a-value p { font-size:13.5px;color:var(--muted);line-height:1.7;font-weight:300 }

/* Benefit box */
.benefit-box {
  background:#FFFFFF;border:1px solid var(--rim);padding:36px 32px;
  border-top:3px solid transparent;transition:all .3s;cursor:none;
}
.benefit-box:hover { background:var(--deep);border-color:rgba(42,34,99,.15);border-top-color:var(--brand) }
.benefit-icon { font-size:1.8rem;margin-bottom:16px }
.benefit-box h4 { font-family:var(--font-ui);font-size:1rem;font-weight:700;color:var(--text);margin-bottom:8px;letter-spacing:.3px }
.benefit-box p { font-size:13.5px;color:var(--muted);line-height:1.75;font-weight:300 }

/* Audit items */
.audit-item { background:#FFFFFF;border:1.5px solid var(--rim);padding:24px 28px;display:flex;gap:18px;align-items:flex-start;transition:border-color .3s,transform .3s;cursor:none;border-radius:10px;box-shadow:var(--shadow) }
.audit-item:hover { border-color:rgba(42,34,99,.25);transform:translateX(6px) }
.audit-item-icon { width:44px;height:44px;flex-shrink:0;border:1.5px solid rgba(42,34,99,.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;transition:border-color .3s;border-radius:8px;background:var(--deep) }
.audit-item:hover .audit-item-icon { border-color:rgba(42,34,99,.3) }
.audit-item h4 { font-family:var(--font-ui);font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:5px;letter-spacing:.3px }
.audit-item p { font-size:13.5px;color:var(--muted);line-height:1.7;font-weight:300 }

/* Blog content styles */
.blog-content h2,.blog-content h3 { font-family:var(--font-d);text-transform:uppercase;font-weight:normal;color:var(--text);margin:40px 0 16px;letter-spacing:1px }
.blog-content h2 { font-size:2.2rem;letter-spacing:1.5px }
.blog-content h3 { font-size:1.6rem }
.blog-content p { margin-bottom:20px;font-size:16px;color:var(--muted);line-height:1.9;font-weight:300 }
.blog-content strong { color:var(--text);font-weight:600 }
.blog-content em { color:var(--brand);font-style:italic }
.blog-content a { color:var(--brand);border-bottom:1px solid rgba(42,34,99,.2);transition:border-color .2s }
.blog-content a:hover { border-color:var(--brand) }
.blog-content ul,.blog-content ol { margin:20px 0 20px 20px }
.blog-content li { color:var(--muted);line-height:1.85;margin-bottom:8px;font-size:15px;font-weight:300 }
.blog-content blockquote { border-left:3px solid var(--brand);padding:20px 28px;background:var(--deep);margin:32px 0;font-style:italic;font-size:17px;color:var(--text);font-weight:300;border-radius:0 8px 8px 0 }
.blog-content img { max-width:100%;margin:28px 0;border:1px solid var(--rim);border-radius:8px }
.blog-content code { background:var(--deep);border:1px solid rgba(42,34,99,.12);padding:2px 8px;font-size:13px;color:var(--brand);border-radius:4px }
.blog-content pre { background:var(--brand);border:none;padding:24px;overflow-x:auto;margin:24px 0;border-radius:10px }
.blog-content pre code { background:none;border:none;padding:0;color:rgba(255,255,255,.85);font-size:13.5px }
.blog-content hr { border:none;border-top:1px solid var(--rim);margin:48px 0 }

/* Alert/Flash */
.flash-success { background:rgba(42,34,99,.06);border:1.5px solid rgba(42,34,99,.15);padding:16px 22px;display:flex;gap:12px;align-items:center;margin-bottom:28px;border-radius:8px }
.flash-success span.icon { color:var(--brand);font-size:1.2rem }
.flash-success span.msg { font-size:14px;color:var(--text);font-weight:400 }

/* Pagination */
.pagi { display:flex;justify-content:center;gap:6px;margin-top:60px }
.pagi a,.pagi span { padding:11px 18px;border:1.5px solid var(--rim);font-family:var(--font-ui);font-size:12px;font-weight:500;letter-spacing:1px;transition:all .2s;cursor:none;border-radius:8px }
.pagi a { color:var(--muted);background:#FFFFFF }
.pagi a:hover { border-color:rgba(42,34,99,.3);color:var(--brand) }
.pagi .current { border-color:rgba(42,34,99,.3);color:var(--brand);background:var(--deep) }
.pagi .disabled { color:var(--dim);opacity:.4 }

/* ═══════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════ */
@media(max-width:1100px) {
  .svc-grid { grid-template-columns:repeat(2,1fr) }
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
  .svc-grid { grid-template-columns:1fr }
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
</style>
</head>
<body>

<!-- CURSOR -->
<div id="cur-dot"></div>
<div id="cur-ring"></div>
<div id="cur-trail"></div>
<!-- SCROLL PROGRESS -->
<div id="spb"></div>
<!-- DATA FLOW LINE -->
<div class="data-line"></div>
<!-- CANVAS BG -->
<canvas id="bg-canvas"></canvas>

<!-- ═══ NAV ═══ -->
<nav class="nav" id="navbar">
  <a href="{{ route('home') }}" class="nav-logo">
    
    @if(!empty($settings['site_logo']))
      <img src="{{ asset($settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'SK Artistic' }}" style="-webkit-text-fill-color:unset;background:none;height:36px;">
    asset($settings['site_logo'])
    @else
    <div class="nav-logo-glyph"><span>SK</span></div>
      {{ $settings['site_name'] ?? 'SK ARTISTIC' }}
    @endif
  </a>

  <button class="ham" id="ham" aria-label="Menu"><span></span><span></span><span></span></button>

  <ul class="nav-center" id="nl">
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

  <a href="{{ route('contact') }}" class="nav-cta">
    Contact
    <span class="nav-cta-arrow">↗</span>
  </a>
</nav>

<!-- ═══ MAIN ═══ -->
<div class="page-wrap">
  @yield('content')
</div>

<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <span class="footer-brand">{{ $settings['site_name'] ?? 'SK ARTISTIC' }}</span>
        <p class="footer-tagline">{{ $settings['site_tagline'] ?? 'Designing without borders. Full-cycle digital agency building the internet\'s next chapter.' }}</p>
        <div class="footer-socials">
          @if(!empty($settings['social_instagram']))<a href="{{ $settings['social_instagram'] }}" class="f-soc" target="_blank">Instagram</a>@endif
          @if(!empty($settings['social_whatsapp']))<a href="{{ $settings['social_whatsapp'] }}" class="f-soc" target="_blank">WhatsApp</a>@endif
          @if(!empty($settings['social_pinterest']))<a href="{{ $settings['social_pinterest'] }}" class="f-soc" target="_blank">Pinterest</a>@endif
          @if(!empty($settings['social_youtube']))<a href="{{ $settings['social_youtube'] }}" class="f-soc" target="_blank">YouTube</a>@endif
          @if(!empty($settings['social_linkedin']))<a href="{{ $settings['social_linkedin'] }}" class="f-soc" target="_blank">LinkedIn</a>@endif
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
          <li><a href="{{ route('blog') }}">Blog</a></li>
          <li><a href="{{ route('team') }}">Our Team</a></li>
          <li><a href="{{ route('careers') }}">Careers</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Contact</h5>
        <ul>
          <li><a href="{{ route('contact') }}">Get In Touch</a></li>
          <li><a href="{{ route('free-audit') }}" style="color:var(--magenta)">Free Audit ✦</a></li>
          @if(!empty($settings['site_email']))<li><a href="/cdn-cgi/l/email-protection#a1dada8185d2c4d5d5c8cfc6d2fa86d2c8d5c4fec4ccc0c8cd86fc81dcdc">{{ $settings['site_email'] }}</a></li>@endif
          @if(!empty($settings['site_phone']))<li><a href="tel:{{ $settings['site_phone'] }}">{{ $settings['site_phone'] }}</a></li>@endif
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>{!! $settings['footer_copyright'] ?? '© 2026 SK Artistic. All rights reserved.' !!}</span>
      <div style="display:flex;gap:20px">
        <a href="/terms-conditions">Terms</a>
        <a href="/privacy-policy">Privacy</a>
        <a href="{{ route('free-audit') }}" style="color:var(--magenta)">Free Audit</a>
      </div>
    </div>
  </div>
</footer>

<!-- ═══ GLOBAL SCRIPTS ═══ -->
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
/* ── CANVAS PARTICLE FIELD (light version) ── */
const cvs=document.getElementById('bg-canvas'),ctx=cvs.getContext('2d');
let W,H,pts=[];
function resize(){W=cvs.width=window.innerWidth;H=cvs.height=window.innerHeight}
resize();window.addEventListener('resize',resize);
class Pt{
  constructor(){this.reset()}
  reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.vx=(Math.random()-.5)*.18;this.vy=(Math.random()-.5)*.18;this.r=Math.random()*1.4+.4;this.a=Math.random()*.2+.04;this.col=Math.random()>.55?'42,34,99':'108,92,231';this.p=Math.random()*Math.PI*2;this.ps=.01+Math.random()*.015}
  update(){this.x+=this.vx;this.y+=this.vy;this.p+=this.ps;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset()}
  draw(){const a=this.a*(.55+.45*Math.sin(this.p));ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);ctx.fillStyle=`rgba(${this.col},${a})`;ctx.fill()}
}
for(let i=0;i<120;i++)pts.push(new Pt());
function drawLines(){for(let i=0;i<pts.length;i++)for(let j=i+1;j<pts.length;j++){const dx=pts[i].x-pts[j].x,dy=pts[i].y-pts[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<100){ctx.beginPath();ctx.moveTo(pts[i].x,pts[i].y);ctx.lineTo(pts[j].x,pts[j].y);ctx.strokeStyle=`rgba(42,34,99,${(1-d/100)*.05})`;ctx.lineWidth=.6;ctx.stroke()}}}
(function loop(){ctx.clearRect(0,0,W,H);drawLines();pts.forEach(p=>{p.update();p.draw()});requestAnimationFrame(loop)})();

/* ── CURSOR ── */
const cd=document.getElementById('cur-dot'),cr=document.getElementById('cur-ring'),ct=document.getElementById('cur-trail');
if(cd){
  let mx=0,my=0,rx=0,ry=0,tx=0,ty=0;
  document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cd.style.left=mx+'px';cd.style.top=my+'px'});
  (function ac(){rx+=(mx-rx)*.08;ry+=(my-ry)*.08;tx+=(mx-tx)*.05;ty+=(my-ty)*.05;cr.style.left=rx+'px';cr.style.top=ry+'px';ct.style.left=tx+'px';ct.style.top=ty+'px';requestAnimationFrame(ac)})();
  document.querySelectorAll('a,button').forEach(el=>{el.addEventListener('mouseenter',()=>document.body.classList.add('hov'));el.addEventListener('mouseleave',()=>document.body.classList.remove('hov'))});
}

/* ── NAV SCROLL ── */
const nav=document.getElementById('navbar');
window.addEventListener('scroll',()=>{
  const p=window.scrollY/(document.body.scrollHeight-window.innerHeight)*100;
  document.getElementById('spb').style.width=p+'%';
  nav.classList.toggle('scrolled',window.scrollY>40);
},{passive:true});

/* ── REVEAL ── */
const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('vis');obs.unobserve(e.target)}})},{threshold:.07,rootMargin:'0px 0px -40px 0px'});
document.querySelectorAll('.reveal,.reveal-l,.reveal-r').forEach(el=>obs.observe(el));

/* ── AUTO STAGGER CARDS ── */
document.querySelectorAll('.svc-cell,.testi-block,.price-card,.team-card,.blog-card,.stat-box,.benefit-box').forEach((el,i)=>{el.style.transitionDelay=(i*.07)+'s';el.classList.add('reveal');obs.observe(el)});

/* ── HAMBURGER ── */
const ham=document.getElementById('ham'),nl=document.getElementById('nl');
if(ham){
  ham.addEventListener('click',()=>{ham.classList.toggle('open');nl.classList.toggle('open')});
  nl.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{if(window.innerWidth<=860){ham.classList.remove('open');nl.classList.remove('open')}}));
}

/* ── FAQ ── */
document.querySelectorAll('.faq-q-btn').forEach(btn=>{btn.addEventListener('click',()=>{const item=btn.closest('.faq-item');document.querySelectorAll('.faq-item.active').forEach(o=>{if(o!==item)o.classList.remove('active')});item.classList.toggle('active')})});

/* ── JOB ACCORDION ── */
document.querySelectorAll('.job-head').forEach(h=>{h.addEventListener('click',()=>{const c=h.closest('.job-card');document.querySelectorAll('.job-card.open').forEach(o=>{if(o!==c)o.classList.remove('open')});c.classList.toggle('open')})});

/* ── GLITCH ── */
const gls=document.querySelectorAll('.hero-h .line-inner');
if(gls.length)setInterval(()=>{const el=gls[Math.floor(Math.random()*gls.length)];el.style.textShadow=`${(Math.random()-.5)*4}px 0 rgba(42,34,99,.5),${(Math.random()-.5)*4}px 0 rgba(108,92,231,.5)`;el.style.transform=`translateX(${(Math.random()-.5)*3}px)`;setTimeout(()=>{el.style.textShadow='';el.style.transform=''},80)},3500);

/* ── MAGNETIC BUTTONS ── */
document.querySelectorAll('.btn-primary').forEach(btn=>{btn.addEventListener('mousemove',e=>{const r=btn.getBoundingClientRect(),cx=r.left+r.width/2,cy=r.top+r.height/2;btn.style.transform=`translate(${(e.clientX-cx)*.12}px,${(e.clientY-cy)*.12-3}px)`});btn.addEventListener('mouseleave',()=>btn.style.transform='')});

/* ── TILT STATS ── */
document.querySelectorAll('.h-stat').forEach(c=>{c.addEventListener('mousemove',e=>{const r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;c.style.transform=`translateX(${-6+x*4}px) rotateX(${-y*5}deg) rotateY(${x*5}deg)`});c.addEventListener('mouseleave',()=>c.style.transform='')});

/* ── COUNTER ── */
function animCount(el,target,dur=1800){let st=null;(function step(ts){if(!st)st=ts;const prog=Math.min((ts-st)/dur,1),eased=1-Math.pow(1-prog,3);el.textContent=Math.floor(eased*target)+'+';if(prog<1)requestAnimationFrame(step)})(performance.now())}
const cObs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.querySelectorAll('[data-count]').forEach(n=>{animCount(n,parseInt(n.dataset.count))});cObs.unobserve(e.target)}})},{threshold:.3});
document.querySelectorAll('.hero-stats,.stats-row,.about-stats-row').forEach(el=>cObs.observe(el));

/* ── LIGHTBOX ── */
window.openLightbox=src=>{const lb=document.getElementById('lightbox');if(lb){document.getElementById('lb-img').src=src;lb.style.display='flex'}};
window.closeLightbox=()=>{const lb=document.getElementById('lightbox');if(lb)lb.style.display='none'};
</script>