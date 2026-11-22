<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | LivejobsBD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #0f1f3d;
            --navy-deep: #0a1730;
            --navy-soft: #16294f;
            --gold: #cf9d3d;
            --gold-soft: #e8c778;
            --mint: #2ec4b6;
            --mint-soft: #d6f5f2;
            --ink: #1c2530;
            --paper: #f6f7fa;
            --line: #e4e7ee;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--paper);
            color: var(--ink);
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        h1, h2, h3, h4, .brand {
            font-family: 'Fraunces', serif;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        /* ---------- Sidebar ---------- */
        .sidebar {
            width: 264px;
            flex-shrink: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--navy) 0%, var(--navy-deep) 100%);
            color: #cfd6e4;
            padding: 28px 18px;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 600;
            letter-spacing: .3px;
            margin-bottom: 4px;
        }

        .sidebar .brand span { color: var(--gold-soft); }

        .sidebar .sub {
            font-size: .74rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6f7d99;
            margin-bottom: 28px;
        }

        .nav-group-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: #5b6a87;
            margin: 18px 10px 8px;
        }

        .sidebar a.nav-link {
            color: #b9c2d6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 3px;
            font-size: .92rem;
            font-weight: 500;
            transition: background .15s ease, color .15s ease;
        }

        .sidebar a.nav-link i { width: 18px; text-align: center; color: #7e8bab; }

        .sidebar a.nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .sidebar a.nav-link.active {
            background: rgba(46, 196, 182, .14);
            color: #fff;
            box-shadow: inset 3px 0 0 var(--mint);
        }

        .sidebar a.nav-link.active i { color: var(--mint); }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-footer .admin-name {
            color: #fff;
            font-size: .88rem;
            font-weight: 600;
        }

        .sidebar-footer .admin-role {
            color: #7e8bab;
            font-size: .74rem;
            margin-bottom: 12px;
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255,80,80,.4);
            color: #ff9c9c;
            font-size: .82rem;
            font-weight: 500;
            padding: 8px;
            border-radius: 8px;
            transition: background .15s ease;
        }
        .btn-logout:hover { background: rgba(255,80,80,.12); color: #ffb3b3; }

        /* ---------- Main ---------- */
        .main {
            flex-grow: 1;
            padding: 32px 40px;
            max-width: 100%;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .topbar h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: var(--navy);
        }

        .topbar .greeting {
            font-size: .85rem;
            color: #6b7280;
            margin-top: 2px;
            font-family: 'Inter', sans-serif;
        }

        .card-soft {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .badge-gold {
            background: var(--gold-soft);
            color: #5c4200;
            font-weight: 600;
        }

        .badge-mint {
            background: var(--mint-soft);
            color: #0c6d64;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .sidebar { position: fixed; left: -264px; z-index: 1050; transition: left .2s ease; height: 100vh; }
            .sidebar.open { left: 0; }
            .main { padding: 20px; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <div class="sidebar" id="adminSidebar">
        <div class="brand">Livejobs<span>BD</span></div>
        <div class="sub">Admin Panel</div>

        <div class="nav-group-label">সাধারণ</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i> ড্যাশবোর্ড
        </a>

        <div class="nav-group-label">জব ম্যানেজমেন্ট</div>
        <a href="{{ route('admin.pending.jobs') }}" class="nav-link {{ request()->routeIs('admin.pending.jobs') ? 'active' : '' }}">
            <i class="fa-solid fa-hourglass-half"></i> পেন্ডিং জবস
        </a>
        <a href="{{ route('admin.active.jobs') }}" class="nav-link {{ request()->routeIs('admin.active.jobs') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-check"></i> অ্যাক্টিভ জবস
        </a>
        <a href="{{ route('admin.trashed.jobs') }}" class="nav-link {{ request()->routeIs('admin.trashed.jobs') ? 'active' : '' }}">
            <i class="fa-solid fa-trash"></i> ট্র্যাশ
        </a>

        <div class="nav-group-label">লোকেশন</div>
        <a href="{{ route('admin.country.index') }}" class="nav-link {{ request()->routeIs('admin.country.*') ? 'active' : '' }}">
            <i class="fa-solid fa-earth-asia"></i> Country
        </a>
        <a href="{{ route('admin.state.index') }}" class="nav-link {{ request()->routeIs('admin.state.*') ? 'active' : '' }}">
            <i class="fa-solid fa-map"></i> State
        </a>

        <a href="{{ route('admin.district.index') }}" class="nav-link {{ request()->routeIs('admin.district.*') ? 'active' : '' }}">
            <i class="fa-solid fa-map"></i> District
        </a>

        <a href="{{ route('admin.city.index') }}" class="nav-link {{ request()->routeIs('admin.city.*') ? 'active' : '' }}">
            <i class="fa-solid fa-city"></i> City
        </a>

        <a href="{{ route('admin.companies.index') }}" class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
            <i class="fa-solid fa-city"></i> Company
        </a>

        <div class="nav-group-label">সেটিংস</div>
        <a href="{{ route('admin.categories.list') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> ক্যাটাগরি
        </a>
        <a href="#" class="nav-link"><i class="fa-solid fa-users"></i> ইউজারস</a>
        <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear"></i> প্রোফাইল
        </a>

        <div class="sidebar-footer">
            <div class="admin-name">{{ Auth::guard('admin')->user()->name }}</div>
            <div class="admin-role">অ্যাডমিনিস্ট্রেটর</div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> লগআউট
                </button>
            </form>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <div>
                <h2>@yield('page-title', 'ড্যাশবোর্ড')</h2>
                <div class="greeting">@yield('page-subtitle', 'স্বাগতম, ' . Auth::guard('admin')->user()->name)</div>
            </div>
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>