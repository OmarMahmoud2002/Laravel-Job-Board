@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pending Jobs</h1>
    <table>
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Employer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->employer->name }}</td>
                    <td>
                        <form action="{{ route('admin.job.approve', $job->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Approve</button>
                        </form>
                        <form action="{{ route('admin.job.reject', $job->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection