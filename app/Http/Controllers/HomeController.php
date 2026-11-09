<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Major Bangladeshi job markets, with the spellings people actually type.
     * Searching "Chittagong" also finds "Chattogram", and so on.
     */
    public const CITIES = [
        'Dhaka'       => ['dhaka'],
        'Chattogram'  => ['chattogram', 'chittagong'],
        'Sylhet'      => ['sylhet'],
        'Rajshahi'    => ['rajshahi'],
        'Khulna'      => ['khulna'],
        'Barishal'    => ['barishal', 'barisal'],
        'Rangpur'     => ['rangpur'],
        'Mymensingh'  => ['mymensingh'],
        'Gazipur'     => ['gazipur'],
        'Narayanganj' => ['narayanganj'],
        'Cumilla'     => ['cumilla', 'comilla'],
        "Cox's Bazar" => ['cox'],
    ];

    private const JOB_TYPES  = ['full-time', 'part-time', 'contract', 'internship'];
    private const JOB_LEVELS = ['Entry', 'Mid', 'Senior', 'Lead', 'Manager'];

    public function registerPage()
    {
        return view('auth.register-page');
    }

    /* ------------------------------------------------------------------
     |  Home page
     * ------------------------------------------------------------------ */
    public function index()
    {
        $stats = Cache::remember('home.stats', 600, function () {
            return [
                'jobs'         => JobPost::open()->count(),
                'companies'    => JobPost::open()->whereNotNull('company_name')->distinct()->count('company_name'),
                'candidates'   => User::count(),
                'applications' => JobApplication::count(),
            ];
        });

        $categories = Category::where('status', 1)
            ->withCount(['jobs' => fn ($q) => $q->open()])
            ->orderByDesc('jobs_count')
            ->orderBy('name')
            ->take(12)
            ->get();

        $allCategories = $this->allCategories();

        $jobs = JobPost::open()
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->take(12)
            ->get();

        $liveJobs = $jobs->take(3);

        $companies = JobPost::open()
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->selectRaw('company_name, COUNT(*) as jobs_count')
            ->groupBy('company_name')
            ->orderByDesc('jobs_count')
            ->take(8)
            ->get();

        $cities = Cache::remember('home.cities', 600, function () {
            $out = [];
            foreach (array_slice(self::CITIES, 0, 8, true) as $label => $aliases) {
                $out[$label] = JobPost::open()->where(function ($w) use ($aliases) {
                    foreach ($aliases as $a) {
                        $w->orWhere('location', 'like', '%' . $a . '%');
                    }
                })->count();
            }
            return $out;
        });

        $remoteCount = Cache::remember('home.remote', 600, fn () => JobPost::open()->where('remote_available', true)->count());

        $popular = array_slice(array_values(array_unique(array_merge(
            ['Software Engineer', 'Accountant', 'Sales Executive', 'Graphic Designer', 'Digital Marketing', 'Customer Support'],
            $categories->pluck('name')->take(4)->all()
        ))), 0, 8);

        return view('index', compact(
            'stats', 'categories', 'allCategories', 'jobs', 'liveJobs',
            'companies', 'cities', 'remoteCount', 'popular'
        ));
    }

    /* ------------------------------------------------------------------
     |  Search / listing page  (GET /jobs)
     * ------------------------------------------------------------------ */
    public function jobs(Request $request)
    {
        $filters = $this->filters($request);
        $jobs    = $this->buildQuery($filters)->paginate(12)->withQueryString();

        $allCategories = $this->allCategories();
        $popular = ['Software Engineer', 'Accountant', 'Sales Executive', 'Graphic Designer', 'Digital Marketing', 'Customer Support'];
        $activeCategory = $filters['category']
            ? $allCategories->firstWhere('id', (int) $filters['category'])
            : null;

        return view('jobs.index', compact('jobs', 'filters', 'allCategories', 'popular', 'activeCategory'));
    }

    /* ------------------------------------------------------------------
     |  Autocomplete  (GET /search/suggest?q=...)
     * ------------------------------------------------------------------ */
    public function suggest(Request $request)
    {
        $q = trim(Str::limit((string) $request->query('q', ''), 60, ''));

        $empty = ['jobs' => [], 'categories' => [], 'companies' => [], 'locations' => []];
        if (mb_strlen($q) < 2) {
            return response()->json($empty);
        }

        $like = '%' . addcslashes($q, '%_\\') . '%';

        $jobs = JobPost::open()
            ->where(function ($w) use ($like) {
                $w->where('job_title', 'like', $like)->orWhere('tags', 'like', $like);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['job_title', 'company_name', 'location', 'slug'])
            ->map(fn ($j) => [
                'title'    => $j->job_title,
                'company'  => $j->company_name,
                'location' => $j->location,
                'url'      => route('jobs.show', $j->slug),
            ]);

        $categories = Category::where('status', 1)
            ->where('name', 'like', $like)
            ->orderBy('name')
            ->take(3)
            ->get(['id', 'name'])
            ->map(fn ($c) => [
                'name' => $c->name,
                'url'  => route('jobs.index', ['category' => $c->id]),
            ]);

        $companies = JobPost::open()
            ->where('company_name', 'like', $like)
            ->selectRaw('company_name, COUNT(*) as jobs_count')
            ->groupBy('company_name')
            ->orderByDesc('jobs_count')
            ->take(3)
            ->get()
            ->map(fn ($c) => [
                'name' => $c->company_name,
                'jobs' => (int) $c->jobs_count,
                'url'  => route('jobs.index', ['q' => $c->company_name]),
            ]);

        $locations = JobPost::open()
            ->where('location', 'like', $like)
            ->select('location')
            ->distinct()
            ->take(3)
            ->pluck('location')
            ->map(fn ($l) => [
                'name' => $l,
                'url'  => route('jobs.index', ['location' => $l]),
            ]);

        return response()->json([
            'jobs'       => $jobs,
            'categories' => $categories,
            'companies'  => $companies,
            'locations'  => $locations,
        ]);
    }

    public function show(JobPost $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function apply(Request $request, $jobId)
    {
        $request->validate([
            'cover_letter' => 'nullable|string|max:1000',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $job = JobPost::findOrFail($jobId);

        // Prevent duplicate application
        if (JobApplication::where('user_id', auth()->id())->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'You already applied to this job.');
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        JobApplication::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }

    /* ------------------------------------------------------------------
     |  Helpers
     * ------------------------------------------------------------------ */
    private function allCategories()
    {
        return Category::where('status', 1)->orderBy('name')->get(['id', 'name']);
    }

    /** Whitelist and normalise every incoming search parameter. */
    private function filters(Request $r): array
    {
        $strings = fn ($v) => array_values(array_filter((array) $v, 'is_string'));

        $q = trim(Str::limit((string) $r->input('q', ''), 100, ''));
        $posted = (int) $r->input('posted');
        $category = (string) $r->input('category', '');
        $sort = (string) $r->input('sort', '');

        if (! in_array($sort, ['relevance', 'latest', 'deadline'], true)) {
            $sort = $q !== '' ? 'relevance' : 'latest';
        }

        return [
            'q'        => $q,
            'location' => trim(Str::limit((string) $r->input('location', ''), 60, '')),
            'category' => ctype_digit($category) ? $category : '',
            'type'     => array_values(array_intersect($strings($r->input('type', [])), self::JOB_TYPES)),
            'level'    => array_values(array_intersect($strings($r->input('level', [])), self::JOB_LEVELS)),
            'posted'   => in_array($posted, [1, 3, 7, 30], true) ? $posted : null,
            'remote'   => $r->boolean('remote'),
            'sort'     => $sort,
        ];
    }

    private function buildQuery(array $f)
    {
        $query = JobPost::open();

        // Keyword: every word must match title / company / tags / skills / code / category name
        if ($f['q'] !== '') {
            $tokens = array_slice(preg_split('/\s+/u', $f['q'], -1, PREG_SPLIT_NO_EMPTY), 0, 5);
            foreach ($tokens as $token) {
                $like   = '%' . addcslashes($token, '%_\\') . '%';
                $catIds = Category::where('name', 'like', $like)->pluck('id')->map(fn ($id) => (string) $id)->all();

                $query->where(function ($w) use ($like, $catIds) {
                    $w->where('job_title', 'like', $like)
                      ->orWhere('company_name', 'like', $like)
                      ->orWhere('tags', 'like', $like)
                      ->orWhere('skills', 'like', $like)
                      ->orWhere('job_code', 'like', $like);
                    if ($catIds) {
                        $w->orWhereIn('category_id', $catIds);
                    }
                });
            }
        }

        // Location (with Bangla-city spelling aliases) or "Remote"
        if ($f['location'] !== '') {
            $loc = mb_strtolower($f['location']);
            if ($loc === 'remote') {
                $query->where('remote_available', true);
            } else {
                $terms = [$loc];
                foreach (self::CITIES as $aliases) {
                    if (in_array($loc, $aliases, true)) {
                        $terms = $aliases;
                        break;
                    }
                }
                $query->where(function ($w) use ($terms) {
                    foreach ($terms as $t) {
                        $w->orWhere('location', 'like', '%' . addcslashes($t, '%_\\') . '%');
                    }
                });
            }
        }

        if ($f['category'] !== '') {
            $query->where('category_id', $f['category']);
        }
        if ($f['type']) {
            $query->whereIn('job_type', $f['type']);
        }
        if ($f['level']) {
            $query->whereIn('job_level', $f['level']);
        }
        if ($f['remote']) {
            $query->where('remote_available', true);
        }
        if ($f['posted']) {
            $query->where('job_posts.created_at', '>=', now()->subDays($f['posted'])->startOfDay());
        }

        switch ($f['sort']) {
            case 'deadline':
                $query->orderBy('deadline')->orderByDesc('created_at');
                break;
            case 'relevance':
                if ($f['q'] !== '') {
                    $like = '%' . addcslashes($f['q'], '%_\\') . '%';
                    $query->orderByRaw(
                        'CASE WHEN job_title LIKE ? THEN 0 WHEN company_name LIKE ? THEN 1 ELSE 2 END',
                        [$like, $like]
                    )->orderByDesc('is_featured')->orderByDesc('created_at');
                    break;
                }
                // no keyword: fall through to latest
            default:
                $query->orderByDesc('is_featured')->orderByDesc('created_at');
        }

        return $query;
    }
}
