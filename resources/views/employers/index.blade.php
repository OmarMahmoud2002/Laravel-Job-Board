@extends('layouts.main')

@section('title', 'Featured Employers')

@section('content')
    <!-- Page Header -->
    <section class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Featured Employers</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Employers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Employers Listing Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Filter Employers</h5>
                            <form action="{{ route('employers.index') }}" method="GET">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Company name">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="industry" class="form-label">Industry</label>
                                    <select class="form-select" id="industry" name="industry">
                                        <option value="">All Industries</option>
                                        @foreach($industries as $industry)
                                            <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}" placeholder="City or country">
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                
                                @if(request()->anyFilled(['search', 'industry', 'location']))
                                    <div class="d-grid mt-2">
                                        <a href="{{ route('employers.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Employers Grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="mb-0">Showing {{ $employers->firstItem() ?? 0 }} - {{ $employers->lastItem() ?? 0 }} of {{ $employers->total() }} employers</p>
                        
                        <div class="btn-group">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="btn btn-outline-primary {{ request('view') != 'list' ? 'active' : '' }}">
                                <i class="fas fa-th-large"></i>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="btn btn-outline-primary {{ request('view') == 'list' ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                    
                    @if(request('view') == 'list')
                        <!-- List View -->
                        <div class="employers-list">
                            @forelse($employers as $employer)
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                                @if($employer->company_logo)
                                                    <img src="{{ asset('storage/' . $employer->company_logo) }}" alt="{{ $employer->company_name ?? $employer->name }}" class="img-fluid" style="max-height: 80px;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px; width: 80px; margin: 0 auto;">
                                                        <i class="fas fa-building fa-2x text-secondary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-7 mb-3 mb-md-0">
                                                <h3 class="h5 mb-1">{{ $employer->company_name ?? $employer->name }}</h3>
                                                @if($employer->website)
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-globe me-1"></i> 
                                                        <a href="{{ $employer->website }}" target="_blank" class="text-decoration-none">{{ $employer->website }}</a>
                                                    </p>
                                                @endif
                                                <p class="mb-2">{{ Str::limit($employer->bio, 100) }}</p>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">{{ $employer->jobs_count }} {{ Str::plural('job', $employer->jobs_count) }}</span>
                                                    <span class="text-muted small">
                                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $employer->location ?? 'Location not specified' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-md-end">
                                                <a href="{{ route('employers.show', $employer->id) }}" class="btn btn-outline-primary">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    No employers found matching your criteria. Try adjusting your filters.
                                </div>
                            @endforelse
                        </div>
                    @else
                        <!-- Grid View -->
                        <div class="row">
                            @forelse($employers as $employer)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                @if($employer->company_logo)
                                                    <img src="{{ asset('storage/' . $employer->company_logo) }}" alt="{{ $employer->company_name ?? $employer->name }}" class="img-fluid mb-3" style="max-height: 80px;">
                                                @else
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="height: 80px; width: 80px;">
                                                        <i class="fas fa-building fa-2x text-secondary"></i>
                                                    </div>
                                                @endif
                                                <h3 class="h5 mb-1">{{ $employer->company_name ?? $employer->name }}</h3>
                                                <p class="text-muted small mb-3">
                                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $employer->location ?? 'Location not specified' }}
                                                </p>
                                            </div>
                                            <p class="small mb-3">{{ Str::limit($employer->bio, 100) }}</p>
                                            <div class="mb-3">
                                                <span class="badge bg-primary">{{ $employer->jobs_count }} {{ Str::plural('job', $employer->jobs_count) }}</span>
                                            </div>
                                            <a href="{{ route('employers.show', $employer->id) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        No employers found matching your criteria. Try adjusting your filters.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endif
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $employers->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Industries Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Browse by Industry</h2>
            <div class="row">
                @foreach($featuredIndustries as $industry => $count)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <a href="{{ route('employers.index', ['industry' => $industry]) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-{{ getIndustryIcon($industry) }} fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="h5 mb-2">{{ $industry }}</h3>
                                    <p class="text-muted small mb-0">{{ $count }} {{ Str::plural('employer', $count) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Join as Employer CTA -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Are You an Employer?</h2>
            <p class="lead mb-4">Join our platform to find the best talent for your company. Post job listings, manage applications, and connect with qualified candidates.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">Register as Employer</a>
            @else
                @if(auth()->user()->role !== 'employer')
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Register as Employer</a>
                @else
                    <a href="{{ route('employer.dashboard') }}" class="btn btn-light btn-lg">Go to Dashboard</a>
                @endif
            @endguest
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function getIndustryIcon(industry) {
        const icons = {
            'Technology': 'laptop-code',
            'Healthcare': 'heartbeat',
            'Finance': 'chart-line',
            'Education': 'graduation-cap',
            'Retail': 'shopping-cart',
            'Manufacturing': 'industry',
            'Marketing': 'bullhorn',
            'Hospitality': 'hotel',
            'Construction': 'hard-hat',
            'Transportation': 'truck',
            'Media': 'photo-video',
            'Agriculture': 'leaf'
        };
        
        return icons[industry] || 'building';
    }
</script>
@endpush
