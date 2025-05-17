@extends('layouts.main')

@section('title', $job->title)

@section('content')
    <!-- Page Header -->
    <section class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Job Details</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('job-listings.index') }}" class="text-white">Jobs</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($job->title, 30) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Detail Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Job Details -->
                <div class="col-lg-8">
                    <!-- Job Header -->
                    <div class="job-detail-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                @if($job->company_logo)
                                    <img src="{{ asset('storage/' . $job->company_logo) }}" alt="{{ $job->employer->company_name ?? $job->employer->name }}" class="company-logo">
                                @else
                                    <div class="company-logo d-flex align-items-center justify-content-center">
                                        <i class="fas fa-building fa-3x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h1 class="h2 mb-2">{{ $job->title }}</h1>
                                <p class="mb-3 text-muted">{{ $job->employer->company_name ?? $job->employer->name }}</p>

                                <div class="d-flex flex-wrap">
                                    <div class="me-4 mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i> {{ $job->location }}
                                    </div>
                                    <div class="me-4 mb-2">
                                        <i class="fas fa-briefcase text-primary me-1"></i> {{ ucfirst($job->type) }}
                                    </div>
                                    <div class="me-4 mb-2">
                                        <i class="fas fa-money-bill-wave text-primary me-1"></i>
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
                                    <div class="mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i> Posted {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <span class="badge bg-primary">{{ $job->category }}</span>
                                    <span class="badge bg-danger ms-2">Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="job-detail-content mb-4">
                        <h3 class="mb-4">Job Description</h3>
                        <div class="job-description">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Similar Jobs -->
                    @if(isset($similarJobs) && $similarJobs->count() > 0)
                        <div class="similar-jobs mt-5">
                            <h3 class="mb-4">Similar Jobs</h3>
                            <div class="row">
                                @foreach($similarJobs as $similarJob)
                                    <div class="col-md-6 mb-4">
                                        <div class="job-card h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($similarJob->company_logo)
                                                    <img src="{{ asset('storage/' . $similarJob->company_logo) }}" alt="{{ $similarJob->employer->company_name ?? $similarJob->employer->name }}" class="company-logo me-3" style="width: 50px; height: 50px;">
                                                @else
                                                    <div class="company-logo me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-building text-secondary"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="h6 mb-1">
                                                        <a href="{{ route('job-listings.show', $similarJob->id) }}">{{ $similarJob->title }}</a>
                                                    </h4>
                                                    <p class="small text-muted mb-0">{{ $similarJob->employer->company_name ?? $similarJob->employer->name }}</p>
                                                </div>
                                            </div>
                                            <div class="small mb-2">
                                                <i class="fas fa-map-marker-alt text-primary me-1"></i> {{ $similarJob->location }}
                                            </div>
                                            <a href="{{ route('job-listings.show', $similarJob->id) }}" class="btn btn-sm btn-outline-primary">View Job</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Apply Button -->
                    <div class="job-detail-sidebar mb-4">
                        <h3 class="h5 mb-4">Apply for this Job</h3>

                        @auth
                            @if(auth()->user()->role === 'candidate')
                                @php
                                    $hasApplied = auth()->user()->applications()->where('job_id', $job->id)->exists();
                                @endphp

                                @if($hasApplied)
                                    <div class="alert alert-info">
                                        <i class="fas fa-check-circle me-2"></i> You have already applied for this job.
                                    </div>
                                @else
                                    @if(\Carbon\Carbon::now()->gt($job->deadline))
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle me-2"></i> The application deadline has passed.
                                        </div>
                                    @else
                                        @if(!auth()->user()->resume_path)
                                            <div class="alert alert-warning mb-3">
                                                <i class="fas fa-exclamation-circle me-2"></i> You need to upload your resume before applying.
                                            </div>
                                            <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <div class="mb-3">
                                                    <label for="resume" class="form-label">Upload Resume</label>
                                                    <input type="file" class="form-control" id="resume" name="resume" required>
                                                    <div class="form-text">Upload your resume in PDF format. Max size: 5MB.</div>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Upload Resume</button>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <a href="{{ route('candidate.profile') }}" class="small">Go to profile to upload more details</a>
                                                </div>
                                            </form>
                                        @else
                                            <form action="{{ route('job-listings.apply', $job->id) }}" method="POST" id="job-application-form">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="message" class="form-label">Cover Letter (Optional)</label>
                                                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tell the employer why you're a good fit for this position..." data-max-chars="1000"></textarea>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Apply Now</button>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        <span>Your resume is attached to this application</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . auth()->user()->resume_path) }}" target="_blank" class="small">
                                                            <i class="fas fa-eye me-1"></i> View your resume
                                                        </a>
                                                        <span class="mx-2">|</span>
                                                        <a href="{{ route('candidate.profile') }}" class="small">
                                                            <i class="fas fa-edit me-1"></i> Update resume
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    @endif
                                @endif
                            @elseif(auth()->user()->role === 'employer')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You are logged in as an employer and cannot apply for jobs.
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You are logged in as an admin and cannot apply for jobs.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i> You need to login as a candidate to apply for this job.
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">Login to Apply</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register as Candidate</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Company Info -->
                    <div class="job-detail-sidebar mb-4">
                        <h3 class="h5 mb-4">About the Company</h3>

                        <div class="text-center mb-4">
                            @if($job->company_logo)
                                <img src="{{ asset('storage/' . $job->company_logo) }}" alt="{{ $job->employer->company_name ?? $job->employer->name }}" class="mb-3" style="max-width: 150px;">
                            @else
                                <div class="d-flex align-items-center justify-content-center mb-3" style="height: 100px;">
                                    <i class="fas fa-building fa-4x text-secondary"></i>
                                </div>
                            @endif
                            <h4 class="h6">{{ $job->employer->company_name ?? $job->employer->name }}</h4>
                        </div>

                        @if($job->employer->website)
                            <div class="mb-3">
                                <i class="fas fa-globe text-primary me-2"></i>
                                <a href="{{ $job->employer->website }}" target="_blank">{{ $job->employer->website }}</a>
                            </div>
                        @endif

                        @if($job->employer->bio)
                            <div class="mb-3">
                                <p>{{ $job->employer->bio }}</p>
                            </div>
                        @endif

                        <div class="d-grid">
                            <a href="{{ route('job-listings.index', ['employer' => $job->employer_id]) }}" class="btn btn-outline-primary">View All Jobs by this Employer</a>
                        </div>
                    </div>

                    <!-- Job Details Summary -->
                    <div class="job-detail-sidebar">
                        <h3 class="h5 mb-4">Job Details</h3>

                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-calendar-alt text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Date Posted</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-hourglass-end text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Application Deadline</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-map-marker-alt text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Location</h6>
                                    <p class="text-muted">{{ $job->location }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-briefcase text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Job Type</h6>
                                    <p class="text-muted">{{ ucfirst($job->type) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-money-bill-wave text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Salary Range</h6>
                                    <p class="text-muted">
                                        @if($job->salary_min && $job->salary_max)
                                            ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                        @elseif($job->salary_min)
                                            From ${{ number_format($job->salary_min) }}
                                        @elseif($job->salary_max)
                                            Up to ${{ number_format($job->salary_max) }}
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-tag text-primary fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Category</h6>
                                    <p class="text-muted">{{ $job->category }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
