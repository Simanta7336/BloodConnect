<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Flash Messages --}}
            @if(session('status') === 'request-assigned')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="check-circle-2" class="text-success"></i>
                        <strong>Assigned!</strong>&nbsp;You are now managing this blood request.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('status') === 'request-unassigned')
                <div class="alert alert-secondary alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="info" class="text-secondary"></i>
                        You have unassigned yourself from this request.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('status') === 'request-status-updated')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="check-circle-2" class="text-success"></i>
                        <strong>Updated!</strong>&nbsp;The request status has been updated.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('status') === 'donation-confirmed')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="check-circle-2" class="text-success"></i>
                        <strong>Donation Completed!</strong>&nbsp;The blood donation has been successfully confirmed as completed.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="alert-triangle" class="text-danger"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="card border-0 mb-4" style="border-radius:1.5rem; box-shadow:0 10px 30px rgba(220,53,69,0.05); overflow:hidden;">

                {{-- Header --}}
                <div class="card-header border-0 text-white text-center p-4"
                     style="background:linear-gradient(90deg,#dc3545 0%,#e35d6a 100%);">
                    <div class="bg-white text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                         style="width:48px;height:48px;">
                        <i data-lucide="heart-handshake"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Blood Donation Request Details</h4>
                    <p class="text-white-50 small mb-0 mt-1">Patient: <strong>{{ $bloodRequest->patient_name }}</strong></p>
                </div>

                <div class="card-body p-4 p-md-5">

                    {{-- Priority + Status Bar --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-3 border-bottom">
                        <div>
                            <span class="text-muted small me-2">Priority:</span>
                            @if($bloodRequest->priority === 'emergency')
                                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold shadow-sm">
                                    <i data-lucide="alert-circle" width="12" class="me-1"></i>Emergency
                                </span>
                            @elseif($bloodRequest->priority === 'urgent')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">
                                    <i data-lucide="alert-triangle" width="12" class="me-1"></i>Urgent
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill">Normal</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-muted small me-2">Status:</span>
                            @if($bloodRequest->status === 'pending')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                                    <i data-lucide="clock" width="12" class="me-1"></i>Pending
                                </span>
                            @elseif($bloodRequest->status === 'accepted')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                                    <i data-lucide="check-circle" width="12" class="me-1"></i>Accepted
                                </span>
                            @elseif($bloodRequest->status === 'fulfilled')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                                    <i data-lucide="check-check" width="12" class="me-1"></i>Fulfilled
                                </span>
                            @else
                                <span class="badge bg-secondary text-capitalize px-3 py-1 rounded-pill">{{ $bloodRequest->status }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Request Details Grid --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <span class="text-muted small d-block mb-1">Blood Group Needed</span>
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-6">
                                    <i data-lucide="droplet" width="14" class="me-1"></i>{{ $bloodRequest->blood_group }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <span class="text-muted small d-block mb-1">Units Required</span>
                                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-1">
                                    <i data-lucide="flask-conical" width="16" class="text-muted"></i>
                                    {{ $bloodRequest->units_required }} unit{{ $bloodRequest->units_required > 1 ? 's' : '' }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <span class="text-muted small d-block mb-1">Needed By</span>
                                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-1">
                                    <i data-lucide="calendar" width="16" class="text-muted"></i>
                                    {{ $bloodRequest->needed_by_date ? $bloodRequest->needed_by_date->format('M d, Y') : '—' }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small d-block mb-1">Hospital / Location</span>
                                <h6 class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2">
                                    <i data-lucide="map-pin" width="18" class="text-danger"></i>
                                    {{ $bloodRequest->location }}
                                </h6>
                            </div>
                        </div>
                        @if($bloodRequest->notes)
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3">
                                    <span class="text-muted small d-block mb-1">Additional Notes</span>
                                    <p class="mb-0 text-dark small" style="white-space:pre-line;">{{ $bloodRequest->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Recipient Contact Info --}}
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                            <i data-lucide="user" width="16" class="text-danger"></i>Requester / Recipient Info
                        </h6>
                        <div class="row g-2 small">
                            <div class="col-sm-6">
                                <span class="text-muted">Name:</span>
                                <strong class="d-block text-dark">{{ $bloodRequest->user->name }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Email:</span>
                                <a href="mailto:{{ $bloodRequest->user->email }}" class="d-block text-primary text-decoration-none">
                                    {{ $bloodRequest->user->email }}
                                </a>
                            </div>
                            @if($bloodRequest->user->phone)
                                <div class="col-sm-6">
                                    <span class="text-muted">Phone:</span>
                                    <a href="tel:{{ $bloodRequest->user->phone }}" class="btn btn-sm btn-success rounded-pill px-3 py-1 mt-1">
                                        <i data-lucide="phone" width="13" class="me-1"></i>{{ $bloodRequest->user->phone }}
                                    </a>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <span class="text-muted">Submitted:</span>
                                <span class="d-block text-dark">{{ $bloodRequest->created_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Donation & Donor Details --}}
                    @if($bloodRequest->acceptedResponse)
                        <div class="p-4 rounded-3 mb-4" style="background:linear-gradient(135deg,#f0fdf4,#fff); border:1.5px solid #86efac;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold text-success mb-0 d-flex align-items-center gap-2">
                                    <i data-lucide="heart" width="18"></i>Donation Details
                                </h6>
                                <div>
                                    @if($bloodRequest->acceptedResponse->status === 'completed')
                                        <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold">
                                            <i data-lucide="check-check" width="12" class="me-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3 py-1 fw-semibold">
                                            <i data-lucide="calendar-check" width="12" class="me-1"></i>{{ $bloodRequest->appointment ? 'Scheduled' : 'Accepted' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 small">
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block">Donor:</span>
                                    <strong class="d-block text-dark fs-6">{{ $bloodRequest->acceptedResponse->donor->name }}</strong>
                                    @if($bloodRequest->acceptedResponse->donor->phone)
                                        <a href="tel:{{ $bloodRequest->acceptedResponse->donor->phone }}" class="text-success text-decoration-none small">
                                            <i data-lucide="phone" width="12" class="me-1"></i>{{ $bloodRequest->acceptedResponse->donor->phone }}
                                        </a>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block">Recipient:</span>
                                    <strong class="d-block text-dark fs-6">{{ $bloodRequest->user->name }}</strong>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block">Blood Group:</span>
                                    <span class="fw-bold text-danger fs-6">{{ $bloodRequest->acceptedResponse->donor->blood_group }}</span>
                                </div>
                                @if($bloodRequest->appointment)
                                    <div class="col-sm-6 col-md-4">
                                        <span class="text-muted d-block">Appointment Date:</span>
                                        <strong class="d-block text-dark">
                                            {{ \Carbon\Carbon::parse($bloodRequest->appointment->appointment_date)->format('M d, Y') }}
                                        </strong>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <span class="text-muted d-block">Appointment Time:</span>
                                        <strong class="d-block text-dark">
                                            {{ \Carbon\Carbon::parse($bloodRequest->appointment->appointment_time)->format('h:i A') }}
                                        </strong>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <span class="text-muted d-block">Appointment Location:</span>
                                        <strong class="d-block text-dark">{{ $bloodRequest->appointment->location }}</strong>
                                    </div>
                                @endif
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block">Hospital:</span>
                                    <strong class="d-block text-dark">{{ $bloodRequest->hospital->hospital_name ?? $bloodRequest->location }}</strong>
                                </div>
                            </div>

                            @if($bloodRequest->acceptedResponse->status === 'completed')
                                {{-- Completed Confirmation Details --}}
                                <div class="mt-3 pt-3 border-top">
                                    <div class="row g-2 small">
                                        <div class="col-sm-6">
                                            <span class="text-muted">Confirmed by:</span>
                                            <strong class="d-block text-primary">
                                                {{ $bloodRequest->acceptedResponse->confirmedByUser->name ?? 'Hospital / Admin' }}
                                            </strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted">Completed at:</span>
                                            <span class="d-block text-dark fw-medium">
                                                {{ $bloodRequest->acceptedResponse->completed_at ? $bloodRequest->acceptedResponse->completed_at->format('M d, Y \a\t h:i A') : '—' }}
                                            </span>
                                        </div>
                                        @if($bloodRequest->acceptedResponse->confirmation_notes)
                                            <div class="col-12 mt-2">
                                                <span class="text-muted">Confirmation Notes:</span>
                                                <p class="mb-0 text-dark small bg-white p-2 rounded border">{{ $bloodRequest->acceptedResponse->confirmation_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($bloodRequest->acceptedResponse->status === 'accepted' && (!$bloodRequest->hospital_id || $bloodRequest->hospital_id === $hospital->id || Auth::user()->isAdmin()))
                                {{-- F17 — Confirm Completed Donation Form --}}
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i data-lucide="check-circle" width="16" class="text-success"></i>Confirm Completed Donation
                                    </h6>
                                    <p class="text-muted small mb-3">Once the donor donates blood at the hospital, confirm the completed donation to update records, hospital logs, and donation history.</p>

                                    <form method="POST" action="{{ route('hospital.donations.confirm', $bloodRequest->acceptedResponse->id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="confirmation_notes" class="form-label small text-muted fw-medium">Confirmation Notes (Optional)</label>
                                            <textarea id="confirmation_notes" name="confirmation_notes" class="form-control form-control-sm rounded-3" rows="2" placeholder="e.g. 1 unit collected successfully, donor vitals stable."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm"
                                                onclick="return confirm('Are you sure you want to confirm this donation as completed?');">
                                            <i data-lucide="check-check" width="16" class="me-1"></i>Confirm Completed Donation
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ============================================================
                         F16 — Hospital Management Panel
                         ============================================================ --}}
                    <hr class="my-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="building-2" width="18" class="text-primary"></i>Hospital Management Panel
                    </h6>

                    {{-- Assign / Unassign --}}
                    @if($bloodRequest->hospital_id === $hospital->id)
                        {{-- Already managing this request --}}
                        <div class="alert border-0 rounded-3 p-3 mb-3 d-flex align-items-center gap-3"
                             style="background:linear-gradient(90deg,#eff6ff,#fff); border-left:4px solid #3b82f6 !important;">
                            <i data-lucide="check-circle" class="text-primary" width="22" height="22"></i>
                            <div>
                                <strong class="text-dark">Your hospital is managing this request.</strong>
                                <p class="text-muted small mb-0">You can update the status or unassign below.</p>
                            </div>
                        </div>

                        {{-- Status Update Form --}}
                        @if(in_array($bloodRequest->status, ['pending','accepted','fulfilled','cancelled']))
                            <form method="POST" action="{{ route('hospital.requests.status', $bloodRequest->id) }}" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <label class="form-label text-muted small fw-medium">Update Status</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <select name="status" class="form-select form-select-sm rounded-pill" style="max-width:200px;">
                                        <option value="pending"   {{ $bloodRequest->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                        <option value="accepted"  {{ $bloodRequest->status === 'accepted'  ? 'selected' : '' }}>Accepted</option>
                                        <option value="fulfilled" {{ $bloodRequest->status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                        <option value="cancelled" {{ $bloodRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">
                                        <i data-lucide="save" width="14" class="me-1"></i>Update Status
                                    </button>
                                </div>
                            </form>
                        @endif

                        {{-- Unassign Button --}}
                        <form method="POST" action="{{ route('hospital.requests.unassign', $bloodRequest->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium"
                                    onclick="return confirm('Remove your hospital from managing this request?')">
                                <i data-lucide="x-circle" width="14" class="me-1"></i>Unassign My Hospital
                            </button>
                        </form>

                    @elseif($bloodRequest->hospital_id)
                        {{-- Managed by another hospital --}}
                        <div class="alert border-0 rounded-3 p-3 bg-light d-flex align-items-center gap-3">
                            <i data-lucide="building-2" class="text-muted" width="22" height="22"></i>
                            <div>
                                <strong class="text-secondary">This request is managed by another hospital.</strong>
                                <p class="text-muted small mb-0">You cannot assign yourself while another hospital is managing it.</p>
                            </div>
                        </div>

                    @else
                        {{-- Not yet assigned — hospital can take it --}}
                        <div class="p-4 rounded-3 text-center mb-3"
                             style="background:linear-gradient(135deg,#eff6ff 0%,#fff 100%); border:1.5px dashed #93c5fd;">
                            <i data-lucide="building-2" width="32" height="32" class="text-primary mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">This request is not yet assigned to any hospital.</h6>
                            <p class="text-muted small mb-3">Assign your hospital to manage this request and coordinate the donation process.</p>
                            <form method="POST" action="{{ route('hospital.requests.assign', $bloodRequest->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                                    <i data-lucide="plus-circle" width="16" class="me-1"></i>Assign My Hospital to This Request
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Navigation Footer --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-5 pt-3 border-top">
                        <a href="{{ route('hospital.requests.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-medium">
                            <i data-lucide="arrow-left" width="16" class="me-1"></i>Back to All Requests
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-medium">
                            <i data-lucide="layout-dashboard" width="16" class="me-1"></i>Dashboard
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
