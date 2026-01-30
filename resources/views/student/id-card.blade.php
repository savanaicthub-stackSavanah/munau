@extends('layouts.student-portal')

@section('title', 'ID Card Request')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 font-weight-bold">Student ID Card Request</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ID Card Details</h5>
                </div>
                <div class="card-body">
                    @if($currentIDCard)
                        <div class="alert alert-info">
                            <strong>Current ID Card:</strong> {{ $currentIDCard->id_number }} 
                            <span class="badge badge-{{ $currentIDCard->status_badge }}">{{ ucfirst($currentIDCard->status) }}</span>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="mb-3">ID Card Information</h6>
                                <p><strong>ID Number:</strong> {{ $currentIDCard->id_number }}</p>
                                <p><strong>Issued Date:</strong> {{ $currentIDCard->issued_date->format('M d, Y') }}</p>
                                <p><strong>Expiry Date:</strong> {{ $currentIDCard->expiry_date->format('M d, Y') }}</p>
                                <p><strong>Status:</strong> <span class="badge badge-{{ $currentIDCard->status_badge }}">{{ ucfirst($currentIDCard->status) }}</span></p>
                            </div>
                            <div class="col-md-6">
                                @if($currentIDCard->card_image)
                                    <img src="{{ $currentIDCard->card_image }}" alt="ID Card" class="img-fluid border rounded">
                                @else
                                    <div class="alert alert-warning">Card image not available</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            @if($currentIDCard->status === 'active' && !$currentIDCard->isExpired())
                                <button class="btn btn-primary" data-toggle="modal" data-target="#renewModal">
                                    <i class="fas fa-sync"></i> Renew Card
                                </button>
                            @elseif($currentIDCard->isExpired())
                                <button class="btn btn-danger" data-toggle="modal" data-target="#renewModal">
                                    <i class="fas fa-exclamation-triangle"></i> Card Expired - Renew Now
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>No ID Card Found:</strong> You haven't requested an ID card yet. Click the button below to request one.
                        </div>
                        
                        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#requestModal">
                            <i class="fas fa-id-card"></i> Request ID Card
                        </button>
                    @endif
                </div>
            </div>

            <!-- ID Card Requests History -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Request History</h5>
                </div>
                <div class="card-body">
                    @if(count($idCardRequests) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request Date</th>
                                        <th>Card ID</th>
                                        <th>Status</th>
                                        <th>Issued Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($idCardRequests as $request)
                                    <tr>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>{{ $request->id_number ?? '-' }}</td>
                                        <td>
                                            @if($request->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($request->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($request->status === 'collected')
                                                <span class="badge badge-info">Collected</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->issued_date ? $request->issued_date->format('M d, Y') : '-' }}</td>
                                        <td>
                                            @if($request->status === 'approved')
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#collectModal" 
                                                    onclick="setRequestId({{ $request->id }})">Collect</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No previous requests found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-info-circle text-info"></i> Information</h6>
                    <ul class="small">
                        <li>ID card is valid for 2 years from issuance</li>
                        <li>Renewal can be done 3 months before expiry</li>
                        <li>You can collect your card from the Student Affairs Office</li>
                        <li>Lost or damaged cards require a new request</li>
                        <li>Processing takes 2-5 business days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Request ID Card</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.request-id-card') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Reason for Request</label>
                        <select name="reason" class="form-control" required>
                            <option value="">Select reason</option>
                            <option value="first_time">First Time Request</option>
                            <option value="lost">Lost Card</option>
                            <option value="damaged">Damaged Card</option>
                            <option value="replacement">Replacement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Photo (if different from profile)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="text-muted">JPG or PNG, max 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Renew Modal -->
<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Renew ID Card</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.renew-id-card') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to renew your ID card? A renewal fee may apply.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Renewal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Collect Modal -->
<div class="modal fade" id="collectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Collect ID Card</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('student.collect-id-card') }}" method="POST">
                @csrf
                <input type="hidden" id="requestId" name="request_id">
                <div class="modal-body">
                    <p>You can collect your ID card from the Student Affairs Office during office hours.</p>
                    <div class="alert alert-info">
                        <strong>Office Hours:</strong> Monday - Friday, 9:00 AM - 4:00 PM
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Collection</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setRequestId(id) {
    document.getElementById('requestId').value = id;
}
</script>
@endsection
