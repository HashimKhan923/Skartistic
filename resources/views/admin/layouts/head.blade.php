@php
  // Motion bundle is optional: a deploy without the new build keeps working, just without motion.
  $adminManifest = public_path('build/manifest.json');
  view()->share('adminMotion', is_file(public_path('hot')) || (is_file($adminManifest) && str_contains(file_get_contents($adminManifest), 'resources/js/admin-motion.js')));
@endphp
<meta name="robots" content="noindex">
<meta name="theme-color" content="#6c2bd9" id="meta-theme">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
<script>
(function(){
  var d=document.documentElement,t=null,rm=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  d.classList.add('js');
  try{t=localStorage.getItem('sk-theme')}catch(e){}
  if(t!=='dark'&&t!=='light')t=window.matchMedia&&matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';
  d.setAttribute('data-theme',t);
  @if(view()->shared('adminMotion'))
  if(!rm)d.classList.add('m-on');
  @endif
})();
</script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --p:#6c2bd9;--p-rgb:108,43,217;--s:#a855f7;--magenta:#a78bfa;--danger:#ef4444;--warn:#a855f7;--success:#6c2bd9;
  --brand:var(--p);--accent:var(--s);--brand-rgb:var(--p-rgb);--accent-rgb:168,85,247;
  --a-bg:#faf8ff;--a-panel:#ffffff;--a-panel-rgb:255,255,255;--a-soft:#f6f3ff;--a-tint:#ede9fe;
  --a-line:rgba(108,43,217,.14);--a-text:#1f2937;--a-muted:rgba(31,41,55,.68);--a-dim:rgba(31,41,55,.46);
  --a-shadow:0 2px 24px rgba(108,43,217,.07);--a-shadow-lg:0 14px 44px rgba(108,43,217,.14);
  --font-d:'Bebas Neue',sans-serif;--font-ui:'Inter',sans-serif;--font-b:'Space Grotesk',sans-serif;
  --ease-out:cubic-bezier(.16,1,.3,1);
  --aurora-a:#a855f7;--aurora-b:#a78bfa;--aurora-c:#EAE6FF;--aurora-k:1;
  --sidebar-w:264px;--top:72px;
}
html[data-theme="dark"]{
  color-scheme:dark;
  --p:#8b5cf6;--p-rgb:139,92,246;--s:#a78bfa;--magenta:#c4b5fd;--accent-rgb:167,139,250;--warn:#a78bfa;--success:#8b5cf6;
  --a-bg:#0d0a18;--a-panel:#1a1430;--a-panel-rgb:26,20,48;--a-soft:#151026;--a-tint:#2a1f4f;
  --a-line:rgba(196,181,253,.14);--a-text:#f1eefc;--a-muted:rgba(226,219,250,.74);--a-dim:rgba(226,219,250,.52);
  --a-shadow:0 2px 24px rgba(0,0,0,.35);--a-shadow-lg:0 14px 44px rgba(0,0,0,.5);
  --aurora-a:#7c3aed;--aurora-b:#6d28d9;--aurora-c:#4c1d95;--aurora-k:1.25;
}
body{font-family:var(--font-b);background:var(--a-bg);color:var(--a-text);min-height:100vh}
a{text-decoration:none;color:inherit}
a:focus-visible,button:focus-visible,select:focus-visible,summary:focus-visible{outline:2.5px solid var(--s);outline-offset:3px;border-radius:4px}

/* Page curtain: lifts itself if the bundle never arrives */
#curtain{display:none;position:fixed;inset:0;z-index:100000;pointer-events:none;color:#fff}
html.m-on #curtain{display:block;animation:cu-failsafe 0s 5s forwards}
@keyframes cu-failsafe{to{visibility:hidden}}
.cu-cols{position:absolute;inset:0;display:flex}
.cu-cols i{flex:1;height:100%;margin-right:-1px;background:var(--p)}
html[data-theme="dark"] .cu-cols i{background:#1c1238}
.cu-label{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;text-align:center;padding:0 24px;font-family:var(--font-d);font-size:clamp(2.2rem,6vw,5rem);letter-spacing:4px;line-height:1;visibility:hidden}

/* Theme toggle */
.theme-btn{width:40px;height:40px;flex-shrink:0;padding:0;position:relative;display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:50%;border:1px solid var(--a-line);background:var(--a-panel);color:var(--p);transition:background .3s,border-color .3s,transform .3s var(--ease-out)}
.theme-btn:hover{background:var(--a-tint);transform:rotate(14deg)}
html[data-theme="dark"] .theme-btn{color:#fde9ff}
.theme-btn svg{position:absolute;width:18px;height:18px;transition:transform .55s var(--ease-out),opacity .3s}
.theme-btn .i-sun{opacity:0;transform:rotate(-90deg) scale(.4)}
html[data-theme="dark"] .theme-btn .i-moon{opacity:0;transform:rotate(90deg) scale(.4)}
html[data-theme="dark"] .theme-btn .i-sun{opacity:1;transform:none}
::view-transition-old(root),::view-transition-new(root){animation:none;mix-blend-mode:normal}
::view-transition-new(root){z-index:9999}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
</style>
