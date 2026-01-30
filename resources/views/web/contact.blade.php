@extends('layouts.web')

@section('title', 'Contact | Munau College of Health Sciences and Technology')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Contact</li>
        </ol>
    </div>
</nav>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <h1 class="section-title">Contact Us</h1>
        
        <div class="row g-5 mb-5">
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Address
                        </h5>
                        <p class="text-muted">
                            Munau College of Health Sciences and Technology<br>
                            Dutse, Jigawa State<br>
                            Nigeria
                        </p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-phone text-primary me-2"></i>Phone
                        </h5>
                        <p class="text-muted">
                            <strong>Main:</strong> +234 (0) XXX XXX XXXX<br>
                            <strong>Admissions:</strong> +234 (0) XXX XXX XXXX<br>
                            <strong>Finance:</strong> +234 (0) XXX XXX XXXX
                        </p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i>Email
                        </h5>
                        <p class="text-muted">
                            <strong>General:</strong> info@munaucollege.edu.ng<br>
                            <strong>Admissions:</strong> admissions@munaucollege.edu.ng<br>
                            <strong>Support:</strong> support@munaucollege.edu.ng
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>Office Hours
                        </h5>
                        <p class="text-muted mb-1">
                            <strong>Monday - Friday:</strong><br>
                            8:00 AM - 5:00 PM
                        </p>
                        <p class="text-muted mb-0">
                            <strong>Saturday:</strong><br>
                            10:00 AM - 2:00 PM
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Send us a Message</h5>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title mb-5">Find Us on the Map</h2>
        <div class="ratio ratio-16x9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3929.5482701946405!2d9.331622!3d11.766667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDQ2JzAwLjAiTiA5wrMyMCcwMTAuMCJF!5e0!3m2!1sen!2sng!4v1234567890" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<!-- Social Media -->
<section class="section">
    <div class="container text-center">
        <h2 class="section-title mb-5">Follow Us on Social Media</h2>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="https://facebook.com/munaucollege" class="btn btn-outline-primary btn-lg rounded-circle p-3">
                <i class="fab fa-facebook-f fa-2x"></i>
            </a>
            <a href="https://twitter.com/munaucollege" class="btn btn-outline-info btn-lg rounded-circle p-3">
                <i class="fab fa-twitter fa-2x"></i>
            </a>
            <a href="https://instagram.com/munaucollege" class="btn btn-outline-danger btn-lg rounded-circle p-3">
                <i class="fab fa-instagram fa-2x"></i>
            </a>
            <a href="https://linkedin.com/company/munaucollege" class="btn btn-outline-primary btn-lg rounded-circle p-3">
                <i class="fab fa-linkedin-in fa-2x"></i>
            </a>
        </div>
    </div>
</section>
@endsection
