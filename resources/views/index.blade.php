@extends('layouts.app')


@section('content')
<section class="hero" id="home">
<div class="container">
<div class="hero-inner">

<div class="hero-content">
<div class="hero-eyebrow eyebrow"><span class="dot"></span>12,845 open roles across Bangladesh</div>
<h1>Find your <em>dream job</em> today</h1>
<p>Discover thousands of vetted opportunities from real companies — with the salary, location and details up front, every time.</p>
<div class="hero-cta-row">
<a href="{{route('register')}}" class="btn btn-gold">Browse Jobs <i class="fas fa-arrow-right"></i></a>
<div class="hero-cta-note"><i class="fas fa-circle-check" style="color:var(--mint);"></i> Free for candidates, always</div>
</div>
</div>

<div class="hero-visual">
<div class="float-card float-card--1">
<div class="num">25.6K</div>
<div class="lbl">Active candidates</div>
</div>
<div class="float-card float-card--2">
<div class="num">3,254</div>
<div class="lbl">Hiring companies</div>
</div>
<div class="float-card float-card--3">
<div class="num">18.9K</div>
<div class="lbl">CVs uploaded</div>
</div>
<div class="role-chip role-chip--1"><div class="avatar"></div>Senior Developer hired</div>
<div class="role-chip role-chip--2"><div class="avatar" style="background:linear-gradient(135deg,var(--gold),#e08d1f);"></div>UX Designer hired</div>
</div>

</div>

</div>
</section>

<div class="search-box-wrap">
<div class="container">
<div class="search-box">
<form class="search-form">
<input type="text" class="search-input" placeholder="Job title, keywords, or company">
<input type="text" class="search-input" placeholder="City or postcode">
<select class="search-input">
<option>All Categories</option>
<option>IT & Development</option>
<option>Marketing</option>
<option>Design</option>
<option>Sales</option>
</select>
<button type="submit" class="btn btn-gold"><i class="fas fa-search"></i> Search</button>
</form>
</div>
</div>
</div>


<section class="job-categories section">
  <div class="container">
    <div class="section-header">
      <div>
        <span class="eyebrow">Browse by field</span>
        <h2>Job categories</h2>
      </div>
      <p class="sub">Five fields, thousands of openings — pick where you want to grow.</p>
    </div>
    <div class="categories-grid">
      <div class="category-card">
        <i class="fas fa-code"></i>
        <h3>IT & Development</h3>
        <p>4,512 JOBS</p>
      </div>
      <div class="category-card">
        <i class="fas fa-bullhorn"></i>
        <h3>Marketing</h3>
        <p>3,120 JOBS</p>
      </div>
      <div class="category-card">
        <i class="fas fa-paint-brush"></i>
        <h3>Design</h3>
        <p>2,845 JOBS</p>
      </div>
      <div class="category-card">
        <i class="fas fa-chart-line"></i>
        <h3>Finance</h3>
        <p>1,980 JOBS</p>
      </div>
      <div class="category-card">
        <i class="fas fa-users"></i>
        <h3>Human Resources</h3>
        <p>1,245 JOBS</p>
      </div>
    </div>
  </div>
</section>


<section class="section" id="jobs">
<div class="container">
<div class="section-header">
<div>
<span class="eyebrow">Fresh off the board</span>
<h2>Latest jobs</h2>
</div>
<p class="sub">Newest opportunities posted today, tickets ready to claim.</p>
</div>
<div class="jobs-grid">
@forelse ($jobs as $job)
<div class="job-card">
<div class="job-main">
<div class="job-header"><div class="company-logo"><i class="fas fa-code"></i></div>
<div class="job-info"><h3>{{$job->job_title}}</h3><p class="company-name">{{$job->company_name}}</p></div></div>
<div class="job-meta"><span class="meta-item"><i class="fas fa-map-marker-alt"></i>{{$job->location}}</span><span class="meta-item"><i class="fas fa-clock"></i>{{ucwords(str_replace("-", " ", $job->job_type))}}</span></div>
<div class="job-tags">
   @php $tags = explode(",", $job->tags); @endphp
    @foreach ($tags as $tag)
     <span class='tag'> {{ trim($tag) }} </span>
    @endforeach
