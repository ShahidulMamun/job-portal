@extends('admin.layouts.app')


@section('content')

<div class="main">

<div class="container py-4">
    <h3 class="mb-4">Unapproved & Valid Job Posts</h3>

    @if($jobs->isEmpty())
        <div class="alert alert-info ">No valid unapproved jobs found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Employer</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Deadline</th>
                        <th>Time</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $key => $job)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $job->job_title }}</td>
                            <td>{{ $job->company_name ?? 'N/A' }}</td>
                              <td>{{ $job->employer->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($job->job_type) }}</td>
                            <td>{{ $job->location ?? 'Remote' }}</td>
                            <td>{{ \Carbon\Carbon::parse($job->deadline)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($job->application_deadline_time)->format('h:i A') }}</td>

                            <td>{{ $job->salary_range ?? 'Negotiat' }}</td>
                            <td>
                              <a href="{{ route('admin.job.show', $job->id) }}" class="btn btn-sm btn-primary">View</a>


                                <form action="{{route('admin.job.approve',$job->id)}}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Do you want to approve this job?')">Approve</button>
                                </form>

                                 <form action="{{ route('admin.job.reject', $job->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Do you want to reject this job?')">Reject</button>
                                </form>
                               
                                <form action="{{ route('admin.job.destroy', $job->id) }}" method="POST" class="d-inline">
                                    @csrf
                                     @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you want to delete this job?')">Delete</button>
                                </form>

                                 <a href="{{ route('admin.jobs.applicants', $job->id) }}" class="btn btn-sm btn-primary">View Applications</a>

    

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
  


    </div>
@include('partials.toast')
@endsection
