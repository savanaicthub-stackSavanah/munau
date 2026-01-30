@extends('layouts.web')

@section('title', 'Apply for Admission | Munau College')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Apply</li>
        </ol>
    </div>
</nav>

<!-- Application Form -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Online Admission Application</h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5>Please correct the following errors:</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admission.store') }}">
                            @csrf

                            <h6 class="mb-3 text-uppercase text-primary">Personal Information</h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Select gender</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h6 class="mb-3 text-uppercase text-primary">Contact Information</h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required>
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required>
                                    @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code') }}">
                                </div>
                            </div>

                            <h6 class="mb-3 text-uppercase text-primary">Academic Information</h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">Select Programme <span class="text-danger">*</span></label>
                                    <select class="form-select @error('program_id') is-invalid @enderror" name="program_id" required>
                                        <option value="">-- Select a Programme --</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                                {{ $program->name }} ({{ $program->award_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Admission Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('admission_type') is-invalid @enderror" name="admission_type" required>
                                        <option value="">Select admission type</option>
                                        <option value="utme" {{ old('admission_type') === 'utme' ? 'selected' : '' }}>UTME</option>
                                        <option value="post_utme" {{ old('admission_type') === 'post_utme' ? 'selected' : '' }}>Post UTME</option>
                                        <option value="merit" {{ old('admission_type') === 'merit' ? 'selected' : '' }}>Merit</option>
                                        <option value="direct_entry" {{ old('admission_type') === 'direct_entry' ? 'selected' : '' }}>Direct Entry</option>
                                    </select>
                                    @error('admission_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">JAMB Registration Number</label>
                                    <input type="text" class="form-control" name="jamb_registration_number" value="{{ old('jamb_registration_number') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">JAMB Score</label>
                                    <input type="number" class="form-control" name="jamb_score" min="0" max="400" value="{{ old('jamb_score') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">O'Level Result Number</label>
                                    <input type="text" class="form-control" name="o_level_result_number" value="{{ old('o_level_result_number') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">O'Level Year</label>
                                    <input type="number" class="form-control" name="o_level_year" min="2000" value="{{ old('o_level_year') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the terms and conditions and certify that all information provided is accurate.
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Create Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
