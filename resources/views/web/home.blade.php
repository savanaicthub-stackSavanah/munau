@extends('layouts.web')

@section('title', 'Home | Munau College of Health Sciences and Technology')
@section('description', 'Welcome to Munau College - A leading institution for health sciences education')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="mb-3">Leading Excellence in Health Sciences Education</h1>
        <p class="lead mb-4">Nurturing Competent, Ethical and Committed Healthcare Professionals for National Development</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('programs') }}" class="btn btn-light btn-lg px-5">Explore Programs</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">Student Portal</a>
            <a href="{{ route('admission.apply') }}" class="btn btn-warning btn-lg px-5">Apply Now</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-title">Why Choose Munau College?</div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-microscope fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">World-Class Facilities</h5>
                        <p class="card-text text-muted">State-of-the-art laboratories, hospitals, and learning centers equipped with modern technology.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Expert Faculty</h5>
                        <p class="card-text text-muted">Learn from highly qualified and experienced healthcare professionals and educators.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Accredited Programs</h5>
                        <p class="card-text text-muted">All programs are accredited by relevant professional bodies and regulatory authorities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-globe fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Global Recognition</h5>
                        <p class="card-text text-muted">Graduates are recognized internationally and employed in healthcare systems worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="section">
    <div class="container">
        <div class="section-title">Our Programs</div>
        <div class="row g-4">
            @forelse($featuredPrograms ?? [] as $program)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $program->name }}</h5>
                            <p class="card-text text-muted mb-3">{{ Str::limit($program->description, 100) }}</p>
                            <p><small class="text-muted"><strong>Department:</strong> {{ $program->department->name }}</small></p>
                            <p><small class="text-muted"><strong>Duration:</strong> {{ $program->duration_in_years }} years</small></p>
                            <p><small class="text-muted"><strong>Award:</strong> {{ ucfirst($program->award_type) }}</small></p>
                            <a href="{{ route('programs') }}" class="btn btn-sm btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <strong>Welcome to Munau College!</strong> Programs information will be available shortly. Please check back soon.
                    </div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('programs') }}" class="btn btn-primary btn-lg">View All Programs</a>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-title">Latest News & Updates</div>
        <div class="row g-4">
            @forelse($latestNews ?? [] as $article)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" class="card-img-top" alt="{{ $article->title }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted mb-2">{{ $article->published_at->format('M d, Y') }}</small>
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
                            <a href="{{ route('news.show', $article->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No news available at the moment.</div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('news') }}" class="btn btn-primary btn-lg">View All News</a>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="section">
    <div class="container">
        <div class="section-title">Upcoming Events</div>
        <div class="row g-4">
            @forelse($upcomingEvents ?? [] as $event)
                <div class="col-md-12">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-md-3">
                                @if($event->featured_image)
                                    <img src="{{ asset('storage/' . $event->featured_image) }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $event->title }}">
                                @else
                                    <div class="bg-primary h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="fas fa-calendar fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title">{{ $event->title }}</h5>
                                            <p class="card-text text-muted">{{ Str::limit($event->description, 150) }}</p>
                                        </div>
                                        <span class="badge bg-success">{{ $event->event_date_start->format('M d') }}</span>
                                    </div>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <strong>{{ $event->location }}</strong>
                                    </p>
                                    @if($event->require_registration)
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-sm btn-primary">Register</a>
                                    @else
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-sm btn-outline-primary">Learn More</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No upcoming events at the moment.</div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('events') }}" class="btn btn-primary btn-lg">View All Events</a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%); color: white;">
    <div class="container text-center">
        <h2 class="mb-3" style="color: white;">Ready to Join Us?</h2>
        <p class="lead mb-4">Start your journey in health sciences education at Munau College today.</p>
        <a href="{{ route('admission.apply') }}" class="btn btn-light btn-lg px-5">Apply for Admission</a>
    </div>
</section>
@endsection
