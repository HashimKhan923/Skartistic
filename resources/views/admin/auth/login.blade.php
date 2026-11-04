<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SK Artistic</title>
    @include('admin.layouts.head')
    <style>
        body{display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow-x:hidden}
        .login-stage{position:fixed;inset:0;overflow:hidden;background:var(--a-bg)}
        .login-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(var(--p-rgb),.07) 1px,transparent 1px),linear-gradient(90deg,rgba(var(--p-rgb),.07) 1px,transparent 1px);background-size:60px 60px;-webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,#000,transparent);mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,#000,transparent)}
        .login-wrap{position:relative;z-index:2;width:100%;max-width:440px}
        .login-top{position:fixed;top:20px;right:20px;z-index:3}
        .login-title{font-family:var(--font-d);font-size:clamp(3.4rem,10vw,5.4rem);line-height:.9;letter-spacing:2px;text-align:center;margin-bottom:10px}
        .login-title .g{background:linear-gradient(135deg,var(--p),var(--s));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
        .login-sub{text-align:center;color:var(--a-muted);font-size:15px;font-weight:300;margin-bottom:30px}
        .login-box{background:rgba(var(--a-panel-rgb),.86);border:1px solid var(--a-line);border-radius:22px;padding:36px;box-shadow:var(--a-shadow-lg)}
        .login-box label.lbl{display:block;font-family:var(--font-ui);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:var(--p);margin-bottom:8px}
        .login-box input[type=email],.login-box input[type=password]{width:100%;padding:14px 16px;background:var(--a-soft);border:1.5px solid var(--a-line);border-radius:12px;color:var(--a-text);font-family:var(--font-b);font-size:14px;outline:none;margin-bottom:18px;transition:border-color .3s,box-shadow .35s var(--ease-out),transform .35s var(--ease-out)}
        .login-box input:focus{border-color:var(--s);box-shadow:0 0 0 4px rgba(var(--accent-rgb),.16);transform:translateY(-1px)}
        .login-check{display:flex;align-items:center;gap:9px;font-size:13px;color:var(--a-muted);margin-bottom:22px;cursor:pointer}
        .login-check input{width:16px;height:16px;accent-color:var(--p)}
        .login-btn{width:100%;padding:15px;background:linear-gradient(135deg,var(--p),var(--s));color:#fff;border:none;border-radius:12px;font-family:var(--font-ui);font-size:14px;font-weight:700;letter-spacing:.3px;cursor:pointer;box-shadow:0 10px 30px rgba(var(--p-rgb),.32);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;gap:10px;transition:box-shadow .3s}
        .login-btn:hover{box-shadow:0 14px 38px rgba(var(--p-rgb),.46)}
        .login-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444;padding:12px 14px;border-radius:10px;font-size:13px;margin-bottom:18px}
        .login-back{display:block;text-align:center;margin-top:20px;font-size:13px;color:var(--a-muted);transition:color .25s}
        .login-back:hover{color:var(--p)}
        @media(max-width:480px){.login-box{padding:26px 20px}}
    </style>
</head>
<body>
<div id="curtain" aria-hidden="true"><div class="cu-cols"><i></i><i></i><i></i><i></i><i></i></div><div class="cu-label"></div></div>
<div id="cur-dot"></div><div id="cur-ring"></div>

<div class="login-stage" id="login-stage"><div class="login-grid"></div></div>
<div class="login-top">
    <button type="button" class="theme-btn" id="theme-btn" aria-label="Switch colour theme">
        <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
        <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
    </button>
</div>

<main class="login-wrap">
    <h1 class="login-title" data-split>SK <span class="g">ADMIN</span></h1>
    <p class="login-sub" data-rise>Sign in to manage SK Artistic.</p>
    <div class="login-box has-spot" data-rise>
        @if($errors->any())
        <div class="login-error" role="alert" data-shake>{{ $errors->first() }}</div>
        @elseif(session('error'))
        <div class="login-error" role="alert" data-shake>{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.post') }}" data-label="Signing in…">
            @csrf
            <label class="lbl" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus placeholder="you@skartistic.com">
            <label class="lbl" for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <label class="login-check"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
            <button type="submit" class="login-btn"><span>Sign In</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
        </form>
    </div>
    <a href="{{ route('home') }}" class="login-back" data-rise>← Back to website</a>
</main>
@include('admin.layouts.foot')
</body>
</html>
