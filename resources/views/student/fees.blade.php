@extends('layouts.student-portal')

@section('title', 'Fee Payment Status')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 font-weight-bold">Fee Payment Status & History</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">
                    <i class="fas fa-credit-card"></i> Make Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Fee Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Charges</h6>
                    <h2 class="font-weight-bold text-primary">₦{{ number_format($totalCharges, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted">Amount Paid</h6>
                    <h2 class="font-weight-bold text-success">₦{{ number_format($amountPaid, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-muted">Outstanding Balance</h6>
                    <h2 class="font-weight-bold text-danger">₦{{ number_format($outstandingBalance, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h6 class="text-muted">Payment Status</h6>
                    <h5 class="font-weight-bold">
                        @if($outstandingBalance == 0)
                            <span class="badge badge-success">Fully Paid</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Breakdown -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Fee Breakdown - {{ $academicSession->name ?? 'Current Session' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeBreakdown as $fee)
                        <tr>
                            <td><strong>{{ $fee['name'] }}</strong></td>
                            <td>₦{{ number_format($fee['amount'], 2) }}</td>
                            <td>₦{{ number_format($fee['paid'], 2) }}</td>
                            <td>₦{{ number_format($fee['balance'], 2) }}</td>
                            <td>
                                @if($fee['balance'] == 0)
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

    <!-- Payment History -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Payment History</h5>
        </div>
        <div class="card-body">
            @if(count($paymentHistory) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Reference No.</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentHistory as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ $payment->description }}</td>
                                <td><strong>₦{{ number_format($payment->amount, 2) }}</strong></td>
                                <td><code>{{ $payment->reference_number }}</code></td>
                                <td><span class="badge badge-info">{{ ucfirst($payment->payment_method) }}</span></td>
                                <td>
                                    @if($payment->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.download-receipt', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Receipt
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No payment history found. Click "Make Payment" to pay your fees.
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.process-payment') }}" method="POST" id="paymentForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Amount to Pay (₦)</label>
                        <div class="input-group">
                            <span class="input-group-text">₦</span>
                            <input type="number" name="amount" class="form-control" step="0.01" 
                                min="1" max="{{ $outstandingBalance }}" placeholder="Enter amount" required>
                        </div>
                        <small class="text-muted">Outstanding balance: ₦{{ number_format($outstandingBalance, 2) }}</small>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Select payment method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Debit Card</option>
                            <option value="paystack">Paystack</option>
                            <option value="flutterwave">Flutterwave</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i> Process Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
