<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | LivejobsBD</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
@vite('resources/css/app.css')

<style>
  :root {
    --ink: #12213f;
    --ink-2: #1b2f57;
    --slate: #1b2f57;
    --cobalt: #3d5aff;
    --gold: #1fc9bc;
    --gold-soft: #d5f6f3;
    --mint: #2bd08f;
    --rose: #e05c6b;
    --paper: #f3f8f8;
    --paper-2: #e8f2f1;
    --ink-text: #1a2338;
    --muted: #6f7891;
    --muted-on-dark: rgba(232,234,244,0.58);
    --hairline: #dce8e7;
  }

  * { box-sizing: border-box; }
  html { margin: 0; padding: 0; width: 100%; }

  body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    color: var(--ink-text);
    background: var(--paper);
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
  }

  h1, h2, h3, .logo { font-family: 'Fraunces', serif; }
  a { text-decoration: none; }

  /* ===== LAYOUT ===== */
  .dashboard-wrap { display: grid; grid-template-columns: 264px minmax(0, 1fr); min-height: 100vh; width: 100%; }

  .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(10,17,33,0.5); z-index: 150; }
  .sidebar-overlay.active { display: block; }

  /* ===== SIDEBAR ===== */
  .sidebar {
    width: 264px; min-width: 264px; max-width: 264px; flex-shrink: 0;
    background: linear-gradient(180deg, var(--ink), var(--ink-2));
    padding: 26px 20px;
    display: flex; flex-direction: column;
    position: sticky; top: 0; height: 100vh;
    overflow-y: auto;
  }

  .sidebar-logo { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 19px; color: #fff; margin-bottom: 34px; padding: 0 6px; }
  .sidebar-logo i {
    width: 34px; height: 34px; border-radius: 9px;
    background: linear-gradient(150deg, var(--gold), #12a79b);
    color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: 15px;
  }

  .side-section-label {
    color: rgba(255,255,255,0.35);
    font-size: 10.5px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'JetBrains Mono', monospace;
    padding: 0 10px; margin: 18px 0 8px;
  }

  .side-nav { list-style: none; margin: 0; padding: 0; }
  .side-nav li { margin-bottom: 3px; }
  .side-nav a {
    display: flex; align-items: center; gap: 12px;
    color: var(--muted-on-dark);
    font-size: 14px; font-weight: 500;
    padding: 10px 12px; border-radius: 10px;
    transition: background .2s ease, color .2s ease;
    position: relative;
  }
  .side-nav a i { width: 18px; text-align: center; font-size: 15px; }
  .side-nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
  .side-nav a.active { background: rgba(31,201,188,0.14); color: var(--gold); }
  .side-nav a.active::before {
    content: ""; position: absolute; left: -20px; top: 50%; transform: translateY(-50%);
    width: 3px; height: 20px; border-radius: 3px; background: var(--gold);
  }
  .side-nav .badge-count {
    margin-left: auto; background: var(--gold); color: #fff;
    font-size: 10.5px; font-weight: 700; padding: 2px 7px; border-radius: 999px;
    font-family: 'JetBrains Mono', monospace;
  }

  .sidebar-profile {
    margin-top: auto; display: flex; align-items: center; gap: 10px;
    padding: 12px; border-radius: 12px; background: rgba(255,255,255,0.05);
  }
  .sidebar-profile .avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(150deg, var(--gold), #12a79b);
    color: #fff; font-weight: 700; font-size: 14px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    overflow: hidden;
  }
  .sidebar-profile .avatar img { width: 100%; height: 100%; object-fit: cover; }
  .sidebar-profile .info h4 { margin: 0; font-size: 13.5px; color: #fff; font-weight: 600; }
  .sidebar-profile .info span { font-size: 11.5px; color: var(--muted-on-dark); }
  .sidebar-profile .logout-link { margin-left: auto; color: var(--muted-on-dark); font-size: 14px; }
  .sidebar-profile .logout-link:hover { color: var(--rose); }

  /* ===== MAIN ===== */
  .main { min-width: 0; overflow-x: hidden; }

  .topbar {
    background: #fff; border-bottom: 1px solid var(--hairline);
    padding: 16px 32px; display: flex; align-items: center; justify-content: space-between;
    gap: 20px; position: sticky; top: 0; z-index: 50;
  }
  .topbar-heading h1 { font-size: 18px; font-weight: 700; color: var(--ink); margin: 0; }
  .topbar-heading span { font-size: 12.5px; color: var(--muted); }

  .topbar-actions { display: flex; align-items: center; gap: 12px; }
  .icon-btn {
    position: relative; width: 40px; height: 40px; border-radius: 10px;
    background: var(--paper); border: 1.5px solid var(--hairline);
    display: flex; align-items: center; justify-content: center;
    color: var(--ink); font-size: 15px; cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
  }
  .icon-btn:hover { background: #fff; border-color: var(--gold); }
  .icon-btn .dot {
    position: absolute; top: 8px; right: 9px;
    width: 7px; height: 7px; border-radius: 50%; background: var(--rose);
    border: 1.5px solid #fff;
  }
  .menu-toggle-mobile { display: none; color: var(--ink); font-size: 20px; cursor: pointer; }

  .content { padding: 30px 32px 60px; max-width: 1180px; margin: auto; }

  /* ===== WELCOME BANNER ===== */
  .welcome-card {
    position: relative;
    background: radial-gradient(ellipse 700px 260px at 90% -30%, #1f3763, var(--ink) 70%);
    border-radius: 20px;
    padding: 30px 34px;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between; gap: 24px;
    overflow: hidden;
    margin-bottom: 22px;
    flex-wrap: wrap;
  }
  .welcome-card::before {
    content: ""; position: absolute; inset: 0;
    background-image: repeating-linear-gradient(115deg, rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px, transparent 1px, transparent 60px);
  }
  .welcome-text { position: relative; z-index: 1; }
  .welcome-text h2 { margin: 0 0 6px; font-size: 22px; font-weight: 700; }
  .welcome-text p { margin: 0; color: var(--muted-on-dark); font-size: 13.5px; max-width: 480px; }

  .completion-ring-wrap {
    position: relative; z-index: 1; display: flex; align-items: center; gap: 16px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14);
    border-radius: 16px; padding: 14px 20px;
  }
  .completion-ring { position: relative; width: 62px; height: 62px; flex-shrink: 0; }
  .completion-ring svg { transform: rotate(-90deg); width: 62px; height: 62px; }
  .completion-ring .ring-bg { fill: none; stroke: rgba(255,255,255,0.15); stroke-width: 6; }
  .completion-ring .ring-fg { fill: none; stroke: var(--mint); stroke-width: 6; stroke-linecap: round; }
  .completion-ring .ring-pct {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; color: #fff;
  }
  .completion-ring-wrap .cta-text h4 { margin: 0 0 4px; font-size: 13.5px; color: #fff; }
  .completion-ring-wrap .cta-text a { font-size: 12px; color: var(--gold-soft); font-weight: 600; }

  /* ===== STAT CARDS ===== */
  .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
  .stat-card {
    background: #fff; border: 1px solid var(--hairline); border-radius: 16px;
    padding: 20px; display: flex; align-items: center; gap: 14px;
  }
  .stat-card .stat-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 17px;
  }
  .stat-card .stat-icon.cobalt { background: rgba(61,90,255,0.1); color: var(--cobalt); }
  .stat-card .stat-icon.mint { background: rgba(43,208,143,0.12); color: #0f9d79; }
  .stat-card .stat-icon.gold { background: var(--gold-soft); color: #0b7f77; }
  .stat-card .stat-icon.rose { background: rgba(224,92,107,0.12); color: var(--rose); }
  .stat-card .stat-value { font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 700; color: var(--ink); line-height: 1.1; }
  .stat-card .stat-label { font-size: 12px; color: var(--muted); margin-top: 2px; }

  /* ===== PANELS / GRID ===== */
  .dash-columns { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; align-items: start; }

  .panel {
    background: #fff; border: 1px solid var(--hairline); border-radius: 18px;
    padding: 22px 24px; margin-bottom: 20px;
  }
  .panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
  .panel-header h2 { font-size: 15.5px; font-weight: 600; color: var(--ink); margin: 0; display: flex; align-items: center; gap: 9px; }
  .panel-header h2 i { color: #12a79b; font-size: 14px; }
  .panel-header .view-all { font-size: 12.5px; font-weight: 600; color: #0b7f77; }

  /* applications table */
  .app-table { width: 100%; border-collapse: collapse; }
  .app-table th {
    text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
    color: var(--muted); font-weight: 600; padding: 0 10px 10px; border-bottom: 1px solid var(--hairline);
  }
  .app-table td { padding: 12px 10px; border-bottom: 1px solid var(--hairline); font-size: 13px; vertical-align: middle; }
  .app-table tr:last-child td { border-bottom: none; }
  .app-table .job-cell { display: flex; align-items: center; gap: 10px; }
  .app-table .job-logo {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: var(--paper-2); display: flex; align-items: center; justify-content: center;
    color: #12a79b; font-size: 13px; font-weight: 700;
  }
  .app-table .job-title { font-weight: 600; color: var(--ink); font-size: 13px; }
  .app-table .job-company { font-size: 11.5px; color: var(--muted); }
  .app-table .app-date { color: var(--muted); font-size: 12px; white-space: nowrap; }

  .status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 999px;
  }
  .status-pill.pending { background: #fdf0d3; color: #8a5c07; }
  .status-pill.reviewed { background: rgba(61,90,255,0.1); color: var(--cobalt); }
  .status-pill.shortlisted { background: rgba(43,208,143,0.12); color: #0f9d79; }
  .status-pill.rejected { background: rgba(224,92,107,0.12); color: var(--rose); }

  .empty-state { text-align: center; padding: 30px 10px; color: var(--muted); font-size: 13px; }
  .empty-state i { font-size: 26px; color: var(--hairline); display: block; margin-bottom: 10px; }

  /* recommended job cards */
  .job-rec-card {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 0; border-bottom: 1px solid var(--hairline);
  }
  .job-rec-card:last-child { border-bottom: none; padding-bottom: 0; }
  .job-rec-card .job-logo {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    background: var(--paper-2); display: flex; align-items: center; justify-content: center;
    color: #12a79b; font-size: 14px; font-weight: 700;
  }
  .job-rec-card .job-rec-info { flex: 1; min-width: 0; }
  .job-rec-card h4 { margin: 0 0 3px; font-size: 13.5px; font-weight: 600; color: var(--ink); }
  .job-rec-card .job-rec-meta { font-size: 11.5px; color: var(--muted); margin-bottom: 6px; }
  .job-rec-card .job-rec-tags { display: flex; gap: 6px; flex-wrap: wrap; }
  .job-rec-card .job-rec-tags span {
    font-size: 10.5px; font-weight: 600; padding: 3px 8px; border-radius: 999px;
    background: var(--paper-2); color: var(--muted);
  }
  .job-rec-card .save-icon { color: var(--muted); font-size: 14px; cursor: pointer; flex-shrink: 0; }
  .job-rec-card .save-icon:hover { color: var(--gold); }

  /* quick actions */
  .quick-actions { display: flex; flex-direction: column; gap: 10px; }
  .quick-action-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px; border-radius: 12px; border: 1px solid var(--hairline);
    background: var(--paper); color: var(--ink); font-size: 13px; font-weight: 600;
    transition: border-color .2s ease, background .2s ease;
  }
  .quick-action-btn:hover { border-color: var(--gold); background: #fff; }
  .quick-action-btn i { width: 20px; text-align: center; color: #12a79b; }

  /* profile checklist */
  .checklist-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 0;
    border-bottom: 1px solid var(--hairline); font-size: 13px;
  }
  .checklist-item:last-child { border-bottom: none; padding-bottom: 0; }
  .checklist-item .check-icon {
    width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 10px;
  }
  .checklist-item .check-icon.done { background: rgba(43,208,143,0.15); color: #0f9d79; }
  .checklist-item .check-icon.pending { background: var(--paper-2); color: var(--muted); border: 1.5px solid var(--hairline); }
  .checklist-item.done span { color: var(--muted); text-decoration: line-through; }

  /* ===== MOBILE ===== */
  @media (max-width: 900px) {
    .dashboard-wrap { grid-template-columns: 1fr; }
    .sidebar {
      position: fixed; left: 0; top: 0; height: 100vh; width: 264px; z-index: 200;
      transform: translateX(-100%);
      transition: transform .25s ease;
      box-shadow: 20px 0 50px rgba(0,0,0,0.3);
    }
    .sidebar.active { transform: translateX(0); }
    .menu-toggle-mobile { display: block; }
    .dash-columns { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 720px) {
    .topbar { padding: 14px 18px; }
    .content { padding: 20px 18px 40px; }
    .welcome-card { flex-direction: column; align-items: flex-start; padding: 24px 22px; }
    .completion-ring-wrap { width: 100%; }
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .panel { padding: 18px 16px; }
  }

  @media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<div class="dashboard-wrap">

  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo"><i class="fas fa-briefcase"></i> LivejobsBD</div>

    <div class="side-section-label">Overview</div>
    <ul class="side-nav">
      <li><a href="{{ route('candidate.dashboard') }}" class="active"><i class="fas fa-grid-2"></i> Dashboard</a></li>
      <li><a href="{{ route('candidate.profile') }}"><i class="fas fa-user"></i> My Profile</a></li>
    </ul>

    <div class="side-section-label">Job Search</div>
    <ul class="side-nav">
      <li><a href="{{ route('candidate.jobs.index') }}"><i class="fas fa-magnifying-glass"></i> Find Jobs</a></li>
      <li>
        <a href="{{ route('candidate.applications') }}">
          <i class="fas fa-file-lines"></i> Applied Jobs
          @if(!empty($stats['applied']))<span class="badge-count">{{ $stats['applied'] }}</span>@endif
        </a>
      </li>
      <li><a href="{{ route('candidate.saved') }}"><i class="fas fa-bookmark"></i> Saved Jobs</a></li>
      <li><a href="{{ route('candidate.alerts') }}"><i class="fas fa-bell"></i> Job Alerts</a></li>
    </ul>

    <div class="side-section-label">Account</div>
    <ul class="side-nav">
      <li><a href="{{ route('candidate.messages') }}"><i class="fas fa-message"></i> Messages</a></li>
      <li><a href="{{ route('candidate.settings') }}"><i class="fas fa-gear"></i> Settings</a></li>
    </ul>

    <div class="sidebar-profile">
      <div class="avatar">
        @if(!empty($user->photo))
          <img src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->full_name }}">
        @else
          {{ isset($user) ? strtoupper(substr($user->full_name ?? $user->name, 0, 1)) : 'U' }}
        @endif
      </div>
      <div class="info">
        <h4>{{ $user->full_name ?? $user->name ?? 'Guest User' }}</h4>
        <span>Candidate</span>
      </div>
      <a href="{{ route('logout') }}" class="logout-link" title="Logout"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-arrow-right-from-bracket"></i>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
  </aside>

  <!-- Main -->
  <div class="main">

    <div class="topbar">
      <div style="display:flex; align-items:center; gap:14px;">
        <div class="menu-toggle-mobile" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
        <div class="topbar-heading">
          <h1>Dashboard</h1>
          <span>Here's what's happening with your job search</span>
        </div>
      </div>
      <div class="topbar-actions">
        <div class="icon-btn"><i class="fas fa-bell"></i><span class="dot"></span></div>
      </div>
    </div>

    <div class="content">

      <!-- Welcome / Completion Banner -->
      @php $completion = $profileCompletion ?? 70; @endphp
      <div class="welcome-card">
        <div class="welcome-text">
          <h2>Welcome back, {{ explode(' ', $user->full_name ?? $user->name ?? 'there')[0] }} 👋</h2>
          <p>You have {{ $stats['new_matches'] ?? 0 }} new job matches and {{ $stats['pending'] ?? 0 }} applications awaiting review.</p>
        </div>

        <div class="completion-ring-wrap">
          <div class="completion-ring">
            <svg viewBox="0 0 62 62">
              <circle class="ring-bg" cx="31" cy="31" r="26"></circle>
              <circle class="ring-fg" cx="31" cy="31" r="26"
                      stroke-dasharray="{{ round(2 * 3.1416 * 26) }}"
                      stroke-dashoffset="{{ round(2 * 3.1416 * 26 * (1 - $completion / 100)) }}"></circle>
            </svg>
            <div class="ring-pct">{{ $completion }}%</div>
          </div>
          <div class="cta-text">
            <h4>Profile Completion</h4>
            <a href="{{ route('candidate.profile') }}">Complete your profile →</a>
          </div>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon cobalt"><i class="fas fa-file-lines"></i></div>
          <div>
            <div class="stat-value">{{ $stats['applied'] ?? 0 }}</div>
            <div class="stat-label">Applied Jobs</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon mint"><i class="fas fa-check-circle"></i></div>
          <div>
            <div class="stat-value">{{ $stats['shortlisted'] ?? 0 }}</div>
            <div class="stat-label">Shortlisted</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon gold"><i class="fas fa-bookmark"></i></div>
          <div>
            <div class="stat-value">{{ $stats['saved'] ?? 0 }}</div>
            <div class="stat-label">Saved Jobs</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon rose"><i class="fas fa-eye"></i></div>
          <div>
            <div class="stat-value">{{ $stats['profile_views'] ?? 0 }}</div>
            <div class="stat-label">Profile Views</div>
          </div>
        </div>
      </div>

      <!-- Two column: Applications + Sidebar widgets -->
      <div class="dash-columns">

        <!-- Left column -->
        <div>
          <div class="panel">
            <div class="panel-header">
              <h2><i class="fas fa-file-lines"></i> Recent Applications</h2>
              <a href="{{ route('candidate.applications') }}" class="view-all">View all</a>
            </div>

            @if (!empty($recentApplications) && count($recentApplications))
              <table class="app-table">
                <thead>
                  <tr>
                    <th>Job</th>
                    <th>Applied On</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($recentApplications as $app)
                    <tr>
                      <td>
                        <div class="job-cell">
                          <div class="job-logo">{{ strtoupper(substr($app->company_name ?? 'J', 0, 1)) }}</div>
                          <div>
                            <div class="job-title">{{ $app->job_title ?? 'Job Title' }}</div>
                            <div class="job-company">{{ $app->company_name ?? 'Company' }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="app-date">{{ optional($app->created_at)->format('d M, Y') }}</td>
                      <td>
                        @php $st = $app->status ?? 'pending'; @endphp
                        <span class="status-pill {{ $st }}">{{ ucfirst($st) }}</span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <div class="empty-state">
                <i class="fas fa-inbox"></i>
                You haven't applied to any jobs yet.<br>
                <a href="{{ route('candidate.jobs.index') }}" style="color:#0b7f77; font-weight:600;">Browse open jobs →</a>
              </div>
            @endif
          </div>

          <div class="panel">
            <div class="panel-header">
              <h2><i class="fas fa-star"></i> Recommended For You</h2>
              <a href="{{ route('candidate.jobs.index') }}" class="view-all">View all</a>
            </div>

            @if (!empty($recommendedJobs) && count($recommendedJobs))
              @foreach ($recommendedJobs as $job)
                <div class="job-rec-card">
                  <div class="job-logo">{{ strtoupper(substr($job->company_name ?? 'J', 0, 1)) }}</div>
                  <div class="job-rec-info">
                    <h4>{{ $job->title ?? 'Job Title' }}</h4>
                    <div class="job-rec-meta">
                      {{ $job->company_name ?? 'Company' }} · {{ $job->location ?? 'Remote' }}
                    </div>
                    <div class="job-rec-tags">
                      @if(!empty($job->job_type))<span>{{ $job->job_type }}</span>@endif
                      @if(!empty($job->salary_range))<span>{{ $job->salary_range }}</span>@endif
                    </div>
                  </div>
                  <div class="save-icon"><i class="fas fa-bookmark"></i></div>
                </div>
              @endforeach
            @else
              <div class="empty-state">
                <i class="fas fa-briefcase"></i>
                No recommendations yet — complete your profile to get matched jobs.
              </div>
            @endif
          </div>
        </div>

        <!-- Right column -->
        <div>
          <div class="panel">
            <div class="panel-header"><h2><i class="fas fa-list-check"></i> Complete Your Profile</h2></div>
            <div class="checklist">
              <div class="checklist-item {{ !empty($user->full_name) ? 'done' : '' }}">
                <div class="check-icon {{ !empty($user->full_name) ? 'done' : 'pending' }}">
                  <i class="fas {{ !empty($user->full_name) ? 'fa-check' : 'fa-circle' }}"></i>
                </div>
                <span>Basic details</span>
              </div>
              <div class="checklist-item {{ !empty($user->photo) ? 'done' : '' }}">
                <div class="check-icon {{ !empty($user->photo) ? 'done' : 'pending' }}">
                  <i class="fas {{ !empty($user->photo) ? 'fa-check' : 'fa-circle' }}"></i>
                </div>
                <span>Profile photo</span>
              </div>
              <div class="checklist-item {{ !empty($user->resume) ? 'done' : '' }}">
                <div class="check-icon {{ !empty($user->resume) ? 'done' : 'pending' }}">
                  <i class="fas {{ !empty($user->resume) ? 'fa-check' : 'fa-circle' }}"></i>
                </div>
                <span>Resume uploaded</span>
              </div>
              <div class="checklist-item {{ !empty($hasExperience) ? 'done' : '' }}">
                <div class="check-icon {{ !empty($hasExperience) ? 'done' : 'pending' }}">
                  <i class="fas {{ !empty($hasExperience) ? 'fa-check' : 'fa-circle' }}"></i>
                </div>
                <span>Work experience</span>
              </div>
              <div class="checklist-item {{ !empty($hasEducation) ? 'done' : '' }}">
                <div class="check-icon {{ !empty($hasEducation) ? 'done' : 'pending' }}">
                  <i class="fas {{ !empty($hasEducation) ? 'fa-check' : 'fa-circle' }}"></i>
                </div>
                <span>Education</span>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header"><h2><i class="fas fa-bolt"></i> Quick Actions</h2></div>
            <div class="quick-actions">
              <a href="{{ route('candidate.jobs.index') }}" class="quick-action-btn"><i class="fas fa-magnifying-glass"></i> Find New Jobs</a>
              <a href="{{ route('candidate.profile') }}" class="quick-action-btn"><i class="fas fa-user-pen"></i> Update Profile</a>
              <a href="{{ route('candidate.alerts') }}" class="quick-action-btn"><i class="fas fa-bell"></i> Manage Job Alerts</a>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('active');
  document.getElementById('sidebarOverlay').classList.toggle('active');
}
</script>

</body>
</html>