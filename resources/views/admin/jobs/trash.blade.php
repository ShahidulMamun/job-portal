@extends('admin.layouts.app')

@section('content')


    <div class="main">
          <table class="table table-bordered">
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Deleted At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trashedJobs as $job)
            <tr>
                <td>{{ $job->job_title }}</td>
                <td>{{ $job->deleted_at->diffForHumans() }}</td>
                <td>
                    <form action="{{ route('admin.job.restore', $job->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm">Restore</button>
                    </form>

                    <form action="{{ route('admin.job.forceDelete', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Permanent Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
@include('partials.toast')
@endsection
