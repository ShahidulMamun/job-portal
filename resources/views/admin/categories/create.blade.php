@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h2>Add New Category</h2>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Description (optional)</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Create Category</button>
        <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@include('partials.toast')
@endsection
