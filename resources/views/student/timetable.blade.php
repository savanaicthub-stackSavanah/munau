@extends('layouts.student-portal')

@section('title', 'Timetable')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 font-weight-bold">Lecture & Examination Timetable</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Academic Session</h6>
                    <h5 class="font-weight-bold">{{ $academicSession->name ?? 'Current' }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Semester</h6>
                    <h5 class="font-weight-bold">{{ $semester ?? 'First' }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Registered Courses</h6>
                    <h5 class="font-weight-bold">{{ count($registeredCourses) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Units</h6>
                    <h5 class="font-weight-bold">{{ $totalUnits ?? 0 }}</h5>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="timetableTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#lectures" data-toggle="tab">Lecture Timetable</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#exams" data-toggle="tab">Examination Schedule</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Lecture Timetable -->
        <div id="lectures" class="tab-pane fade show active">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Weekly Lecture Schedule</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Time Slot</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeSlots as $slot)
                                <tr>
                                    <td class="font-weight-bold">{{ $slot }}</td>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                        <td>
                                            @php
                                                $class = $scheduledClasses->where('day', $day)->where('time_slot', $slot)->first();
                                            @endphp
                                            @if($class)
                                                <div class="class-schedule">
                                                    <strong>{{ $class->course->code }}</strong><br>
                                                    <small>{{ $class->course->title }}</small><br>
                                                    <small class="text-muted">{{ $class->venue }}</small>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Examination Schedule -->
        <div id="exams" class="tab-pane fade">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Examination Schedule</h5>
                </div>
                <div class="card-body">
                    @if(count($examSchedule) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Exam Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Venue</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examSchedule as $exam)
                                    <tr>
                                        <td><strong>{{ $exam->course->code }}</strong></td>
                                        <td>{{ $exam->course->title }}</td>
                                        <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                                        <td>{{ $exam->start_time }}</td>
                                        <td>{{ $exam->duration }} mins</td>
                                        <td>{{ $exam->venue }}</td>
                                        <td><span class="badge badge-primary">{{ $exam->exam_type }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Examination schedule has not been released yet. Please check back later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<style>
.class-schedule {
    background-color: #e8f4f8;
    padding: 8px;
    border-radius: 4px;
    border-left: 3px solid #007bff;
}
table td {
    vertical-align: middle;
}
</style>
@endsection
