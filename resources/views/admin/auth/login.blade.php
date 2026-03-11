<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: system-ui; }
        .login-box { background: #1e293b; border-radius: 16px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 25px 60px rgba(0,0,0,0.5); }
        h1 { color: #fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 8px; }
        p { color: rgba(255,255,255,0.5); font-size: 14px; margin-bottom: 28px; }
        label { display: block; color: rgba(255,255,255,0.7); font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 14px; outline: none; margin-bottom: 16px; }
        input:focus { border-color: #6c2bd9; }
        button { width: 100%; padding: 13px; background: linear-gradient(135deg, #6c2bd9, #a855f7); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; margin-top: 8px; }
        .error { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="login-box">
    <h1>⚡ Admin Panel</h1>
    <p>Sign in to SK Artistic CMS</p>
    @if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@skartistic.com">
        <label>Password</label>
        <input type="password" name="password" required placeholder="••••••••">
        <button type="submit">Sign In →</button>
    </form>
</div>
</body>
</html>