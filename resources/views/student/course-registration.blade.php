@extends('layouts.student-portal')

@section('title', 'Course Registration')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 font-weight-bold">Course Registration</h1>
                <span class="badge badge-info">{{ $academicSession->name ?? 'Current Session' }}</span>
            </div>
        </div>
    </div>

    @if($registrationOpen)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <strong>Registration Period:</strong> {{ $registrationStart->format('M d, Y') }} - {{ $registrationEnd->format('M d, Y') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Available Courses</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('student.register-courses') }}" id="courseForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%"><input type="checkbox" id="selectAll"></th>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                            <th>Units</th>
                                            <th>Lecturer</th>
                                            <th>Capacity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($availableCourses as $course)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="courses[]" value="{{ $course->id }}" 
                                                    class="course-checkbox" 
                                                    {{ in_array($course->id, $registeredCourseIds) ? 'checked disabled' : '' }}>
                                            </td>
                                            <td><strong>{{ $course->code }}</strong></td>
                                            <td>{{ $course->title }}</td>
                                            <td><span class="badge badge-secondary">{{ $course->units }}</span></td>
                                            <td>{{ $course->lecturer_name ?? 'TBA' }}</td>
                                            <td>
                                                <small class="text-muted">{{ $course->enrolled_count }}/{{ $course->capacity }}</small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                No courses available for your program
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-warning">
                                <strong>Total Units:</strong> <span id="totalUnits">0</span> / {{ $maxUnits ?? 24 }}
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle"></i> Register Courses
                                </button>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Registration Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Program:</strong> {{ $student->program->name ?? 'N/A' }}</p>
                        <p><strong>Current Level:</strong> {{ $student->current_level ?? 'N/A' }}</p>
                        <hr>
                        <p><strong>Registered Courses:</strong> <span id="courseCount">{{ count($registeredCourseIds) }}</span></p>
                        <p><strong>Total Units:</strong> <span id="summaryUnits">0</span></p>
                        <hr>
                        <div class="alert alert-info small">
                            <strong>Note:</strong> You can register between {{ $maxUnits ?? 24 }} - {{ $minUnits ?? 12 }} course units per semester.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            <strong>Registration Closed:</strong> The registration period for this session has ended. Please contact the Academic Office if you have any questions.
        </div>
    @endif
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.course-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateTotalUnits();
});

document.querySelectorAll('.course-checkbox').forEach(cb => {
    cb.addEventListener('change', updateTotalUnits);
});

function updateTotalUnits() {
    let total = 0;
    let count = 0;
    document.querySelectorAll('.course-checkbox:checked').forEach(cb => {
        const row = cb.closest('tr');
        const units = parseInt(row.querySelector('td:nth-child(4)').textContent);
        total += units;
        count++;
    });
    document.getElementById('totalUnits').textContent = total;
    document.getElementById('summaryUnits').textContent = total;
    document.getElementById('courseCount').textContent = count;
}
</script>
@endsection
