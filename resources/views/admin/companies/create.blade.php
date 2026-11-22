{{-- resources/views/admin/companies/create.blade.php --}}
@extends('admin.layouts.app')   {{-- <- change to your admin layout name --}}

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Add company</h4>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.companies._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save company</button>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection