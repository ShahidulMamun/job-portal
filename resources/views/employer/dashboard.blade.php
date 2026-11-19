<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivejobsBD - Employee Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- ══ Inline script BEFORE body renders — kills flash ══ --}}
    <script>
        (function () {
            var saved = localStorage.getItem('activeTab');
            if (saved) {
                document.documentElement.setAttribute('data-tab', saved);
            }
        })();
    </script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f8f8;
            color: #444;
        }

        /* ── Tab Flash Prevention ── */
        .tab-panel { display: none; animation: fadeIn 0.3s ease; }
        .tab-panel.active { display: block; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            width: 260px;
            height: 100vh;
            background: #12213f;
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: width 0.3s;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px; height: 42px;
            background: #1fc9bc;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            font-weight: 700; flex-shrink: 0;
        }

        .brand-text { font-size: 20px; font-weight: 700; }

        .sidebar-nav { padding: 20px 0; flex: 1; }

        .nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.45);
            padding: 10px 20px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
            border-left: 3px solid transparent;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            background: none;
            border-top: none;
            border-right: none;
            border-bottom: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        .nav-item i { width: 20px; text-align: center; font-size: 16px; }

        .nav-item:hover, .nav-item.active {
            background: rgba(31, 201, 188,0.15);
            color: #1fc9bc;
            border-left-color: #1fc9bc;
        }

        .badge {
            margin-left: auto;
            background: #1fc9bc;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px; height: 42px;
            background: #1fc9bc;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: 16px;
            flex-shrink: 0;
        }

        .user-name { font-size: 14px; font-weight: 600; }
        .user-role { font-size: 12px; color: rgba(255,255,255,0.55); }

        /* ── Main Content ── */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s;
        }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            padding: 0 30px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            position: sticky;
            top: 0; z-index: 90;
        }

        .topbar-left { display: flex; align-items: center; gap: 15px; }

        .hamburger {
            font-size: 20px;
            cursor: pointer;
            color: #12213f;
            display: none;
        }

        .page-title { font-size: 20px; font-weight: 700; color: #12213f; }

        .topbar-right { display: flex; align-items: center; gap: 20px; }

        .topbar-btn {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #f3f8f8;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            position: relative;
            color: #12213f;
            transition: all 0.2s;
            border: none;
        }

        .topbar-btn:hover { background: #1fc9bc; color: #fff; }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #e74c3c;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer;
        }

        .topbar-avatar {
            width: 38px; height: 38px;
            background: #12213f;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #1fc9bc; font-weight: 700; font-size: 14px;
        }

        /* ── Page Content ── */
        .content { padding: 30px; flex: 1; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Welcome Banner ── */
        .welcome-banner {
            background: linear-gradient(135deg, #12213f 0%, #1b2f57 100%);
            color: #fff;
            border-radius: 12px;
            padding: 30px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -30px; top: -30px;
            width: 180px; height: 180px;
            background: rgba(31, 201, 188,0.15);
            border-radius: 50%;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            right: 60px; bottom: -50px;
            width: 130px; height: 130px;
            background: rgba(31, 201, 188,0.08);
            border-radius: 50%;
        }

        .welcome-banner h2 { font-size: 24px; margin-bottom: 6px; }
        .welcome-banner p { color: rgba(255,255,255,0.8); font-size: 15px; }

        .profile-complete {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px 20px;
            text-align: center;
            z-index: 1;
        }

        .profile-complete h4 { font-size: 13px; color: rgba(255,255,255,0.8); margin-bottom: 8px; }

        .progress-ring {
            width: 70px; height: 70px;
            border-radius: 50%;
            background: conic-gradient(#1fc9bc 75%, rgba(255,255,255,0.2) 0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 5px;
            position: relative;
        }

        .progress-ring::before {
            content: '';
            position: absolute;
            width: 52px; height: 52px;
            background: #162a4d;
            border-radius: 50%;
        }

        .progress-ring span { position: relative; z-index: 1; font-size: 14px; font-weight: 700; }

        /* ── Stat Cards ── */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 55px; height: 55px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }

        .icon-blue   { background: #e8ecff; color: #3d5aff; }
        .icon-yellow { background: #d5f6f3; color: #12a79b; }
        .icon-green  { background: #e8f5e9; color: #2e7d32; }
        .icon-red    { background: #fce4ec; color: #c62828; }
        .icon-purple { background: #f3e5f5; color: #6a1b9a; }

        .stat-info h3 { font-size: 26px; font-weight: 700; color: #12213f; }
        .stat-info p  { font-size: 13px; color: #888; margin-top: 2px; }

        /* ── Grid Layout ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 25px; margin-bottom: 25px; }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-header h3 { font-size: 16px; font-weight: 700; color: #12213f; }

        .card-body { padding: 20px 25px; }

        .view-all {
            font-size: 13px; color: #12a79b; font-weight: 600;
            text-decoration: none;
        }

        /* ── App Items ── */
        .app-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .app-item:last-child { border-bottom: none; }

        .app-logo {
            width: 46px; height: 46px;
            border-radius: 10px;
            background: #f3f8f8;
            border: 1px solid #eee;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #12213f; flex-shrink: 0;
        }

        .app-info { flex: 1; }
        .app-info h4 { font-size: 15px; font-weight: 600; color: #12213f; margin-bottom: 3px; }
        .app-info p  { font-size: 13px; color: #888; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pending   { background: #fff8e1; color: #f57c00; }
        .status-reviewed  { background: #e3f2fd; color: #1565c0; }
        .status-interview { background: #f3e5f5; color: #6a1b9a; }
        .status-offered   { background: #e8f5e9; color: #2e7d32; }
        .status-rejected  { background: #fce4ec; color: #c62828; }

        /* ── Timeline ── */
        .timeline { list-style: none; }
        .timeline-item {
            display: flex;
            gap: 15px;
            padding-bottom: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 17px; top: 34px;
            width: 2px;
            height: calc(100% - 20px);
            background: #f0f0f0;
        }

        .timeline-item:last-child::before { display: none; }

        .t-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #e8ecff;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #12213f; flex-shrink: 0;
        }

        .t-content h4 { font-size: 14px; font-weight: 600; color: #12213f; margin-bottom: 3px; }
        .t-content p  { font-size: 12px; color: #888; }
        .t-time        { font-size: 11px; color: #bbb; margin-top: 3px; }

        /* ── Saved Cards ── */
        .saved-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

        .saved-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 18px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .saved-card:hover { border-color: #1fc9bc; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .saved-card h4 { font-size: 15px; font-weight: 600; color: #12213f; margin-bottom: 5px; }
        .saved-card .company { font-size: 13px; color: #888; margin-bottom: 10px; }
        .saved-meta { display: flex; gap: 10px; flex-wrap: wrap; }
        .saved-meta span { font-size: 12px; color: #888; display: flex; align-items: center; gap: 4px; }
        .saved-meta i { color: #12a79b; }

        /* ── Profile ── */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 25px;
            padding: 30px 25px;
            background: linear-gradient(135deg, #12213f, #1b2f57);
            color: #fff;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .profile-big-avatar {
            width: 90px; height: 90px;
            background: #1fc9bc;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; font-weight: 700; color: #fff;
            border: 4px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }

        .profile-meta h2 { font-size: 22px; font-weight: 700; margin-bottom: 5px; }
        .profile-meta p  { color: rgba(255,255,255,0.8); font-size: 14px; }

        .profile-edit-btn {
            margin-left: auto;
            background: #1fc9bc;
            color: #fff;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .profile-edit-btn:hover { background: #12a79b; }

        /* ── Form ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group.full { grid-column: span 2; }

        .form-group label {
            display: block; font-size: 13px; font-weight: 600; color: #555;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 11px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            color: #444;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #1fc9bc;
            box-shadow: 0 0 0 3px rgba(31, 201, 188,0.15);
        }

        .save-btn {
            background: #12213f;
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            margin-top: 20px;
            transition: all 0.2s;
            font-family: inherit;
            font-size: 14px;
        }

        .save-btn:hover { background: #1fc9bc; color: #fff; }

        /* ── Progress Bars ── */
        .progress-item { margin-bottom: 18px; }
        .progress-item h4 {
            font-size: 14px; font-weight: 600; color: #12213f;
            margin-bottom: 8px; display: flex; justify-content: space-between;
        }
        .progress-bar { height: 8px; background: #f0f0f0; border-radius: 5px; overflow: hidden; }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #1b2f57, #1fc9bc);
            border-radius: 5px;
        }

        .section-label {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #bbb;
            margin-bottom: 18px;
            margin-top: 8px;
        }

        /* ── Logout Form ── */
        .logout-form {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 1100px) {
            .stat-cards { grid-template-columns: repeat(2, 1fr); }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .saved-grid { grid-column: span 1; grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: span 1; }
        }

        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.open { left: 0; }
            .main { margin-left: 0; }
            .hamburger { display: block; }
            .stat-cards { grid-template-columns: 1fr 1fr; }
            .welcome-banner { flex-direction: column; gap: 20px; }
            .profile-complete { width: 100%; }
            .profile-header { flex-direction: column; text-align: center; }
            .profile-edit-btn { margin: 0 auto; }
        }

        /* style for photo upload card desing  */
   
        .upload-card { background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); padding: 1.5rem; max-width: 420px; margin: 1rem auto; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .card-title { font-size: 15px; font-weight: 500; color: var(--color-text-primary); margin: 0; }
        .badge { font-size: 11px; padding: 3px 10px; border-radius: 99px; background: var(--color-background-success); color: var(--color-text-success); font-weight: 500; }
        .drop-zone { border: 1.5px dashed var(--color-border-secondary); border-radius: var(--border-radius-md); padding: 2rem 1rem; text-align: center; cursor: pointer; transition: background 0.2s, border-color 0.2s; position: relative; }
        .drop-zone:hover, .drop-zone.dragging { background: var(--color-background-secondary); border-color: var(--color-border-primary); }
        .drop-icon { width: 40px; height: 40px; margin: 0 auto 0.75rem; border-radius: 50%; background: var(--color-background-secondary); display: flex; align-items: center; justify-content: center; }
        .drop-icon svg { width: 20px; height: 20px; stroke: var(--color-text-secondary); fill: none; stroke-width: 1.5; }
        .drop-label { font-size: 14px; color: var(--color-text-primary); font-weight: 500; margin-bottom: 4px; }
        .drop-sub { font-size: 12px; color: var(--color-text-secondary); }
        .browse-link { color: var(--color-text-info); text-decoration: underline; cursor: pointer; }
        .hidden-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .preview-area { display: none; margin-top: 1rem; align-items: center; gap: 12px; padding: 0.75rem; background: var(--color-background-secondary); border-radius: var(--border-radius-md); }
        .preview-thumb { width: 52px; height: 52px; border-radius: var(--border-radius-md); object-fit: cover; border: 0.5px solid var(--color-border-tertiary); flex-shrink: 0; }
        .preview-info { flex: 1; min-width: 0; }
        .preview-name { font-size: 13px; font-weight: 500; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .preview-size { font-size: 12px; color: var(--color-text-secondary); margin-top: 2px; }
        .remove-btn { width: 28px; height: 28px; border-radius: 50%; border: 0.5px solid var(--color-border-tertiary); background: var(--color-background-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .remove-btn svg { width: 14px; height: 14px; stroke: var(--color-text-secondary); fill: none; stroke-width: 2; }
        .progress-bar { height: 3px; background: var(--color-border-tertiary); border-radius: 99px; margin-top: 0.75rem; overflow: hidden; display: none; }
        .progress-fill { height: 100%; width: 0; background: var(--color-text-info); border-radius: 99px; transition: width 0.3s ease; }
        .footer { margin-top: 1.25rem; display: flex; gap: 8px; }
        .btn-cancel { flex: 1; padding: 8px; font-size: 13px; border-radius: var(--border-radius-md); border: 0.5px solid var(--color-border-secondary); background: transparent; color: var(--color-text-secondary); cursor: pointer; }
        .btn-upload { flex: 2; padding: 8px; font-size: 13px; font-weight: 500; border-radius: var(--border-radius-md); border: none; background: var(--color-text-primary); color: var(--color-background-primary); cursor: pointer; opacity: 0.4; transition: opacity 0.2s; }
        .btn-upload.active { opacity: 1; }
        .requirements { margin-top: 1rem; }
        .req-row { display: flex; gap: 12px; }
        .req-item { flex: 1; font-size: 11px; color: var(--color-text-secondary); display: flex; align-items: center; gap: 4px; }
        .req-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--color-border-secondary); flex-shrink: 0; }

        /* style for photo upload card design  */
    </style>
     @stack('styles')
</head>
<body>

<!-- ═══════ SIDEBAR ═══════ -->
@include('employer.sidebar.index')
<!-- ═══════ MAIN ═══════ -->
<div class="main">

    <!-- Topbar -->
@include('employer.topbar.index')

    <!-- Content -->
    <div class="content">

        <!-- ─── DASHBOARD ─── -->
        <div class="tab-panel" id="tab-dashboard">
            <div class="welcome-banner">
                <div>
                    <h2>Welcome back, {{Auth::guard('employer')->user()->name}}! 👋</h2>
                    <p>You have <strong>3 new</strong> job matches and <strong>2 interview</strong> invites waiting.</p>
                </div>
                <div class="profile-complete">
                    <h4>Profile Complete</h4>
                    <div class="progress-ring"><span>75%</span></div>
                    <small style="color:rgba(255,255,255,0.7);font-size:11px;">Complete your profile</small>
                </div>
            </div>

            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-icon icon-blue"><i class="fas fa-paper-plane"></i></div>
                    <div class="stat-info"><h3>24</h3><p>Total Applications</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-yellow"><i class="fas fa-eye"></i></div>
                    <div class="stat-info"><h3>12</h3><p>Profile Views</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-purple"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info"><h3>2</h3><p>Interviews Scheduled</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fas fa-bookmark"></i></div>
                    <div class="stat-info"><h3>8</h3><p>Saved Jobs</p></div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Applications</h3>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="app-item">
                            <div class="app-logo"><i class="fas fa-code"></i></div>
                            <div class="app-info">
                                <h4>Senior Frontend Developer</h4>
                                <p>TechCorp Solutions · New York</p>
                            </div>
                            <span class="status-badge status-interview">Interview</span>
                        </div>
                        <div class="app-item">
                            <div class="app-logo"><i class="fas fa-paint-brush"></i></div>
                            <div class="app-info">
                                <h4>UI/UX Designer</h4>
                                <p>Creative Studios · Remote</p>
                            </div>
                            <span class="status-badge status-reviewed">Reviewed</span>
                        </div>
                        <div class="app-item">
                            <div class="app-logo"><i class="fas fa-mobile-alt"></i></div>
                            <div class="app-info">
                                <h4>Mobile Developer</h4>
                                <p>AppWorks Studio · Toronto</p>
                            </div>
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                        <div class="app-item">
                            <div class="app-logo"><i class="fas fa-server"></i></div>
                            <div class="app-info">
                                <h4>DevOps Engineer</h4>
                                <p>CloudSystems Ltd · Sydney</p>
                            </div>
                            <span class="status-badge status-offered">Offered</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3>Recent Activity</h3></div>
                    <div class="card-body">
                        <ul class="timeline">
                            <li class="timeline-item">
                                <div class="t-icon icon-purple"><i class="fas fa-calendar"></i></div>
                                <div class="t-content">
                                    <h4>Interview Scheduled</h4>
                                    <p>TechCorp Solutions — Frontend Developer</p>
                                    <div class="t-time">Tomorrow, 10:00 AM</div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="t-icon icon-green"><i class="fas fa-check-circle"></i></div>
                                <div class="t-content">
                                    <h4>Application Reviewed</h4>
                                    <p>Creative Studios — UI/UX Designer</p>
                                    <div class="t-time">Yesterday, 3:45 PM</div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="t-icon icon-blue"><i class="fas fa-paper-plane"></i></div>
                                <div class="t-content">
                                    <h4>Application Submitted</h4>
                                    <p>AppWorks Studio — Mobile Developer</p>
                                    <div class="t-time">2 days ago</div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="t-icon icon-yellow"><i class="fas fa-bookmark"></i></div>
                                <div class="t-content">
                                    <h4>Job Saved</h4>
                                    <p>DataTech Analytics — Data Scientist</p>
                                    <div class="t-time">3 days ago</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom:25px">
                <div class="card-header">
                    <h3>Recommended For You</h3>
                    <a href="#" class="view-all">View All</a>
                </div>
                <div class="card-body">
                    <div class="grid-3" style="margin-bottom:0">
                        <div class="saved-card">
                            <h4>React Developer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>NexTech Labs</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>London</span>
                                <span><i class="fas fa-clock"></i>Full Time</span>
                                <span><i class="fas fa-dollar-sign"></i>$90k+</span>
                            </div>
                        </div>
                        <div class="saved-card">
                            <h4>Vue.js Engineer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>WebSoft Inc</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>Remote</span>
                                <span><i class="fas fa-clock"></i>Full Time</span>
                                <span><i class="fas fa-dollar-sign"></i>$85k+</span>
                            </div>
                        </div>
                        <div class="saved-card">
                            <h4>Node.js Backend Dev</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>CloudBase Co</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>Berlin</span>
                                <span><i class="fas fa-clock"></i>Contract</span>
                                <span><i class="fas fa-dollar-sign"></i>$80k+</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── APPLICATIONS ─── -->
        <div class="tab-panel" id="tab-applications">
            <div class="card">
                <div class="card-header">
                    <h3>All Applications (24)</h3>
                    <select class="form-control" style="width:auto;padding:6px 12px">
                        <option>All Status</option>
                        <option>Pending</option>
                        <option>Reviewed</option>
                        <option>Interview</option>
                        <option>Offered</option>
                        <option>Rejected</option>
                    </select>
                </div>
                <div class="card-body">
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-code"></i></div>
                        <div class="app-info"><h4>Senior Frontend Developer</h4><p>TechCorp Solutions · New York · Applied 3 days ago</p></div>
                        <span class="status-badge status-interview">Interview</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-paint-brush"></i></div>
                        <div class="app-info"><h4>UI/UX Designer</h4><p>Creative Studios · Remote · Applied 5 days ago</p></div>
                        <span class="status-badge status-reviewed">Reviewed</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-mobile-alt"></i></div>
                        <div class="app-info"><h4>Mobile App Developer</h4><p>AppWorks Studio · Toronto · Applied 1 week ago</p></div>
                        <span class="status-badge status-pending">Pending</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-server"></i></div>
                        <div class="app-info"><h4>DevOps Engineer</h4><p>CloudSystems Ltd · Sydney · Applied 1 week ago</p></div>
                        <span class="status-badge status-offered">Offered</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-database"></i></div>
                        <div class="app-info"><h4>Data Scientist</h4><p>DataTech Analytics · Berlin · Applied 2 weeks ago</p></div>
                        <span class="status-badge status-rejected">Rejected</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── SAVED ─── -->
        <div class="tab-panel" id="tab-saved">
            <div class="card">
                <div class="card-header"><h3>Saved Jobs (8)</h3></div>
                <div class="card-body">
                    <div class="saved-grid">
                        <div class="saved-card">
                            <h4>React Developer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>NexTech Labs</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>London</span>
                                <span><i class="fas fa-clock"></i>Full Time</span>
                                <span><i class="fas fa-dollar-sign"></i>$90k-$110k</span>
                            </div>
                        </div>
                        <div class="saved-card">
                            <h4>Vue.js Engineer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>WebSoft Inc</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>Remote</span>
                                <span><i class="fas fa-clock"></i>Full Time</span>
                                <span><i class="fas fa-dollar-sign"></i>$85k-$100k</span>
                            </div>
                        </div>
                        <div class="saved-card">
                            <h4>Node.js Developer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>CloudBase Co</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>Berlin</span>
                                <span><i class="fas fa-clock"></i>Contract</span>
                                <span><i class="fas fa-dollar-sign"></i>$80k-$95k</span>
                            </div>
                        </div>
                        <div class="saved-card">
                            <h4>Full Stack Developer</h4>
                            <div class="company"><i class="fas fa-building" style="color:#12a79b;margin-right:5px"></i>DigitalMind</div>
                            <div class="saved-meta">
                                <span><i class="fas fa-map-marker-alt"></i>Toronto</span>
                                <span><i class="fas fa-clock"></i>Full Time</span>
                                <span><i class="fas fa-dollar-sign"></i>$95k-$120k</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── RECOMMENDED ─── -->
        <div class="tab-panel" id="tab-recommended">
            <div class="card">
                <div class="card-header"><h3>Recommended Jobs for You</h3></div>
                <div class="card-body">
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-laptop-code"></i></div>
                        <div class="app-info"><h4>React Developer — NexTech Labs</h4><p>London · Full Time · $90k-$110k</p></div>
                        <button class="save-btn" style="margin-top:0;padding:8px 18px;font-size:13px">Apply</button>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-code-branch"></i></div>
                        <div class="app-info"><h4>Vue.js Engineer — WebSoft Inc</h4><p>Remote · Full Time · $85k-$100k</p></div>
                        <button class="save-btn" style="margin-top:0;padding:8px 18px;font-size:13px">Apply</button>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-server"></i></div>
                        <div class="app-info"><h4>Node.js Developer — CloudBase Co</h4><p>Berlin · Contract · $80k-$95k</p></div>
                        <button class="save-btn" style="margin-top:0;padding:8px 18px;font-size:13px">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── PROFILE ─── -->
        <div class="tab-panel" id="tab-profile">
            <div class="profile-header">
                <div class="profile-big-avatar"><img style="height:90px;width: 90px;border-radius: 50px;" src="{{ asset('storage/' . Auth::guard('employer')->user()->photo) }}"></div>
                <div class="profile-meta">
                    <h2>{{auth::guard('employer')->user()->name}}</h2>
                    <p>Senior Web Developer</p>
                    <p style="margin-top:5px"><i class="fas fa-map-marker-alt"></i> New York, USA</p>
                </div>
                <button class="profile-edit-btn"><i class="fas fa-edit"></i> Edit Profile</button>
            </div>
            <div class="grid-2">
                {{-- profile card --}}
                <div class="card">
                    <div class="card-header"><h3>Personal Information</h3></div>
                    <div class="card-body">
                     <form action="{{ route('employer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" value="{{Auth::guard('employer')->user()->name}}">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" value="{{Auth::guard('employer')->user()->email}}">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control" name="phone" value="{{Auth::guard('employer')->user()->phone}}">
                            </div>

                             <div class="form-group">
                                <label>Designation</label>
                                <input class="form-control" name="designation" value="{{Auth::guard('employer')->user()->designation}}">
                            </div>

                            <div class="form-group">
                                <label>Company</label>
                                <input class="form-control" name="company_name" value="{{Auth::guard('employer')->user()->company_name}}">
                            </div>

                            <div class="form-group">
                                <label>Location</label>
                                <input class="form-control" name="company_address" value="{{Auth::guard('employer')->user()->company_address}}">
                            </div>
                              
                            
                            <div class="form-group">
                                <label>Website</label>
                                <input type="url" class="form-control" name="company_website" value="{{Auth::guard('employer')->user()->company_website}}">
                            </div>
                            <div class="form-group full">
                                <label>Company Bio {{-- company details --}} </label>
                                <textarea class="form-control" name="company_description" rows="3">{{Auth::guard('employer')->user()->company_description}} </textarea>
                            </div>
                        </div>
                        <button class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
                    </form>
                    </div>
                </div>
                {{-- password card --}}

                    <div class="card">
                    <div class="card-header"><h3>Update Password</h3></div>
                    <div class="card-body">
                         <form action="{{ route('employer.password.update') }}" method="POST">
                               @csrf
                               @method('PUT')
                             <div class="form-grid">
                                <div class="form-group">
                                    <label>Old</label>
                                    <input type="password" name="current_password" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" name="email" value="">
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" name="email" value="">
                                </div>
                            
                             </div>
                                <button class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
                         </form>
                     
                    </div>
                </div>


                <div class="card">
                    <div class="card-header"><h3>Profile Photo</h3></div>
                    <div class="card-body">
                        <!-- Photo Upload Card -->
                        <form action="{{route('employer.photo.upload')}}" method="post" enctype="multipart/form-data">
                          @csrf
                            <div class="upload-card">
                            <div class="drop-zone" id="dropZone">
                                <input type="file" name="photo" class="hidden-input" id="fileInput" accept="image/*" />
                                <div class="drop-icon">
                                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <p class="drop-label">Drop photo here or <span class="browse-link">browse</span></p>
                                <p class="drop-sub">JPG, PNG or WEBP — max 5MB</p>
                            </div>

                            <div class="progress-bar" id="progressBar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>

                            <div class="preview-area" id="previewArea">
                                <img class="preview-thumb" id="previewThumb" src="" alt="preview" />
                                <div class="preview-info">
                                <p class="preview-name" id="previewName">photo.jpg</p>
                                <p class="preview-size" id="previewSize">2.4 MB</p>
                                </div>
                                <button class="remove-btn" id="removeBtn">
                                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>

                        

                            <div class="footer">
                               
                                <button class="btn btn-success btn-upload" id="uploadBtn">Upload photo</button>
                            </div>
                        </div>

                      </form>
                        <button class="btn-cancel" id="cancelBtn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── RESUME ─── -->
        <div class="tab-panel" id="tab-resume">
            <div class="card">
                <div class="card-header"><h3>Upload CV / Resume</h3></div>
                <div class="card-body">
                    <div style="border:2px dashed #1fc9bc;border-radius:12px;padding:50px;text-align:center;cursor:pointer">
                        <i class="fas fa-cloud-upload-alt" style="font-size:48px;color:#1fc9bc;margin-bottom:15px;display:block"></i>
                        <h4 style="color:#12213f;margin-bottom:8px">Drag & Drop your CV here</h4>
                        <p style="color:#888;font-size:14px">Supports PDF, DOC, DOCX (Max 5MB)</p>
                        <button class="save-btn" style="margin-top:20px"><i class="fas fa-upload"></i> Browse File</button>
                    </div>
                    <div class="section-label" style="margin-top:25px">Uploaded Files</div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-file-pdf" style="color:#e53935"></i></div>
                        <div class="app-info"><h4>John_Smith_CV.pdf</h4><p>2.4 MB · Uploaded 5 days ago</p></div>
                        <button class="save-btn" style="margin-top:0;padding:8px 18px;font-size:13px"><i class="fas fa-download"></i> Download</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── ALERTS ─── -->
        <div class="tab-panel" id="tab-alerts">
            <div class="card">
                <div class="card-header">
                    <h3>Job Alerts (3 Active)</h3>
                    <button class="save-btn" style="margin-top:0;padding:8px 18px;font-size:13px"><i class="fas fa-plus"></i> Create Alert</button>
                </div>
                <div class="card-body">
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-bell" style="color:#1fc9bc"></i></div>
                        <div class="app-info"><h4>Frontend Developer — React, JavaScript</h4><p>New York · Full Time · Daily alerts</p></div>
                        <span class="status-badge status-offered">Active</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-bell" style="color:#1fc9bc"></i></div>
                        <div class="app-info"><h4>UI/UX Designer — Remote</h4><p>Worldwide · Remote · Weekly alerts</p></div>
                        <span class="status-badge status-offered">Active</span>
                    </div>
                    <div class="app-item">
                        <div class="app-logo"><i class="fas fa-bell" style="color:#bbb"></i></div>
                        <div class="app-info"><h4>Full Stack Developer — Node.js</h4><p>London · Any · Paused</p></div>
                        <span class="status-badge status-pending">Paused</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── SETTINGS ─── -->
        <div class="tab-panel" id="tab-settings">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><h3>Account Settings</h3></div>
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom:18px">
                            <label>Email</label>
                            <input class="form-control" value="john@email.com">
                        </div>
                        <div class="form-group" style="margin-bottom:18px"><label>New Password</label><input class="form-control" type="password" placeholder="New password"></div>
                        <div class="form-group" style="margin-bottom:18px"><label>Confirm Password</label><input class="form-control" type="password" placeholder="Confirm password"></div>
                        <button class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h3>Notification Settings</h3></div>
                    <div class="card-body">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f5f5f5">
                            <div><div style="font-weight:600;color:#12213f;font-size:14px">Email Notifications</div><div style="font-size:12px;color:#888">Job alerts via email</div></div>
                            <input type="checkbox" checked style="accent-color:#12a79b;width:18px;height:18px">
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f5f5f5">
                            <div><div style="font-weight:600;color:#12213f;font-size:14px">Application Updates</div><div style="font-size:12px;color:#888">Status changes</div></div>
                            <input type="checkbox" checked style="accent-color:#12a79b;width:18px;height:18px">
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0">
                            <div><div style="font-weight:600;color:#12213f;font-size:14px">Profile Views</div><div style="font-size:12px;color:#888">When employers view your profile</div></div>
                            <input type="checkbox" style="accent-color:#12a79b;width:18px;height:18px">
                        </div>
                        <button class="save-btn"><i class="fas fa-save"></i> Save Preferences</button>
                    </div>
                </div>
            </div>
        </div>


         <!-- ─── Job Post ─── -->
        @include('employer.jobs.create')

        @include('employer.jobs.index')

    </div>
</div>

<script>
    // ── Tab titles ──
    var titles = {
        dashboard:    'Dashboard',
        applications: 'My Applications',
        saved:        'Saved Jobs',
        recommended:  'Recommended Jobs',
        profile:      'My Profile',
        resume:       'Resume / CV',
        alerts:       'Job Alerts',
        settings:     'Settings',
        postjob:      'Post Job',
        postedjob:    'Posted Job'
    };

    // ── Show tab ──
    function showTab(tabId, el) {
        document.querySelectorAll('.tab-panel').forEach(function(p) {
            p.classList.remove('active');
        });
        var panel = document.getElementById('tab-' + tabId);
        if (panel) panel.classList.add('active');

        document.querySelectorAll('.nav-item').forEach(function(n) {
            n.classList.remove('active');
        });
        if (el) el.classList.add('active');

        document.getElementById('pageTitle').textContent = titles[tabId] || 'Dashboard';

        // Save to localStorage
        try { localStorage.setItem('activeTab', tabId); } catch(e) {}
    }

    // ── On page load — restore saved tab ──
    (function () {
        var saved = 'dashboard';
        try { saved = localStorage.getItem('activeTab') || 'dashboard'; } catch(e) {}

        // Check if saved tab panel actually exists, fallback to dashboard
        if (!document.getElementById('tab-' + saved)) saved = 'dashboard';

        var navEl = document.querySelector('.nav-item[onclick*="' + saved + '"]');
        showTab(saved, navEl);
    })();

    // ── Sidebar toggle ──
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }

    // ── Close sidebar on outside click (mobile) ──
    document.addEventListener('click', function(e) {
        var sb = document.getElementById('sidebar');
        var hb = document.getElementById('hamburger');
        if (window.innerWidth <= 768 && sb && hb &&
            !sb.contains(e.target) && !hb.contains(e.target)) {
            sb.classList.remove('open');
        }
    });
</script>

{{-- script for photo upload card design  --}}
<script>
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const previewArea = document.getElementById('previewArea');
  const previewThumb = document.getElementById('previewThumb');
  const previewName = document.getElementById('previewName');
  const previewSize = document.getElementById('previewSize');
  const removeBtn = document.getElementById('removeBtn');
  const uploadBtn = document.getElementById('uploadBtn');
  const progressBar = document.getElementById('progressBar');
  const progressFill = document.getElementById('progressFill');
  let hasFile = false;

  function formatSize(bytes) {
    return bytes < 1024 * 1024 ? Math.round(bytes / 1024) + ' KB' : (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  }

  function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
      previewThumb.src = e.target.result;
      previewName.textContent = file.name;
      previewSize.textContent = formatSize(file.size);
      previewArea.style.display = 'flex';
      uploadBtn.classList.add('active');
      hasFile = true;
    };
    reader.readAsDataURL(file);
  }

  fileInput.addEventListener('change', e => { if (e.target.files[0]) showPreview(e.target.files[0]); });

  dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragging'); });
  dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragging'));
  dropZone.addEventListener('drop', e => {
    e.preventDefault(); dropZone.classList.remove('dragging');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) showPreview(file);
  });

  removeBtn.addEventListener('click', () => {
    previewArea.style.display = 'none';
    fileInput.value = '';
    uploadBtn.classList.remove('active');
    hasFile = false;
  });

  uploadBtn.addEventListener('click', () => {
    if (!hasFile) return;
    progressBar.style.display = 'block';
    let w = 0;
    const iv = setInterval(() => {
      w = Math.min(w + Math.random() * 18, 100);
      progressFill.style.width = Math.round(w) + '%';
      if (w >= 100) { clearInterval(iv); setTimeout(() => { progressBar.style.display = 'none'; progressFill.style.width = '0'; uploadBtn.textContent = 'Uploaded!'; }, 400); }
    }, 80);
  });

  cancelBtn.addEventListener('click', () => {
    previewArea.style.display = 'none';
    fileInput.value = '';
    uploadBtn.classList.remove('active');
    uploadBtn.textContent = 'Upload photo';
    hasFile = false;
  });
</script>

</body>
</html>