<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SK Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--p:#7c3aed;--s:#06b6d4;--danger:#ef4444;--warn:#f59e0b;--success:#10b981;--sidebar:#0f0f23;--sidebar-w:260px;--top:64px}
        body{font-family:'DM Sans',system-ui,sans-serif;background:#f1f4f9;color:#1e293b;min-height:100vh}
        a{text-decoration:none;color:inherit}

        /* ── SIDEBAR ── */
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--sidebar);z-index:200;display:flex;flex-direction:column;overflow-y:auto}
        .sidebar-top{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0}
        .sidebar-brand{display:flex;align-items:center;gap:10px}
        .brand-dot{width:32px;height:32px;background:linear-gradient(135deg,var(--p),var(--s));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
        .brand-name{font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:-0.3px}
        .brand-sub{font-size:10px;color:rgba(255,255,255,.3);display:block;margin-top:1px;font-weight:500}
        .sidebar-nav{padding:16px 0;flex:1}
        .nav-group{padding:6px 16px 2px;font-size:10px;font-weight:700;color:rgba(255,255,255,.25);text-transform:uppercase;letter-spacing:1.5px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:11px 20px;color:rgba(255,255,255,.5);font-size:14px;font-weight:500;border-radius:0;transition:all .2s;margin:1px 8px;border-radius:10px;cursor:pointer}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,.06)}
        .nav-item.active{color:#fff;background:linear-gradient(135deg,rgba(124,58,237,.3),rgba(6,182,212,.15));border:1px solid rgba(124,58,237,.3)}
        .nav-item-icon{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.06);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;transition:background .2s}
        .nav-item.active .nav-item-icon{background:linear-gradient(135deg,var(--p),var(--s))}
        .sidebar-footer{padding:16px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0}
        .sidebar-user{display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border-radius:12px}
        .user-avatar{width:34px;height:34px;background:linear-gradient(135deg,var(--p),var(--s));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0}
        .user-name{color:#fff;font-size:13px;font-weight:600}
        .user-role{font-size:11px;color:rgba(255,255,255,.35)}
        .nav-badge{margin-left:auto;background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px}

        /* ── TOPBAR ── */
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--top);background:#fff;border-bottom:1px solid #e8ecf0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:0 32px}
        .page-title{font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;color:#1e293b}
        .breadcrumbs{font-size:12px;color:#94a3b8;margin-top:2px}
        .topbar-right{display:flex;align-items:center;gap:12px}
        .topbar-btn{width:36px;height:36px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:1rem;cursor:pointer;transition:all .2s}
        .topbar-btn:hover{background:#f0f4ff;border-color:rgba(124,58,237,.3)}
        .view-site-btn{padding:8px 18px;background:linear-gradient(135deg,var(--p),var(--s));color:#fff;border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;transition:opacity .2s}
        .view-site-btn:hover{opacity:.9}

        /* ── MAIN CONTENT ── */
        .main{margin-left:var(--sidebar-w);padding-top:var(--top);min-height:100vh}
        .content{padding:28px 32px;max-width:1400px}

        /* ── ALERTS ── */
        .alert{padding:14px 18px;border-radius:12px;margin-bottom:22px;font-size:14px;display:flex;align-items:center;gap:10px;font-weight:500}
        .alert-success{background:linear-gradient(135deg,rgba(16,185,129,.1),rgba(5,150,105,.07));border:1px solid rgba(16,185,129,.25);color:#065f46}
        .alert-danger{background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2);color:#991b1b}

        /* ── CARDS ── */
        .card{background:#fff;border-radius:16px;border:1px solid #e8ecf0;overflow:hidden}
        .card-head{padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center}
        .card-title{font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;color:#1e293b}
        .card-body{padding:24px}
        .card-footer{padding:16px 24px;background:#f8fafc;border-top:1px solid #f1f5f9;font-size:13px;color:#64748b}

        /* ── STAT BOXES ── */
        .stats-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:18px;margin-bottom:24px}
        .stat-box{background:#fff;border-radius:16px;border:1px solid #e8ecf0;padding:24px;position:relative;overflow:hidden}
        .stat-box::before{content:'';position:absolute;top:0;right:0;width:80px;height:80px;border-radius:50%;background:var(--box-color,rgba(124,58,237,.07));transform:translate(20px,-20px)}
        .stat-box-icon{font-size:1.6rem;margin-bottom:12px}
        .stat-box-num{font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--p);letter-spacing:-1px;line-height:1}
        .stat-box-lbl{font-size:13px;color:#64748b;margin-top:6px;font-weight:500}
        .stat-box-change{font-size:12px;margin-top:8px;font-weight:600}
        .change-up{color:var(--success)}
        .change-down{color:var(--danger)}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:14px}
        thead th{padding:12px 16px;text-align:left;background:#f8fafc;color:#64748b;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;border-bottom:1px solid #e8ecf0;white-space:nowrap}
        thead th:first-child{border-radius:8px 0 0 0}
        thead th:last-child{border-radius:0 8px 0 0}
        tbody td{padding:15px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle;color:#334155}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:#f8fafc}
        .td-img{width:40px;height:40px;border-radius:10px;object-fit:cover;background:#f1f5f9}
        .td-emoji{font-size:1.5rem}

        /* ── BUTTONS ── */
        .btn{padding:9px 20px;border-radius:10px;font-size:13.5px;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:6px;transition:all .2s;font-family:'DM Sans',sans-serif}
        .btn:hover{transform:translateY(-1px)}
        .btn-primary{background:linear-gradient(135deg,var(--p),var(--s));color:#fff;box-shadow:0 4px 15px rgba(124,58,237,.25)}
        .btn-primary:hover{box-shadow:0 8px 25px rgba(124,58,237,.4)}
        .btn-danger{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.2)}
        .btn-danger:hover{box-shadow:0 8px 20px rgba(239,68,68,.35)}
        .btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e2e8f0}
        .btn-ghost:hover{background:#f8fafc;border-color:#cbd5e1;color:#334155}
        .btn-sm{padding:7px 14px;font-size:12px;border-radius:8px}
        .btn-icon{width:34px;height:34px;padding:0;border-radius:8px;justify-content:center}

        /* ── BADGES ── */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:700;letter-spacing:.2px}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7}
        .badge-green{background:#d1fae5;color:#065f46}
        .badge-red{background:#fee2e2;color:#991b1b}
        .badge-blue{background:#dbeafe;color:#1d4ed8}
        .badge-yellow{background:#fef3c7;color:#92400e}
        .badge-purple{background:#ede9fe;color:#5b21b6}

        /* ── FORMS ── */
        .form-group{margin-bottom:22px}
        .form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:7px}
        .form-label .req{color:var(--p)}
        .form-control{width:100%;padding:12px 16px;border:1.5px solid #e5e7eb;border-radius:12px;font-size:14px;color:#1e293b;outline:none;transition:all .2s;font-family:'DM Sans',sans-serif;background:#fff}
        .form-control:focus{border-color:var(--p);box-shadow:0 0 0 3px rgba(124,58,237,.1)}
        .form-control[type=color]{height:48px;padding:6px 10px;cursor:pointer}
        textarea.form-control{resize:vertical;min-height:130px}
        select.form-control{cursor:pointer}
        .form-hint{font-size:12px;color:#94a3b8;margin-top:5px}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
        .form-check{display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:500;color:#374151;padding:3px 0}
        .form-check input[type=checkbox]{width:18px;height:18px;border-radius:5px;accent-color:var(--p);cursor:pointer}
        .form-section{border:1px solid #e8ecf0;border-radius:14px;padding:24px;margin-bottom:24px}
        .form-section-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#1e293b;margin-bottom:18px;display:flex;align-items:center;gap:8px}
        .img-preview{width:100px;height:100px;border-radius:12px;object-fit:cover;border:2px solid #e8ecf0;margin-top:8px}
        .divider{border:none;border-top:1px solid #f1f5f9;margin:24px 0}

        /* ── COLOR PICKERS ── */
        .color-row{display:flex;flex-direction:column;gap:6px}
        .color-display{width:100%;height:44px;border-radius:10px;border:2px solid #e8ecf0;cursor:pointer;transition:border-color .2s}
        .color-display:hover{border-color:var(--p)}
        .color-input-wrap{display:flex;align-items:center;gap:10px}
        .color-swatch{width:36px;height:36px;border-radius:8px;border:2px solid #e8ecf0;flex-shrink:0}

        /* ── PAGINATION ── */
        .pagination-wrap{margin-top:20px;display:flex;justify-content:center}

        /* ── EMPTY STATE ── */
        .empty-state{text-align:center;padding:60px 20px;color:#94a3b8}
        .empty-state-icon{font-size:3.5rem;margin-bottom:14px;display:block}
        .empty-state p{font-size:15px;margin-bottom:20px}

        /* ── ACTIVITY ── */
        .activity-list{display:flex;flex-direction:column;gap:0}
        .activity-item{display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid #f8fafc}
        .activity-item:last-child{border-bottom:none}
        .activity-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
        .activity-text{font-size:14px;color:#334155;line-height:1.4}
        .activity-time{font-size:12px;color:#94a3b8;margin-top:2px}

        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);transition:transform .3s}
            .sidebar.open{transform:none}
            .main{margin-left:0}
            .topbar{left:0}
            .form-grid,.form-grid-3{grid-template-columns:1fr}
            .stats-row{grid-template-columns:1fr 1fr}
            .content{padding:20px 16px}
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-brand">
            <div class="brand-dot">⚡</div>
            <div>
                <div class="brand-name">SK Admin</div>
                <span class="brand-sub">Content Management</span>
            </div>
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
            <div class="nav-item-icon">⚡</div> About Us
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
        <!-- <a href="{{ route('admin.theme') }}" class="nav-item {{ request()->routeIs('admin.theme') ? 'active' : '' }}">
            <div class="nav-item-icon">🎨</div> Theme & Colors
        </a> -->
        <a href="{{ route('admin.seo.index') }}" class="nav-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
            <div class="nav-item-icon">🔍</div> SEO Manager
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
        <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:10px">
            @csrf
            <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;font-size:13px">
                🚪 Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- TOPBAR --}}
<header class="topbar">
    <div>
        <div class="page-title">@yield('title', 'Dashboard')</div>
        <div class="breadcrumbs">Admin Panel / @yield('title', 'Dashboard')</div>
    </div>
    <div class="topbar-right">
        <a href="{{ route('home') }}" target="_blank" class="view-site-btn">
            🌐 View Site
        </a>
    </div>
</header>

{{-- MAIN --}}
<main class="main">
    <div class="content">
        @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<div>⚠️ {{ $e }}</div>@endforeach
        </div>
        @endif
        @yield('content')
    </div>
</main>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', () => {
    // auto-close mobile nav on link click
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('open');
            }
        });
    });
});
</script>
@yield('scripts')
</body>
</html>