@extends('layouts.student-portal')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-5">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Welcome, {{ Auth::user()->full_name }}!</h5>
                <p class="card-text mb-0">
                    Student ID: <strong>{{ $student->matric_number }}</strong> | 
                    Program: <strong>{{ $student->program->name }}</strong> | 
                    Level: <strong>{{ $student->current_level }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Academic Status -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <h6 class="text-white-50">Enrolled Courses</h6>
            <h3 class="text-white mb-0">{{ $enrolledCourses }}</h3>
        </div>
    </div>

    <!-- Finance Status -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <h6 class="text-white-50">Outstanding Fees</h6>
            <h3 class="text-white mb-0">
                @if($schoolFee)
                    ₦{{ number_format($schoolFee->balance, 2) }}
                @else
                    N/A
                @endif
            </h3>
        </div>
    </div>

    <!-- CGPA -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <h6 class="text-white-50">CGPA</h6>
            <h3 class="text-white mb-0">{{ number_format($student->cgpa, 2) }}</h3>
        </div>
    </div>

    <!-- Notifications -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <h6 class="text-white-50">Notifications</h6>
            <h3 class="text-white mb-0">{{ $pendingNotifications }}</h3>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<section class="mt-5">
    <h5 class="mb-3">Quick Actions</h5>
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('student.courses') }}" class="btn btn-outline-primary w-100 py-3">
                <i class="fas fa-book d-block mb-2 fa-lg"></i>
                Register Courses
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('student.fees') }}" class="btn btn-outline-warning w-100 py-3">
                <i class="fas fa-wallet d-block mb-2 fa-lg"></i>
                Pay Fees
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('student.results') }}" class="btn btn-outline-success w-100 py-3">
                <i class="fas fa-chart-bar d-block mb-2 fa-lg"></i>
                View Results
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('student.hostel') }}" class="btn btn-outline-info w-100 py-3">
                <i class="fas fa-bed d-block mb-2 fa-lg"></i>
                Hostel Info
            </a>
        </div>
    </div>
</section>

<!-- Important Deadlines -->
<section class="mt-5">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Important Deadlines</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td><strong>Course Registration Closes</strong></td>
                            <td class="text-end">{{ $schoolFee?->due_date?->format('M d, Y') ?? 'TBA' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Fee Payment Due</strong></td>
                            <td class="text-end text-danger">{{ $schoolFee?->due_date?->format('M d, Y') ?? 'TBA' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Examination Period</strong></td>
                            <td class="text-end">To be announced</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Recent Activities -->
<section class="mt-5">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Recent Notifications</h5>
        </div>
        <div class="card-body">
            @forelse(Auth::user()->notifications()->limit(5)->get() as $notification)
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 @if(!$loop->last)border-bottom @endif">
                    <div>
                        <h6 class="mb-1">{{ $notification->title }}</h6>
                        <p class="text-muted small mb-0">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->sent_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge badge-{{ $notification->status }}">{{ ucfirst($notification->status) }}</span>
                </div>
            @empty
                <p class="text-muted text-center py-3">No notifications yet</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Hostel Status -->
@if($hostelAllocation)
<section class="mt-5">
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Hostel Allocation</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted small">Allocated Block</p>
                    <h6>{{ $hostelAllocation->hostelRoom->hostelBlock->name }}</h6>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small">Room Number</p>
                    <h6>{{ $hostelAllocation->hostelRoom->room_number }}</h6>
                </div>
                <div class="col-12">
                    <span class="badge badge-{{ $hostelAllocation->status }}">{{ ucfirst($hostelAllocation->status) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
