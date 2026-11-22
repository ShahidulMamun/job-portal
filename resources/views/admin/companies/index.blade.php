{{-- resources/views/admin/companies/index.blade.php --}}
@extends('admin.layouts.app')   {{-- <- change to your admin layout name --}}

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h4 class="mb-0">Companies <span class="text-muted fs-6">({{ $companies->total() }})</span></h4>
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Add company</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or location…">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    @if (request('q'))<a href="{{ route('admin.companies.index') }}" class="btn btn-link">Clear</a>@endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th class="text-center">Jobs</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($company->logo_url)
                                            <img src="{{ $company->logo_url }}" alt="" style="height:38px;width:38px;object-fit:cover;border-radius:9px;border:1px solid #dee2e6">
                                        @else
                                            <span class="d-inline-flex align-items-center justify-content-center fw-bold text-white" style="height:38px;width:38px;border-radius:9px;background:#12a79b;font-size:13px">{{ $company->initials }}</span>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $company->name }}</div>
                                            @if ($company->website)<a href="{{ $company->website }}" target="_blank" rel="noopener" class="small text-muted">{{ parse_url($company->website, PHP_URL_HOST) }}</a>@endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $company->location ?: '—' }}</td>
                                <td class="text-center"><span class="badge text-bg-light">{{ $company->jobs_count }}</span></td>
                                <td class="text-center">
                                    <form action="{{ route('admin.companies.toggle-active', $company) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="badge border-0 {{ $company->is_active ? 'text-bg-success' : 'text-bg-secondary' }}" title="Toggle status">{{ $company->is_active ? 'Active' : 'Hidden' }}</button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete {{ addslashes($company->name) }}? Its jobs will stay but lose the company link.');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">No companies yet. <a href="{{ route('admin.companies.create') }}">Add the first one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $companies->links() }}
        </div>
    </div>
</div>
@endsection