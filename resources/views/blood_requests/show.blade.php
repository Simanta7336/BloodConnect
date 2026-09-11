<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- Flash messages --}}
            @if(session('status') === 'donation-accepted')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                        <div>
                            <strong>Thank you!</strong> You have successfully accepted this blood donation request. Please contact the recipient below to coordinate the donation.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status') === 'donation-rejected')
                <div class="alert alert-secondary alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="x-circle" class="me-2 text-secondary"></i>
                        <div>
                            You have declined this blood donation request.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="alert-triangle" class="me-2 text-danger"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Main Request Card --}}
            <div class="card border-0 mb-4" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="heart-handshake"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Blood Donation Request Details</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Patient: <strong>{{ $bloodRequest->patient_name }}</strong></p>
                </div>

                <div class="card-body p-4 p-md-5">

                    {{-- Priority & Status Badges Bar --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-3 border-bottom">
                        <div>
                            <span class="text-muted small me-2">Priority Level:</span>
                            @if(strtolower($bloodRequest->priority) === 'emergency')
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold shadow-sm">
                                    <i data-lucide="alert-circle" width="12" class="me-1"></i>Emergency
                                </span>
                            @elseif(strtolower($bloodRequest->priority) === 'urgent')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">
                                    <i data-lucide="alert-triangle" width="12" class="me-1"></i>Urgent
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill">
                                    Normal
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-muted small me-2">Status:</span>
                            @if($bloodRequest->status === 'accepted')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                                    <i data-lucide="check-circle" width="12" class="me-1"></i>Accepted
                                </span>
                            @elseif($bloodRequest->status === 'pending')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill fw-medium">
                                    <i data-lucide="clock" width="12" class="me-1"></i>Pending
                                </span>
                            @else
                                <span class="badge bg-secondary text-capitalize px-3 py-1 rounded-pill">
                                    {{ $bloodRequest->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Request Details Grid --}}
                    <div class="row g-4 mb-4">
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
                                <span class="text-muted small d-block mb-1">Needed By Date</span>
                                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-1">
                                    <i data-lucide="calendar" width="16" class="text-muted"></i>
                                    {{ $bloodRequest->needed_by_date ? \Carbon\Carbon::parse($bloodRequest->needed_by_date)->format('M d, Y') : '—' }}
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
                                    <p class="mb-0 text-dark small" style="white-space: pre-line;">{{ $bloodRequest->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Donor Action / Response Section --}}
                    @if(Auth::user()->role === 'donor')
                        <hr class="my-4">

                        @if($donorResponse && $donorResponse->status === 'accepted')
                            {{-- State 1: Donor has accepted --}}
                            <div class="card border-0 rounded-4 p-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1.5px solid #86efac !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                        <i data-lucide="check" width="22" height="22"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-success mb-1">You Accepted this Donation Request</h5>
                                        <p class="text-muted small mb-3">Thank you for stepping up to save a life! Please coordinate with the recipient using the contact details below.</p>

                                        {{-- Recipient Contact Details (Disclosed ONLY after acceptance) --}}
                                        <div class="bg-white p-3 rounded-3 shadow-sm border">
                                            <h6 class="fw-bold text-dark mb-2">Recipient Contact Information</h6>
                                            <div class="row g-2 small">
                                                <div class="col-sm-6">
                                                    <span class="text-muted">Contact Name:</span>
                                                    <strong class="d-block text-dark">{{ $bloodRequest->user->name }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="text-muted">Phone Number:</span>
                                                    @if($bloodRequest->user->phone)
                                                        <div class="d-flex align-items-center gap-2 mt-1">
                                                            <a href="tel:{{ $bloodRequest->user->phone }}" class="btn btn-sm btn-success rounded-pill px-3 py-1">
                                                                <i data-lucide="phone" width="14" class="me-1"></i>{{ $bloodRequest->user->phone }}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <strong class="d-block text-muted">Not provided</strong>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mt-2">
                                                    <span class="text-muted">Email:</span>
                                                    <a href="mailto:{{ $bloodRequest->user->email }}" class="d-block text-primary text-decoration-none">
                                                        <i data-lucide="mail" width="14" class="me-1"></i>{{ $bloodRequest->user->email }}
                                                    </a>
                                                </div>
                                                <div class="col-sm-6 mt-2">
                                                    <span class="text-muted">Location:</span>
                                                    <span class="d-block text-dark">{{ $bloodRequest->user->location ?? $bloodRequest->location }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Appointment Status for Donor --}}
                                        @if($bloodRequest->appointment)
                                            <div class="mt-3 p-3 rounded-3 bg-white border border-success">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div>
                                                        <span class="badge bg-success mb-1">Appointment Scheduled</span>
                                                        <div class="small text-dark">
                                                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($bloodRequest->appointment->appointment_date)->format('M d, Y') }} &nbsp;|&nbsp;
                                                            <strong>Time:</strong> {{ date('h:i A', strtotime($bloodRequest->appointment->appointment_time)) }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <strong>Location:</strong> {{ $bloodRequest->appointment->location }}
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('appointments.show', $bloodRequest->appointment->id) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                        <i data-lucide="calendar-check" width="14" class="me-1"></i> View Details
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-3 p-2 rounded-3 bg-white border text-muted small d-flex align-items-center gap-2">
                                                <i data-lucide="clock" width="16" class="text-warning"></i>
                                                <span>The recipient will schedule the donation appointment date, time, and location with you shortly.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @elseif($donorResponse && $donorResponse->status === 'rejected')
                            {{-- State 2: Donor has rejected --}}
                            <div class="alert border-0 rounded-4 p-3 bg-light d-flex align-items-center gap-3" style="border-left: 4px solid #6c757d !important;">
                                <i data-lucide="x-circle" class="text-secondary" width="24" height="24"></i>
                                <div>
                                    <strong class="text-secondary">You declined this blood donation request.</strong>
                                    <p class="text-muted mb-0 small">Thank you for letting us know. You will not receive further reminders for this request.</p>
                                </div>
                            </div>

                        @elseif($bloodRequest->status === 'accepted')
                            {{-- State 3: Another donor already accepted --}}
                            <div class="alert border-0 rounded-4 p-3 d-flex align-items-center gap-3" style="background-color: #f0f9ff; border-left: 4px solid #0284c7 !important;">
                                <i data-lucide="info" class="text-info" width="24" height="24"></i>
                                <div>
                                    <strong class="text-dark">This blood request has already been accepted by another donor.</strong>
                                    <p class="text-muted mb-0 small">A fellow lifesaver has already volunteered for this donation. Thank you for your willingness to help!</p>
                                </div>
                            </div>

                        @elseif(in_array($bloodRequest->status, ['cancelled', 'fulfilled']))
                            {{-- State 4: Cancelled or fulfilled --}}
                            <div class="alert alert-secondary border-0 rounded-4 p-3">
                                <strong>This blood request is no longer active.</strong>
                            </div>

                        @else
                            {{-- State 5: Request is Pending & Donor hasn't responded -> Show Action Buttons --}}
                            <div class="card border-0 rounded-4 p-4 text-center" style="background: linear-gradient(135deg, #fdf0f0 0%, #ffffff 100%); border: 1.5px dashed #dc354566 !important;">
                                <h5 class="fw-bold text-dark mb-1">Can you donate blood for this request?</h5>
                                <p class="text-muted small mb-4">Your response will immediately inform the recipient.</p>

                                <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                                    {{-- Accept Form --}}
                                    <form method="POST" action="{{ route('blood-requests.accept', $bloodRequest->id) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2 shadow-sm" style="min-width: 160px; justify-content: center;">
                                            <i data-lucide="heart" width="18"></i> Accept Donation
                                        </button>
                                    </form>

                                    {{-- Reject Form --}}
                                    <form method="POST" action="{{ route('blood-requests.reject', $bloodRequest->id) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2" style="min-width: 160px; justify-content: center;">
                                            <i data-lucide="x" width="18"></i> Decline / Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    @elseif(Auth::user()->role === 'recipient' && $bloodRequest->user_id === Auth::id())
                        {{-- Recipient Creator Overview --}}
                        <hr class="my-4">
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <h6 class="fw-bold text-dark mb-2">Donor Responses Overview</h6>
                            @if($bloodRequest->status === 'accepted' && $bloodRequest->acceptedResponse)
                                <div class="alert alert-success border-0 rounded-3 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i data-lucide="check-circle" class="text-success"></i>
                                        <div>
                                            <strong>Accepted by:</strong> {{ $bloodRequest->acceptedResponse->donor->name }}
                                            @if($bloodRequest->acceptedResponse->donor->phone)
                                                ({{ $bloodRequest->acceptedResponse->donor->phone }})
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Appointment Section for Recipient --}}
                                @if($bloodRequest->appointment)
                                    <div class="card border-0 rounded-3 p-3 mb-0" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1.5px solid #86efac !important;">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <i data-lucide="calendar-check" class="text-success" width="18"></i>
                                                    <strong class="text-success">Donation Appointment Scheduled</strong>
                                                    <span class="badge bg-success rounded-pill">{{ ucfirst($bloodRequest->appointment->status) }}</span>
                                                </div>
                                                <div class="small text-dark">
                                                    <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($bloodRequest->appointment->appointment_date)->format('M d, Y') }}</span>
                                                    <span class="ms-3"><strong>Time:</strong> {{ date('h:i A', strtotime($bloodRequest->appointment->appointment_time)) }}</span>
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    <i data-lucide="map-pin" width="14" class="text-danger me-1"></i>{{ $bloodRequest->appointment->location }}
                                                </div>
                                            </div>
                                            <a href="{{ route('appointments.show', $bloodRequest->appointment->id) }}" class="btn btn-success rounded-pill px-3 py-1 fw-semibold shadow-sm">
                                                <i data-lucide="eye" width="14" class="me-1"></i> View Appointment
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="card border-0 rounded-3 p-3 mb-0" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border: 1.5px dashed #dc354588 !important;">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-bold text-danger mb-1"><i data-lucide="calendar-plus" class="me-1"></i> Schedule Donation Appointment</h6>
                                                <p class="text-muted small mb-0">A donor has accepted your request. Please schedule the donation date, time, and location.</p>
                                            </div>
                                            <a href="{{ route('appointments.create', $bloodRequest->id) }}" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm">
                                                <i data-lucide="calendar-plus" width="16" class="me-1"></i> Schedule Appointment
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted small mb-0">Waiting for matching donors to respond. You will be notified as soon as a donor accepts.</p>
                            @endif
                        </div>

                        {{-- F11: Matched Donors Panel --}}
                        <div class="mt-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <i data-lucide="users" width="18" height="18"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Matched Donors</h6>
                                    <small class="text-muted">Available donors compatible with <strong>{{ $bloodRequest->blood_group }}</strong> blood group</small>
                                </div>
                                @if($matchedDonors->isNotEmpty())
                                    <span class="badge bg-danger rounded-pill ms-auto">{{ $matchedDonors->count() }} found</span>
                                @endif
                            </div>

                            @if($matchedDonors->isEmpty())
                                {{-- Empty State --}}
                                <div class="text-center py-4 border rounded-3 bg-light">
                                    <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 56px; height: 56px;">
                                        <i data-lucide="user-x" class="text-muted" width="26" height="26"></i>
                                    </div>
                                    <h6 class="text-dark fw-bold mb-1">No Matching Donors Found</h6>
                                    <p class="text-muted small mb-0">No available donors with a compatible blood group were found at this time.<br>Donors will be notified when they register or become available.</p>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($matchedDonors as $donor)
                                        <div class="col-md-6">
                                            <div class="card border-0 h-100 shadow-sm" style="border-radius: 1rem; border: 1.5px solid {{ $donor->is_exact_match ? '#dc354533' : '#e9ecef' }} !important; background: {{ $donor->is_exact_match ? 'linear-gradient(135deg, #fff5f5 0%, #ffffff 100%)' : '#ffffff' }};">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start gap-3">
                                                        {{-- Avatar / Blood Group Icon --}}
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white"
                                                             style="width: 44px; height: 44px; font-size: 0.75rem; background: {{ $donor->is_exact_match ? 'linear-gradient(135deg, #dc3545, #c82333)' : 'linear-gradient(135deg, #6c757d, #495057)' }};">
                                                            {{ $donor->blood_group }}
                                                        </div>

                                                        <div class="flex-grow-1 min-width-0">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                                <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $donor->name }}</span>
                                                                @if($donor->is_exact_match)
                                                                    <span class="badge bg-danger text-white rounded-pill" style="font-size: 0.7rem;">
                                                                        <i data-lucide="zap" width="10" class="me-1"></i>Perfect Match
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill" style="font-size: 0.7rem;">
                                                                        Compatible
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Location --}}
                                                            @if($donor->location)
                                                                <div class="d-flex align-items-center gap-1 text-muted mb-2" style="font-size: 0.82rem;">
                                                                    <i data-lucide="map-pin" width="13" height="13"></i>
                                                                    <span>{{ $donor->location }}</span>
                                                                </div>
                                                            @endif

                                                            {{-- Phone --}}
                                                            @if($donor->phone)
                                                                <a href="tel:{{ $donor->phone }}"
                                                                   class="btn btn-sm rounded-pill px-3 py-1 fw-medium d-inline-flex align-items-center gap-1"
                                                                   style="font-size: 0.8rem; background: {{ $donor->is_exact_match ? '#dc3545' : '#6c757d' }}; color: white; text-decoration: none;">
                                                                    <i data-lucide="phone" width="13" height="13"></i>
                                                                    {{ $donor->phone }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                                    <i data-lucide="phone-off" width="13" class="me-1"></i>No phone listed
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <p class="text-muted mt-3 mb-0" style="font-size: 0.8rem;">
                                    <i data-lucide="info" width="13" class="me-1"></i>
                                    Showing {{ $matchedDonors->count() }} compatible donor{{ $matchedDonors->count() > 1 ? 's' : '' }} sorted by location proximity and blood group match.
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Navigation Footer --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-5 pt-3 border-top">
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-medium">
                            <i data-lucide="arrow-left" width="16" class="me-1"></i> Back to Notifications
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-medium">
                            <i data-lucide="layout-dashboard" width="16" class="me-1"></i> Dashboard
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
