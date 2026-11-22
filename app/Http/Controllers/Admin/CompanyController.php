<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

    public function index(Request $request)
    {
        $companies = Company::withCount('jobs')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->q) . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)
                                       ->orWhere('location', 'like', $term));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }


    public function create()
    {
        return view('admin.companies.create', ['company' => new Company(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['slug']        = $this->uniqueSlug($data['name']);
        $data['is_active']   = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies', 'public');
        }

        Company::create($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company added successfully.');
    }


    public function toggleActive(Company $company)
    {
        $company->update(['is_active' => ! $company->is_active]);
        return back()->with('success', 'Status updated.');
    }

    /* ---------------------------------------------------------------- */

    private function validated(Request $request, ?Company $company = null): array
    {
        return $request->validate([
            'name'         => ['required', 'string', 'max:150', Rule::unique('companies', 'name')->ignore($company?->id)],
            'logo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'website'      => ['nullable', 'url', 'max:255'],
            'location'     => ['nullable', 'string', 'max:120'],
            'description'  => ['nullable', 'string', 'max:5000'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'company';
        $slug = $base;
        $i = 2;

        while (Company::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}