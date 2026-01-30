@extends('layouts.student-portal')

@section('title', 'Results & Transcript')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 font-weight-bold">Academic Results & Transcript</h1>
                <a href="{{ route('student.download-transcript') }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download Transcript
                </a>
            </div>
        </div>
    </div>

    <!-- GPA Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted">Cumulative GPA</h6>
                    <h2 class="font-weight-bold text-success">{{ number_format($cumulativeGPA, 2) }}</h2>
                    <small class="text-muted">Out of 4.0</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h6 class="text-muted">Current Level GPA</h6>
                    <h2 class="font-weight-bold text-info">{{ number_format($currentLevelGPA, 2) }}</h2>
                    <small class="text-muted">This Session</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Units</h6>
                    <h2 class="font-weight-bold text-warning">{{ $totalUnitsCompleted }}</h2>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h6 class="text-muted">Academic Status</h6>
                    <h5 class="font-weight-bold">{{ $academicStatus ?? 'Good Standing' }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <label class="mr-3">Filter by Semester:</label>
                <select name="semester" class="form-control mr-3" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    @foreach($allSemesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                            {{ $sem }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Course Results - {{ request('semester', 'All Time') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Units</th>
                            <th>C.A. (20%)</th>
                            <th>Exam (80%)</th>
                            <th>Total (100%)</th>
                            <th>Grade</th>
                            <th>Points</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                        <tr>
                            <td><strong>{{ $result->course->code }}</strong></td>
                            <td>{{ $result->course->title }}</td>
                            <td><span class="badge badge-secondary">{{ $result->course->units }}</span></td>
                            <td>{{ $result->continuous_assessment ?? '-' }}/20</td>
                            <td>{{ $result->exam_score ?? '-' }}/80</td>
                            <td><strong>{{ $result->total_score ?? '-' }}/100</strong></td>
                            <td>
                                <span class="badge badge-{{ $result->grade_badge_color }}">
                                    {{ $result->grade ?? 'TBA' }}
                                </span>
                            </td>
                            <td>{{ $result->grade_points ?? '-' }}</td>
                            <td>
                                @if($result->status === 'passed')
                                    <span class="badge badge-success">Passed</span>
                                @elseif($result->status === 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No results available yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grade Scale Legend -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Grade Scale</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>A:</strong> 90-100 (4.0)<br>
                    <strong>B:</strong> 80-89 (3.0)<br>
                    <strong>C:</strong> 70-79 (2.0)
                </div>
                <div class="col-md-3">
                    <strong>D:</strong> 60-69 (1.0)<br>
                    <strong>E:</strong> 50-59 (0.5)<br>
                    <strong>F:</strong> Below 50 (0.0)
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
@endsection
