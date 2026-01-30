@extends('layouts.web')

@section('title', 'Programs | Munau College of Health Sciences and Technology')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Programs</li>
        </ol>
    </div>
</nav>

<!-- Hero Section -->
<section class="section bg-light">
    <div class="container text-center">
        <h1 class="display-4 mb-3">Our Academic Programs</h1>
        <p class="lead text-muted mb-0">Explore our comprehensive range of health sciences programs designed to develop competent healthcare professionals.</p>
    </div>
</section>

<!-- Programs Grid -->
<section class="section">
    <div class="container">
        <div class="row g-4">
            @forelse($programs as $program)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover:shadow-lg transition-shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">{{ $program->name }}</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($program->description, 120) }}</p>
                            
                            <div class="mb-3">
                                <small class="d-block text-muted mb-2">
                                    <strong>Department:</strong> {{ $program->department->name }}
                                </small>
                                <small class="d-block text-muted mb-2">
                                    <strong>Duration:</strong> {{ $program->duration_in_years }} Years
                                </small>
                                <small class="d-block text-muted mb-2">
                                    <strong>Award:</strong> {{ ucfirst($program->award_type) }}
                                </small>
                                <small class="d-block text-muted">
                                    <strong>Credit Units:</strong> {{ $program->total_credit_units }}
                                </small>
                            </div>

                            <a href="{{ route('programs.show', $program->id) }}" class="btn btn-primary btn-sm mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h5>No Programs Available</h5>
                        <p>Programs will be available soon. Please check back later.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12">
                {{ $programs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Ready to Apply?</h2>
        <p class="lead mb-4">Start your journey in health sciences education today.</p>
        <a href="{{ route('admission.apply') }}" class="btn btn-light btn-lg px-5">Apply for Admission</a>
    </div>
</section>
@endsection
