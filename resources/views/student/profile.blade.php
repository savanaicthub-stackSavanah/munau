@extends('layouts.student-portal')

@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                @else
                    <div class="bg-light rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                @endif
                <h5>{{ $user->full_name }}</h5>
                <p class="text-muted small mb-0">{{ $student->matric_number }}</p>
                <p class="text-muted small">{{ $student->program->name }}</p>
                <form action="{{ route('student.profile') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    @method('PUT')
                    <input type="file" name="profile_photo" class="form-control form-control-sm mb-2" accept="image/*">
                    <button type="submit" class="btn btn-sm btn-primary">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="{{ $user->first_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="{{ $user->last_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" value="{{ $user->middle_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->gender ?? 'Not specified') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <input type="text" class="form-control @error('blood_group') is-invalid @enderror" name="blood_group" value="{{ old('blood_group', $student->blood_group) }}">
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Address Information</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Street Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state', $user->state) }}">
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Next of Kin Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Next of Kin Name</label>
                            <input type="text" class="form-control @error('next_of_kin_name') is-invalid @enderror" name="next_of_kin_name" value="{{ old('next_of_kin_name', $student->next_of_kin_name) }}">
                            @error('next_of_kin_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Next of Kin Relationship</label>
                            <input type="text" class="form-control @error('next_of_kin_relationship') is-invalid @enderror" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $student->next_of_kin_relationship) }}">
                            @error('next_of_kin_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Next of Kin Phone</label>
                            <input type="tel" class="form-control @error('next_of_kin_phone') is-invalid @enderror" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $student->next_of_kin_phone) }}">
                            @error('next_of_kin_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Student Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Matric Number</label>
                            <input type="text" class="form-control" value="{{ $student->matric_number }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Registration Number</label>
                            <input type="text" class="form-control" value="{{ $student->registration_number }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="{{ $student->department->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program</label>
                            <input type="text" class="form-control" value="{{ $student->program->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Level</label>
                            <input type="text" class="form-control" value="{{ $student->current_level }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst($student->status) }}" readonly>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
