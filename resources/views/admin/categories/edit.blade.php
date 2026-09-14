@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h2>Edit Category</h2>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $category->name) }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Description (optional)</label>
            <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