</div>
<div class="job-footer"><span class="salary">{{$job->salary_range}}</span><a href="{{route('jobs.show',$job->slug)}}" class="btn btn-outline">Apply Now</a></div>
</div>
</div>
@empty
<p>No jobs found right now.</p>
@endforelse
</div>
<div style="text-align:center; margin-top:3rem;"><a href="#" class="btn btn-outline-dark">View all jobs <i class="fas fa-arrow-right"></i></a></div>
</div>
</section>

<section class="section" id="companies">
<div class="container">
<div class="section-header">
<div>
<span class="eyebrow">Who's hiring</span>
<h2>Popular companies</h2>
</div>
<p class="sub">Established teams actively growing their headcount right now.</p>
</div>
<div class="companies-grid">
<div class="company-card"><div class="company-logo"><i class="fas fa-code"></i></div><h3>TechCorp Solutions</h3><p>Information Technology</p><p class="job-count">45 OPEN POSITIONS</p></div>
<div class="company-card"><div class="company-logo"><i class="fas fa-building"></i></div><h3>Global Finance Corp</h3><p>Banking & Finance</p><p class="job-count">28 OPEN POSITIONS</p></div>
<div class="company-card"><div class="company-logo"><i class="fas fa-heart"></i></div><h3>HealthCare Plus</h3><p>Healthcare</p><p class="job-count">32 OPEN POSITIONS</p></div>
<div class="company-card"><div class="company-logo"><i class="fas fa-graduation-cap"></i></div><h3>EduLearn Platform</h3><p>Education</p><p class="job-count">19 OPEN POSITIONS</p></div>
</div>
</div>
</section>

<section class="section" id="candidates">
<div class="container">
<div class="section-header">
<div>
<span class="eyebrow">Talent on the market</span>
<h2>Featured candidates</h2>
</div>
<p class="sub">Skilled professionals ready for their next opportunity.</p>
</div>
<div class="candidates-grid">
<div class="candidate-card"><div class="candidate-avatar">JS</div><h3>John Smith</h3><p class="role">Senior Web Developer</p><div class="skills"><span class="tag">React</span><span class="tag">Node.js</span></div><a href="#" class="btn btn-outline-dark">View Profile</a></div>
<div class="candidate-card"><div class="candidate-avatar">SE</div><h3>Sarah Evans</h3><p class="role">UX Designer</p><div class="skills"><span class="tag">Figma</span><span class="tag">UI Design</span></div><a href="#" class="btn btn-outline-dark">View Profile</a></div>
<div class="candidate-card"><div class="candidate-avatar">MP</div><h3>Michael Park</h3><p class="role">Data Analyst</p><div class="skills"><span class="tag">Python</span><span class="tag">SQL</span></div><a href="#" class="btn btn-outline-dark">View Profile</a></div>
<div class="candidate-card"><div class="candidate-avatar">EJ</div><h3>Emily Johnson</h3><p class="role">Marketing Specialist</p><div class="skills"><span class="tag">SEO</span><span class="tag">Content</span></div><a href="#" class="btn btn-outline-dark">View Profile</a></div>
</div>
</div>
</section>


<section class="stats">
<div class="container">

<div class="stats-grid">
<div class="stat-item"><i class="fas fa-briefcase"></i><h3>12,845</h3><p>Available jobs</p></div>
<div class="stat-item"><i class="fas fa-building"></i><h3>3,254</h3><p>Popular companies</p></div>
<div class="stat-item"><i class="fas fa-users"></i><h3>25,680</h3><p>Active candidates</p></div>
<div class="stat-item"><i class="fas fa-file-alt"></i><h3>18,920</h3><p>CVs uploaded</p></div>
</div>

</div>
</section>



<section class="cta">
<div class="container cta-inner">
<h2>Ready to take the next step?</h2>
<p>Join thousands of professionals finding their dream jobs on LivejobsBD.</p>
<div class="cta-buttons">
<a href="{{route('register')}}" class="btn btn-ghost-white">Create free account</a>
<a href="#" class="btn btn-outline">Upload your CV</a>
</div>
</div>
</section>
@endsection