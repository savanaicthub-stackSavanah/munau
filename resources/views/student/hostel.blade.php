@extends('layouts.student-portal')

@section('title', 'Hostel Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 font-weight-bold">Hostel Management</h1>
                @if(!$currentAllocation)
                    <button class="btn btn-primary" data-toggle="modal" data-target="#applicationModal">
                        <i class="fas fa-home"></i> Apply for Hostel
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if($currentAllocation)
        <!-- Current Allocation -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Current Hostel Allocation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Hostel Name:</strong> {{ $currentAllocation->hostel->name }}</p>
                                <p><strong>Block:</strong> {{ $currentAllocation->block ?? 'N/A' }}</p>
                                <p><strong>Room Number:</strong> {{ $currentAllocation->room_number }}</p>
                                <p><strong>Bed:</strong> {{ $currentAllocation->bed_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Allocation Date:</strong> {{ $currentAllocation->allocation_date->format('M d, Y') }}</p>
                                <p><strong>Session:</strong> {{ $currentAllocation->academic_session }}</p>
                                <p><strong>Status:</strong> <span class="badge badge-success">Allocated</span></p>
                                <p><strong>Check-in Status:</strong> 
                                    @if($currentAllocation->checked_in)
                                        <span class="badge badge-info">Checked In</span>
                                    @else
                                        <span class="badge badge-warning">Not Yet Checked In</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(!$currentAllocation->checked_in)
                            <div class="mt-3">
                                <button class="btn btn-info" data-toggle="modal" data-target="#checkInModal">
                                    <i class="fas fa-sign-in-alt"></i> Check In
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Hostel Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Hostel Address:</strong><br>{{ $currentAllocation->hostel->address }}</p>
                        <p><strong>Warden:</strong><br>{{ $currentAllocation->hostel->warden_name }}<br>{{ $currentAllocation->hostel->warden_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hostel Charges -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Hostel Charges & Payments</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Charge</h6>
                                <h4 class="font-weight-bold">₦{{ number_format($hostelCharges->total_amount ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Amount Paid</h6>
                                <h4 class="font-weight-bold">₦{{ number_format($hostelCharges->amount_paid ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Balance</h6>
                                <h4 class="font-weight-bold">₦{{ number_format($hostelCharges->balance ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100" data-toggle="modal" data-target="#paymentModal">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hostelPayments as $payment)
                            <tr>
                                <td>{{ $payment->description }}</td>
                                <td>₦{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            <strong>No Hostel Allocation:</strong> You haven't been allocated a hostel yet. Click "Apply for Hostel" to submit an application.
        </div>
    @endif

    <!-- Application History -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Application History</h5>
        </div>
        <div class="card-body">
            @if(count($applications) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Session</th>
                                <th>Application Date</th>
                                <th>Status</th>
                                <th>Allocation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                            <tr>
                                <td>{{ $app->academic_session }}</td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($app->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($app->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($app->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $app->allocation ? $app->allocation->hostel->name . ' - ' . $app->allocation->room_number : '-' }}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No previous applications found.</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Application Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Apply for Hostel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.apply-hostel') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Preferred Hostel</label>
                        <select name="hostel_id" class="form-control" required>
                            <option value="">Select hostel</option>
                            @foreach($availableHostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->available_beds }} beds available)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Do you have any special needs?</label>
                        <textarea name="special_needs" class="form-control" rows="3" placeholder="e.g., medical needs, accessibility requirements"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Check-In Modal -->
<div class="modal fade" id="checkInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Check In to Hostel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.hostel-checkin') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>By checking in, you confirm that you have received the hostel keys and inspected the room.</p>
                    <div class="form-check">
                        <input type="checkbox" id="confirmCheck" name="confirm" required>
                        <label for="confirmCheck">I confirm the room condition and have received all keys</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Confirm Check In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Pay Hostel Charges</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.pay-hostel') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Amount (₦)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" 
                            max="{{ $hostelCharges->balance ?? 0 }}" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Select method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Debit Card</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Process Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
