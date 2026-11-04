<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SK Admin</title>
    @include('admin.layouts.head')
    <style>
        /* ── SCROLL PROGRESS ── */
        #spb{position:fixed;top:0;left:0;height:3px;width:0;z-index:9999;background:linear-gradient(90deg,var(--p),var(--s),var(--magenta))}

        /* ── SIDEBAR ── */
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:linear-gradient(180deg,#22104f 0%,#150b33 100%);z-index:200;display:flex;flex-direction:column;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.2) transparent}
        html[data-theme="dark"] .sidebar{background:linear-gradient(180deg,#160d2e 0%,#0f0a20 100%);border-right:1px solid var(--a-line)}
        .sidebar-top{padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .sidebar-brand{display:flex;flex-direction:column;align-items:flex-start;gap:6px}
        .sidebar-brand img{height:38px;width:auto;max-width:190px;object-fit:contain;filter:brightness(0) invert(1)}
        .brand-name{font-family:var(--font-d);font-size:1.7rem;letter-spacing:2px;line-height:1;color:#fff}
        .brand-sub{font-family:var(--font-ui);font-size:10px;letter-spacing:2.5px;text-transform:uppercase;color:rgba(255,255,255,.45);font-weight:500}
        .sidebar-nav{padding:14px 0;flex:1;position:relative}
        .nav-group{padding:12px 24px 6px;font-family:var(--font-ui);font-size:10px;font-weight:700;color:rgba(255,255,255,.38);text-transform:uppercase;letter-spacing:2px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:8px 12px;color:rgba(255,255,255,.68);font-family:var(--font-ui);font-size:13.5px;font-weight:500;margin:1px 10px;border-radius:12px;cursor:pointer;position:relative;z-index:1;transition:color .2s,background .2s}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,.07)}
        .nav-item.active{color:#fff;background:linear-gradient(135deg,rgba(168,85,247,.4),rgba(108,43,217,.3))}
        .nav-item-icon{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;transition:transform .4s var(--ease-out),background .2s}
        .nav-item-icon,.stat-box-icon{filter:grayscale(1) sepia(1) hue-rotate(218deg) saturate(3.4) brightness(1.15)}
        .nav-item:hover .nav-item-icon{transform:scale(1.1) rotate(-5deg)}
        .nav-item.active .nav-item-icon{background:linear-gradient(135deg,#6c2bd9,#a855f7)}
        .nav-badge{margin-left:auto;background:#fff;color:#5b21b6;font-size:10px;font-weight:800;padding:2px 8px;border-radius:999px}
        .sidebar-footer{padding:14px;border-top:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .sidebar-user{display:flex;align-items:center;gap:10px;padding:10px 12px;background:rgba(255,255,255,.06);border-radius:12px}
        .user-avatar{width:34px;height:34px;background:linear-gradient(135deg,#6c2bd9,#a855f7);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0}
        .user-name{color:#fff;font-size:13px;font-weight:600}
        .user-role{font-size:11px;color:rgba(255,255,255,.5)}
        .sign-out{width:100%;margin-top:10px;padding:9px;border-radius:10px;background:transparent;border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.75);font-family:var(--font-ui);font-size:13px;font-weight:500;cursor:pointer;transition:background .2s,color .2s}
        .sign-out:hover{background:rgba(255,255,255,.1);color:#fff}
        .sidebar-scrim{display:none;position:fixed;inset:0;background:rgba(8,4,20,.55);z-index:150;opacity:0;transition:opacity .3s}

        /* ── TOPBAR ── */
        .topbar{position:fixed;top:12px;left:calc(var(--sidebar-w) + 20px);right:20px;height:60px;background:rgba(var(--a-panel-rgb),.94);border:1px solid var(--a-line);border-radius:100px;box-shadow:var(--a-shadow);z-index:100;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0 10px 0 24px}
        .topbar-left{display:flex;align-items:center;gap:12px;min-width:0}
        .breadcrumbs{font-family:var(--font-ui);font-size:12.5px;font-weight:500;color:var(--a-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .breadcrumbs b{color:var(--a-text);font-weight:600}
        .topbar-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
        .menu-btn{display:none;width:40px;height:40px;border-radius:50%;border:1px solid var(--a-line);background:transparent;color:var(--a-text);cursor:pointer;align-items:center;justify-content:center;flex-shrink:0}
        .view-site-btn{padding:10px 20px;background:linear-gradient(135deg,var(--p),var(--s));color:#fff;border-radius:100px;font-family:var(--font-ui);font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;box-shadow:0 6px 18px rgba(var(--p-rgb),.3);white-space:nowrap;transition:box-shadow .3s}
        .view-site-btn:hover{box-shadow:0 10px 26px rgba(var(--p-rgb),.45)}

        /* ── MAIN CONTENT ── */
        .main{margin-left:var(--sidebar-w);padding-top:calc(var(--top) + 12px);min-height:100vh}
        .content{padding:12px 28px 40px;max-width:1440px}
        html:not(.m-on) .content{animation:content-in .45s var(--ease-out) both}
        @keyframes content-in{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}

        /* ── PAGE BANNER ── */
        .page-banner{position:relative;overflow:hidden;border-radius:22px;border:1px solid var(--a-line);background:linear-gradient(135deg,var(--a-tint),var(--a-panel));padding:30px 34px;margin-bottom:24px}
        .page-banner::before{content:'';position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(var(--p-rgb),.06) 1px,transparent 1px),linear-gradient(90deg,rgba(var(--p-rgb),.06) 1px,transparent 1px);background-size:44px 44px;-webkit-mask-image:linear-gradient(90deg,transparent,#000);mask-image:linear-gradient(90deg,transparent,#000)}
        .banner-tag{position:relative;z-index:2;font-family:var(--font-ui);font-size:11px;font-weight:700;letter-spacing:3.5px;text-transform:uppercase;color:var(--p);display:flex;align-items:center;gap:12px;margin-bottom:8px}
        .banner-tag::before{content:'';width:26px;height:2px;background:var(--p);opacity:.5;border-radius:2px}
        .page-banner h1{position:relative;z-index:2;font-family:var(--font-d);font-weight:400;font-size:clamp(2.4rem,5vw,4rem);letter-spacing:1.5px;line-height:1;color:var(--a-text)}

        /* ── ALERTS ── */
        .alert{padding:14px 18px;border-radius:14px;margin-bottom:22px;font-size:14px;display:flex;align-items:center;gap:10px;font-weight:500;flex-wrap:wrap}
        .alert-success{background:var(--a-tint);border:1px solid var(--a-line);color:var(--a-text)}
        .alert-danger{background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.28);color:#dc2626}
        html[data-theme="dark"] .alert-danger{color:#fca5a5}

        /* ── CARDS ── */
        .card{background:var(--a-panel);border-radius:18px;border:1px solid var(--a-line);overflow:hidden;box-shadow:var(--a-shadow);transition:box-shadow .3s,border-color .3s}
        .card:hover{box-shadow:var(--a-shadow-lg)}
        .card-head{padding:20px 24px;border-bottom:1px solid var(--a-line);display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
        .card-title{font-family:var(--font-ui);font-size:1rem;font-weight:700;color:var(--a-text)}
        .card-body{padding:24px}
        .card-footer{padding:16px 24px;background:var(--a-soft);border-top:1px solid var(--a-line);font-size:13px;color:var(--a-muted)}

        /* ── STAT BOXES ── */
        .stats-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px}
        .stat-box{background:var(--a-panel);border-radius:18px;border:1px solid var(--a-line);padding:24px;position:relative;overflow:hidden;box-shadow:var(--a-shadow);transition:box-shadow .3s}
        .stat-box:hover{box-shadow:var(--a-shadow-lg)}
        .stat-box::before{content:'';position:absolute;top:0;right:0;width:84px;height:84px;border-radius:50%;background:rgba(var(--p-rgb),.08);transform:translate(20px,-20px);transition:transform .5s var(--ease-out)}
        .stat-box:hover::before{transform:translate(14px,-26px) scale(1.2)}
        .stat-box-icon{font-size:1.6rem;margin-bottom:12px}
        .stat-box-num{font-family:var(--font-d);font-size:3rem;font-weight:400;color:var(--p);letter-spacing:1px;line-height:1}
        .stat-box-lbl{font-size:13px;color:var(--a-muted);margin-top:6px;font-weight:500}
        .stat-box-change{font-size:12px;margin-top:8px;font-weight:600}
        .change-up,.change-down{color:var(--p)}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:14px}
        thead th{padding:12px 16px;text-align:left;background:var(--a-soft);color:var(--a-muted);font-family:var(--font-ui);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--a-line);white-space:nowrap}
        tbody td{padding:15px 16px;border-bottom:1px solid var(--a-line);vertical-align:middle;color:var(--a-text);transition:background .2s}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:var(--a-soft)}
        tbody tr td:first-child{box-shadow:inset 0 0 0 0 var(--p);transition:box-shadow .25s var(--ease-out),background .2s}
        tbody tr:hover td:first-child{box-shadow:inset 3px 0 0 0 var(--p)}
        .td-img{width:40px;height:40px;border-radius:10px;object-fit:cover;background:var(--a-soft);transition:transform .35s var(--ease-out)}
        tbody tr:hover .td-img{transform:scale(1.08)}
        .td-emoji{font-size:1.5rem}

        /* ── BUTTONS ── */
        .btn{padding:9px 20px;border-radius:100px;font-family:var(--font-ui);font-size:13px;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:6px;position:relative;overflow:hidden;isolation:isolate;transition:box-shadow .25s,background .25s,color .25s,border-color .25s}
        .btn-primary{background:linear-gradient(135deg,var(--p),var(--s));color:#fff;box-shadow:0 6px 18px rgba(var(--p-rgb),.28)}
        .btn-primary:hover{box-shadow:0 10px 28px rgba(var(--p-rgb),.45)}
        .btn-danger{background:#ef4444;color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.22)}
        .btn-danger:hover{box-shadow:0 8px 20px rgba(239,68,68,.38)}
        .btn-ghost{background:transparent;color:var(--a-muted);border:1.5px solid var(--a-line)}
        .btn-ghost:hover{border-color:var(--p);color:var(--p)}
        .btn-sm{padding:7px 14px;font-size:12px}
        .btn-icon{width:34px;height:34px;padding:0;justify-content:center}

        /* ── BADGES ── */
        .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-family:var(--font-ui);font-size:11px;font-weight:700;letter-spacing:.2px}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.8}
        .badge-green,.badge-purple,.badge-blue{background:var(--a-tint);color:var(--p)}
        .badge-yellow{background:var(--a-soft);color:var(--a-muted);border:1px solid var(--a-line)}
        .badge-red{background:rgba(239,68,68,.12);color:#dc2626}
        html[data-theme="dark"] .badge-red{color:#fca5a5}

        /* ── FORMS ── */
        .form-group{margin-bottom:22px}
        .form-label{display:block;font-family:var(--font-ui);font-size:12.5px;font-weight:600;color:var(--a-text);margin-bottom:7px;transition:color .25s}
        .form-group:focus-within .form-label{color:var(--p)}
        .form-label .req{color:var(--p)}
        .form-control{width:100%;padding:12px 16px;border:1.5px solid var(--a-line);border-radius:12px;font-size:14px;color:var(--a-text);outline:none;font-family:var(--font-b);background:var(--a-soft);transition:border-color .25s,background .25s,box-shadow .35s var(--ease-out)}
        .form-control:focus{border-color:var(--s);background:var(--a-panel);box-shadow:0 0 0 4px rgba(var(--accent-rgb),.15)}
        .form-control::placeholder{color:var(--a-dim)}
        textarea.form-control{resize:vertical;min-height:130px}
        select.form-control{cursor:pointer}
        .form-hint{font-size:12px;color:var(--a-dim);margin-top:5px}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
        .form-check{display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:500;color:var(--a-text);padding:3px 0}
        .form-check input[type=checkbox]{width:18px;height:18px;border-radius:5px;accent-color:var(--p);cursor:pointer}
        .form-section{border:1px solid var(--a-line);border-radius:16px;padding:24px;margin-bottom:24px}
        .form-section-title{font-family:var(--font-ui);font-size:14px;font-weight:700;color:var(--a-text);margin-bottom:18px;display:flex;align-items:center;gap:8px}
        .img-preview{width:100px;height:100px;border-radius:12px;object-fit:cover;border:2px solid var(--a-line);margin-top:8px}
        .divider{border:none;border-top:1px solid var(--a-line);margin:24px 0}

        /* ── PAGINATION ── */
        .pagination-wrap{margin-top:20px;display:flex;justify-content:center}

        /* ── EMPTY STATE ── */
        .empty-state{text-align:center;padding:60px 20px;color:var(--a-dim)}
        .empty-state-icon{font-size:3.5rem;margin-bottom:14px;display:inline-block;animation:empty-float 3.2s ease-in-out infinite}
        @keyframes empty-float{0%,100%{transform:translateY(0) rotate(-4deg)}50%{transform:translateY(-10px) rotate(4deg)}}
        .empty-state p{font-size:15px;margin-bottom:20px}

        /* ── ACTIVITY ── */
        .activity-list{display:flex;flex-direction:column;gap:0}
        .activity-item{display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid var(--a-line)}
        .activity-item:last-child{border-bottom:none}
        .activity-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
        .activity-text{font-size:14px;color:var(--a-text);line-height:1.4}
        .activity-time{font-size:12px;color:var(--a-dim);margin-top:2px}

        /* Layout grids used across pages */
        .dash-grid{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:22px;align-items:start}

        @media(max-width:1024px){.dash-grid{grid-template-columns:minmax(0,1fr)}}
        @media(max-width:960px){
            .sidebar{transform:translateX(-100%);transition:transform .45s var(--ease-out)}
            .sidebar.open{transform:none}
            .sidebar-scrim.open{display:block;opacity:1}
            .main{margin-left:0}
            .topbar{left:12px;right:12px;padding-left:10px}
            .menu-btn{display:flex}
            .form-grid,.form-grid-3{grid-template-columns:minmax(0,1fr)}
            .stats-row{grid-template-columns:1fr 1fr}
            .content{padding:8px 14px 32px}
            .page-banner{padding:22px 20px}
            .view-site-btn span{display:none}
            .view-site-btn{padding:10px 12px}
        }
    </style>
    @yield('styles')
</head>
<body>
<div id="curtain" aria-hidden="true"><div class="cu-cols"><i></i><i></i><i></i><i></i><i></i></div><div class="cu-label"></div></div>
<div id="cur-dot"></div><div id="cur-ring"></div>
<div id="spb"></div>
<div class="sidebar-scrim" id="sidebar-scrim"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar" data-lenis-prevent>
    <div class="sidebar-top">
        <div class="sidebar-brand">
            @php $adminLogo = \App\Models\Setting::get('site_logo'); @endphp
            @if($adminLogo)<img src="{{ asset($adminLogo) }}" alt="SK Artistic">@else<div class="brand-name">SK ARTISTIC</div>@endif
            <span class="brand-sub">Admin Panel</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="nav-item-icon">📊</div> Dashboard
        </a>
        <a href="{{ route('home') }}" target="_blank" class="nav-item">
            <div class="nav-item-icon">🌐</div> View Website
        </a>

        <div class="nav-group" style="margin-top:8px">Content</div>
        <a href="{{ route('admin.pages.index') }}" class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            <div class="nav-item-icon">📄</div> Pages
        </a>
        <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <div class="nav-item-icon">⚡</div> Services
        </a>
        <a href="{{ route('admin.about') }}" class="nav-item {{ request()->routeIs('admin.about') ? 'active' : '' }}">
            <div class="nav-item-icon">🏛️</div> About Us
        </a>
        <a href="{{ route('admin.blog.index') }}" class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
            <div class="nav-item-icon">✏️</div> Blog Posts
        </a>
        <a href="{{ route('admin.team.index') }}" class="nav-item {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
            <div class="nav-item-icon">👥</div> Team Members
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
            <div class="nav-item-icon">⭐</div> Testimonials
        </a>
        <a href="{{ route('admin.faqs.index') }}" class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
            <div class="nav-item-icon">❓</div> FAQs
        </a>
        <a href="{{ route('admin.pricing.index') }}" class="nav-item {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}">
            <div class="nav-item-icon">💰</div> Pricing Plans
        </a>
        <a href="{{ route('admin.careers.index') }}" class="nav-item {{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">
            <div class="nav-item-icon">💼</div> Careers
        </a>

        <div class="nav-group" style="margin-top:8px">Inbox</div>
        <a href="{{ route('admin.contacts.index') }}" class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
            <div class="nav-item-icon">📬</div> Messages
            @php $unread = \App\Models\Contact::where('is_read',false)->count(); @endphp
            @if($unread)<span class="nav-badge">{{ $unread }}</span>@endif
        </a>

        <a href="{{ route('admin.portfolio.index') }}" class="nav-item {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🎨</div> Portfolio
        </a>
        <a href="{{ route('admin.client-logos.index') }}" class="nav-item {{ request()->routeIs('admin.client-logos.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🏢</div> Client Logos
        </a>

        <div class="nav-group" style="margin-top:8px">Growth</div>
        <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <div class="nav-item-icon">📈</div> Analytics
        </a>
        <a href="{{ route('admin.audit-leads.index') }}" class="nav-item {{ request()->routeIs('admin.audit-leads.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🔍</div> Audit Leads
            @php $newLeads = \App\Models\AuditLead::where('is_read',false)->count(); @endphp
            @if($newLeads)<span class="nav-badge">{{ $newLeads }}</span>@endif
        </a>
        <a href="{{ route('admin.media.index') }}" class="nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🖼️</div> Media Library
        </a>

        <div class="nav-group" style="margin-top:8px">Customize</div>
        <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <div class="nav-item-icon">⚙️</div> General Settings
        </a>
        
        <a href="{{ route('admin.seo.index') }}" class="nav-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🧭</div> SEO Manager
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" data-label="Signing out…">
            @csrf
            <button type="submit" class="sign-out">Sign Out</button>
        </form>
    </div>
</aside>

{{-- TOPBAR --}}
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="menu-btn" id="menu-btn" aria-label="Open menu" aria-expanded="false" aria-controls="sidebar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        <div class="breadcrumbs">Admin Panel / <b>@yield('title', 'Dashboard')</b></div>
    </div>
    <div class="topbar-right">
        <button type="button" class="theme-btn" id="theme-btn" aria-label="Switch colour theme">
            <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
            <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
        </button>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="view-site-btn" aria-label="View website">🌐 <span>View Site</span></a>
    </div>
</header>

{{-- MAIN --}}
<main class="main">
    <div class="content">
        <div class="page-banner" id="page-banner">
            <div class="banner-tag">{{ request()->routeIs('admin.dashboard') ? 'Welcome back, '.(auth()->user()->name ?? 'Admin') : 'Admin Panel' }}</div>
            <h1>@yield('title', 'Dashboard')</h1>
        </div>
        @if(session('success'))
        <div class="alert alert-success" role="status"><span class="alert-ico">✓</span> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $e)<div>⚠️ {{ $e }}</div>@endforeach
        </div>
        @endif
        @yield('content')
    </div>
</main>

<script>
(function(){
  var side=document.getElementById('sidebar'),scrim=document.getElementById('sidebar-scrim'),btn=document.getElementById('menu-btn'),spb=document.getElementById('spb');
  function setMenu(open){side.classList.toggle('open',open);scrim.classList.toggle('open',open);btn.setAttribute('aria-expanded',open?'true':'false')}
  btn.addEventListener('click',function(){setMenu(!side.classList.contains('open'))});
  scrim.addEventListener('click',function(){setMenu(false)});
  document.addEventListener('keydown',function(e){if(e.key==='Escape')setMenu(false)});
  document.querySelectorAll('.nav-item').forEach(function(item){item.addEventListener('click',function(){setMenu(false)})});

  // Keep the sidebar where it was between pages
  try{
    var saved=sessionStorage.getItem('sk-a-side');
    if(saved)side.scrollTop=+saved;
    window.addEventListener('pagehide',function(){sessionStorage.setItem('sk-a-side',side.scrollTop)});
  }catch(e){}

  function onScroll(){var max=document.documentElement.scrollHeight-innerHeight;spb.style.width=(max>0?scrollY/max*100:0)+'%'}
  addEventListener('scroll',onScroll,{passive:true});onScroll();
})();
</script>
@include('admin.layouts.foot')
@yield('scripts')
</body>
</html>