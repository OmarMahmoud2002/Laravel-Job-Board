@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}. You can manage jobs and users here.</p>
    
    <h3>Latest Jobs</h3>
    <ul>
        @foreach ($jobs as $job)
            <li>{{ $job->title }} - Status: {{ $job->status }}</li>
        @endforeach
    </ul>

    <h3>Latest Users</h3>
    <ul>
        @foreach ($users as $user)
            <li>{{ $user->name }} - Role: {{ $user->role }}</li>
        @endforeach
    </ul>
</div>
@endsection
