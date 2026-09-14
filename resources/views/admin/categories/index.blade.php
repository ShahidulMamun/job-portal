@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h2>Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->slug }}</td>
                <td>{{ $cat->description }}</td>
                <td>{{ $cat->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</div>

@include('partials.toast')
@endsection
