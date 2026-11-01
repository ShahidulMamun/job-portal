<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <a href="{{ url('/') }}" class="brand brand--light">
                    <span class="brand-mark"><i class="fa-solid fa-briefcase"></i></span>
                    <span class="brand-name">Livejobs<b>BD</b></span>
                </a>
                <p>Bangladesh's job portal built around one idea: finding work should be fast, free and clear. Real salaries, real deadlines, real employers.</p>
                <div class="social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div>
                <h4>Job seekers</h4>
                <ul>
                    <li><a href="{{ route('jobs.index') }}">Browse all jobs</a></li>
                    <li><a href="{{ route('jobs.index', ['remote' => 1]) }}">Remote jobs</a></li>
                    <li><a href="{{ route('jobs.index', ['level' => ['Entry']]) }}">Fresher jobs</a></li>
                    <li><a href="{{ route('jobs.index', ['type' => ['internship']]) }}">Internships</a></li>
                    <li><a href="{{ route('register') }}">Create account</a></li>
                </ul>
            </div>

            <div>
                <h4>Employers</h4>
                <ul>
                    <li><a href="{{ route('employer.register') }}">Post a job</a></li>
                    <li><a href="{{ route('employer.login') }}">Employer login</a></li>
                    <li><a href="{{ url('/') }}#how">How hiring works</a></li>
                </ul>
            </div>

            <div>
                <h4>Popular cities</h4>
                <ul>
                    @foreach (['Dhaka', 'Chattogram', 'Sylhet', 'Rajshahi', 'Khulna'] as $c)
                        <li><a href="{{ route('jobs.index', ['location' => $c]) }}">Jobs in {{ $c }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} LivejobsBD. All rights reserved.</p>
            <p>Made with <i class="fa-solid fa-heart"></i> in Bangladesh <span class="bn">🇧🇩</span></p>
        </div>
    </div>
</footer>
