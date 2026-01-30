@extends('layouts.web')

@section('title', 'About | Munau College of Health Sciences and Technology')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">About</li>
        </ol>
    </div>
</nav>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-5 mb-4">About Munau College</h2>
                <p class="lead text-muted mb-3">
                    Munau College of Health Sciences and Technology stands as a beacon of excellence in health professions education and training. Established to address the critical shortage of skilled healthcare professionals, our institution combines traditional academic rigor with modern practical training.
                </p>
                <p class="text-muted mb-3">
                    We are committed to producing competent, ethical, and compassionate healthcare professionals who are prepared to serve Nigeria and the global community with distinction. Our world-class facilities, expert faculty, and student-centered approach ensure that graduates are ready for the challenges of modern healthcare.
                </p>
                <p class="text-muted">
                    With programs spanning nursing, midwifery, radiography, laboratory science, environmental health, and many other health disciplines, we provide comprehensive training that meets international standards.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1576091160550-112173f7f869?w=500&h=400&fit=crop" alt="Medical Education" class="img-fluid rounded-3">
            </div>
        </div>
    </div>
</section>

<!-- Vision Mission Values -->
<section class="section bg-light" id="vision">
    <div class="container">
        <h2 class="section-title mb-5">Our Vision, Mission & Core Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-eye fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title text-center">Our Vision</h5>
                        <p class="card-text">
                            To be a world-class institution recognized for excellence in health professions education, research, and community service, producing graduates who are globally competitive and committed to improving healthcare delivery.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-target fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title text-center">Our Mission</h5>
                        <p class="card-text">
                            To provide quality education and training in health sciences that develops knowledgeable, skilled, and ethical healthcare professionals capable of meeting the health needs of individuals, families, and communities.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-heart fa-2x text-danger"></i>
                        </div>
                        <h5 class="card-title text-center">Core Values</h5>
                        <ul class="list-unstyled">
                            <li><strong>Excellence:</strong> In all endeavors</li>
                            <li><strong>Integrity:</strong> In conduct and practice</li>
                            <li><strong>Compassion:</strong> In service delivery</li>
                            <li><strong>Innovation:</strong> In education & research</li>
                            <li><strong>Accountability:</strong> To stakeholders</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- History & Milestones -->
<section class="section" id="history">
    <div class="container">
        <h2 class="section-title mb-5">Our Journey</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="timeline">
                    <div class="timeline-item mb-4">
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="timeline-marker bg-primary"></div>
                            </div>
                            <div class="col">
                                <h5>Institution Established</h5>
                                <p class="text-muted mb-0">Munau College was founded with a vision to provide world-class health professions education in Nigeria.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="timeline-marker bg-success"></div>
                            </div>
                            <div class="col">
                                <h5>First Cohort Graduated</h5>
                                <p class="text-muted mb-0">Our inaugural batch of healthcare professionals successfully completed their programs and began contributing to the healthcare sector.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="timeline-marker bg-warning"></div>
                            </div>
                            <div class="col">
                                <h5>Expanded Programs</h5>
                                <p class="text-muted mb-0">Introduction of new programs and specializations to meet evolving healthcare industry demands.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="timeline-marker bg-info"></div>
                            </div>
                            <div class="col">
                                <h5>International Recognition</h5>
                                <p class="text-muted mb-0">Achieved international accreditation and recognition for our commitment to quality education and research.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="section bg-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div>
                    <h3 class="display-4 text-primary">50+</h3>
                    <p class="text-muted">Programs Offered</p>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h3 class="display-4 text-success">1000+</h3>
                    <p class="text-muted">Students Enrolled</p>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h3 class="display-4 text-warning">200+</h3>
                    <p class="text-muted">Faculty & Staff</p>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h3 class="display-4 text-info">5000+</h3>
                    <p class="text-muted">Graduates Worldwide</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section">
    <div class="container">
        <h2 class="section-title mb-5">Why Students Choose Munau College</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Comprehensive Curriculum</h5>
                        <p class="text-muted">Programs designed to meet international standards and industry requirements.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Practical Training</h5>
                        <p class="text-muted">Hands-on experience through clinical placements and internships in healthcare facilities.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Career Support</h5>
                        <p class="text-muted">Career guidance and job placement assistance for graduating students.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Modern Facilities</h5>
                        <p class="text-muted">Access to laboratories, simulation centers, and teaching hospitals.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Scholarship Programs</h5>
                        <p class="text-muted">Various scholarship and financial aid options available for deserving students.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5>Global Network</h5>
                        <p class="text-muted">Exchange programs and partnerships with international institutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .timeline-marker {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-top: 3px;
    }
</style>
@endsection
