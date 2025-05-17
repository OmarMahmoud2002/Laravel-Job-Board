@extends('layouts.main')

@section('title', 'Find Your Dream Job')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Find Your Dream Job Today</h1>
                    <p class="lead mb-4">Thousands of jobs in the technology, finance, education, and healthcare sectors are waiting for you.</p>
                    <a href="{{ route('job-listings.index') }}" class="btn btn-light btn-lg">Browse All Jobs</a>
                </div>
                <div class="col-lg-6">
                    <div class="search-form">
                        <form action="{{ route('job-listings.index') }}" method="GET" id="job-search-form">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Job title, keywords, or company">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" name="location" class="form-control" placeholder="Location">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-light btn-lg w-100">Search Jobs</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="section-title">Featured Jobs</h2>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('job-listings.index') }}" class="btn btn-outline-primary">View All Jobs</a>
                </div>
            </div>

            <div class="row">
                @forelse($featuredJobs as $job)
                    <div class="col-md-6 col-lg-4">
                        <div class="job-card">
                            <div class="d-flex align-items-center mb-3">
                                @if($job->company_logo)
                                    <img src="{{ asset('storage/' . $job->company_logo) }}" alt="{{ $job->employer->company_name ?? $job->employer->name }}" class="company-logo me-3">
                                @else
                                    <div class="company-logo me-3 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-building fa-2x text-secondary"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="job-title mb-1">
                                        <a href="{{ route('job-listings.show', $job->id) }}">{{ $job->title }}</a>
                                    </h3>
                                    <p class="company-name mb-0">{{ $job->employer->company_name ?? $job->employer->name }}</p>
                                </div>
                            </div>

                            <div class="job-details">
                                <div class="job-detail">
                                    <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                                </div>
                                <div class="job-detail">
                                    <i class="fas fa-briefcase"></i> {{ ucfirst($job->type) }}
                                </div>
                                <div class="job-detail">
                                    <i class="fas fa-money-bill-wave"></i>
                                    @if($job->salary_min && $job->salary_max)
                                        ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                    @elseif($job->salary_min)
                                        From ${{ number_format($job->salary_min) }}
                                    @elseif($job->salary_max)
                                        Up to ${{ number_format($job->salary_max) }}
                                    @else
                                        Not specified
                                    @endif
                                </div>
                            </div>

                            <div class="job-tags">
                                <span class="job-tag">{{ $job->category }}</span>
                            </div>

                            <div class="job-actions">
                                <a href="{{ route('job-listings.show', $job->id) }}" class="btn btn-sm btn-primary">View Details</a>
                                <div class="job-deadline">
                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($job->deadline)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No featured jobs available at this time.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Job Categories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Popular Job Categories</h2>

            <div class="row">
                @foreach($popularCategories as $category => $count)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-{{ getCategoryIcon($category) }} fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">{{ $category }}</h5>
                                <p class="card-text text-muted">{{ $count }} {{ Str::plural('job', $count) }} available</p>
                                <a href="{{ route('job-listings.index', ['category' => $category]) }}" class="btn btn-sm btn-outline-primary">Browse Jobs</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Employers Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Featured Employers</h2>

            <div class="row">
                @foreach($featuredEmployers as $employer)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="employer-card">
                            @if($employer->company_logo)
                                <img src="{{ asset('storage/' . $employer->company_logo) }}" alt="{{ $employer->company_name ?? $employer->name }}" class="employer-logo">
                            @else
                                <div class="employer-logo d-flex align-items-center justify-content-center">
                                    <i class="fas fa-building fa-3x text-secondary"></i>
                                </div>
                            @endif
                            <h3 class="employer-name">{{ $employer->company_name ?? $employer->name }}</h3>
                            <p class="job-count">{{ $employer->jobs_count }} {{ Str::plural('job', $employer->jobs_count) }}</p>
                            <a href="{{ route('job-listings.index', ['employer' => $employer->id]) }}" class="btn btn-sm btn-outline-primary">View Jobs</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">How It Works</h2>

            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-user-plus fa-4x text-primary"></i>
                            </div>
                            <h4>Create an Account</h4>
                            <p class="text-muted">Sign up as a job seeker or employer to get started with our platform.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-search fa-4x text-primary"></i>
                            </div>
                            <h4>Find What You Need</h4>
                            <p class="text-muted">Search for jobs or candidates that match your requirements.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-handshake fa-4x text-primary"></i>
                            </div>
                            <h4>Apply or Hire</h4>
                            <p class="text-muted">Submit applications or contact candidates to fill your positions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


