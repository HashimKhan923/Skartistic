<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<title>@yield('code') — @yield('title')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@300;400;500;600&display=swap" rel="stylesheet">
{{-- Self-contained on purpose: error pages can't depend on the database or the build. --}}
<style>
:root { --brand:#2A2263; --accent:#6c5ce7; --magenta:#a78bfa; --void:#f5f3ff; --muted:rgba(42,34,99,.62) }
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0 }
body { min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; padding:32px 20px; font-family:'Space Grotesk',sans-serif; background:var(--void); color:var(--brand); position:relative }
.bg { position:fixed; inset:0; overflow:hidden; pointer-events:none }
.grid { position:absolute; inset:0; background-image:linear-gradient(rgba(42,34,99,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(42,34,99,.05) 1px,transparent 1px); background-size:60px 60px; -webkit-mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,#000,transparent); mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,#000,transparent) }
.blob { position:absolute; width:560px; height:560px; border-radius:50%; filter:blur(80px); opacity:.45; animation:drift 12s ease-in-out infinite alternate }
.blob.a { background:var(--accent); top:-180px; left:-120px }
.blob.b { background:var(--magenta); bottom:-200px; right:-120px; animation-delay:-5s }
@keyframes drift { to { transform:translate(90px,70px) scale(1.15) } }
main { position:relative; z-index:1; max-width:620px }
.code { font-family:'Bebas Neue',sans-serif; font-size:clamp(8rem,28vw,17rem); line-height:.85; letter-spacing:4px; display:flex; justify-content:center; overflow:hidden; padding-top:.06em }
.code span { display:inline-block; background:linear-gradient(135deg,var(--brand),var(--accent)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; animation:rise 1s cubic-bezier(.16,1,.3,1) both, bob 4s ease-in-out 1s infinite alternate }
.code span:nth-child(2) { animation-delay:.09s,1.3s }
.code span:nth-child(3) { animation-delay:.18s,1.6s }
@keyframes rise { from { transform:translateY(105%) rotate(8deg) } to { transform:none } }
@keyframes bob { to { transform:translateY(-3%) } }
h1 { font-family:'Bebas Neue',sans-serif; font-weight:400; font-size:clamp(1.8rem,5vw,3rem); letter-spacing:2px; margin:18px 0 12px; animation:fade .9s .35s both }
p { color:var(--muted); line-height:1.75; font-weight:300; margin-bottom:32px; animation:fade .9s .45s both }
a { display:inline-flex; align-items:center; gap:10px; background:var(--brand); color:#fff; text-decoration:none; font-size:14px; font-weight:500; padding:14px 30px; border-radius:100px; transition:transform .3s,box-shadow .3s; animation:fade .9s .55s both }
a:hover { transform:translateY(-3px); box-shadow:0 14px 34px rgba(42,34,99,.3) }
a:focus-visible { outline:2.5px solid var(--accent); outline-offset:3px }
@keyframes fade { from { opacity:0; transform:translateY(16px) } to { opacity:1; transform:none } }
@media (prefers-reduced-motion:reduce) { *,*::before,*::after { animation:none!important; transition:none!important } }
</style>
</head>
<body>
<div class="bg" aria-hidden="true"><div class="grid"></div><div class="blob a"></div><div class="blob b"></div></div>
<main>
  <div class="code" aria-hidden="true">@foreach(str_split(trim($__env->yieldContent('code'))) as $digit)<span>{{ $digit }}</span>@endforeach</div>
  <h1>@yield('title')</h1>
  <p>@yield('message')</p>
  <a href="{{ url('/') }}">Back to home <span aria-hidden="true">→</span></a>
</main>
</body>
</html>
