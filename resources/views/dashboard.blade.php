<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05);">
                <div class="p-6 text-gray-900">

                    {{-- Success flash for blood request creation --}}
                    @if(session('status') === 'blood-request-created')
                        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                                <strong>Success!</strong> &nbsp;Your blood request has been submitted.
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Welcome back, {{ Auth::user()->name }}!</h4>
                            <p class="text-muted mb-0">You're logged in to BloodConnect.</p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- F12: Small notification alert indicator for donors --}}
                    @if(Auth::user()->role === 'donor' && Auth::user()->unreadNotifications->count() > 0)
                        <div class="alert border-0 rounded-3 shadow-sm d-flex align-items-center justify-content-between p-3 mb-4" style="background: linear-gradient(90deg, #fff5f5 0%, #ffffff 100%); border-left: 4px solid #dc3545 !important;">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                    <i data-lucide="bell-ring" width="18" height="18"></i>
                                </div>
                                <div>
                                    <strong class="text-danger">You have {{ Auth::user()->unreadNotifications->count() }} new blood donation request{{ Auth::user()->unreadNotifications->count() > 1 ? 's' : '' }}!</strong>
                                    <p class="text-muted mb-0 small">A recipient urgently needs blood matching your donor profile.</p>
                                </div>
                            </div>
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-medium shadow-sm">
                                View Alerts
                            </a>
                        </div>
                    @endif

                    <div class="row g-4 mt-2">
                        {{-- PB04: show the correct profile card based on the user's role --}}
                        @if(Auth::user()->role === 'hospital')
                            {{-- Sprint 4 F16: Hospital dashboard cards --}}
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#eff6ff 0%,#ffffff 100%);border-radius:1rem;">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;">
                                            <i data-lucide="clipboard-list" class="text-primary" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-primary mb-3">Manage Blood Requests</h5>
                                        <p class="text-muted mb-4">View and manage all blood donation requests. Assign your hospital and update request statuses.</p>
                                        <a href="{{ route('hospital.requests.index') }}" class="btn text-white px-4 py-2 w-100" style="background:linear-gradient(90deg,#2563eb,#1d4ed8);border-radius:50px;font-weight:600;">
                                            <i data-lucide="arrow-right" width="16" class="me-1"></i>View All Requests
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#f0fdf4 0%,#ffffff 100%);border-radius:1rem;border:1.5px dashed #86efac !important;">
                                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;">
                                            <i data-lucide="building-2" class="text-success" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-success mb-2">{{ Auth::user()->hospital->hospital_name ?? Auth::user()->name }}</h5>
                                        <p class="text-muted small mb-0">
                                            @if(Auth::user()->hospital?->is_verified)
                                                <span class="badge bg-success rounded-pill px-2 py-1"><i data-lucide="check-circle" width="12" class="me-1"></i>Verified Hospital</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1"><i data-lucide="clock" width="12" class="me-1"></i>Pending Verification</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                                                @elseif(Auth::user()->role === 'admin')
                            {{-- Sprint 4 F19: Admin dashboard & F20: Reports --}}
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#fdf0f0 0%,#ffffff 100%);border-radius:1rem;">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;">
                                            <i data-lucide="shield" class="text-danger" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-danger mb-2">Admin Dashboard</h5>
                                        <p class="text-muted mb-4">Manage users, approve hospital verifications, and monitor blood requests.</p>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger text-white px-4 py-2 w-100" style="border-radius:50px;font-weight:600;">
                                            <i data-lucide="arrow-right" width="16" class="me-1"></i>Open Admin Panel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#eff6ff 0%,#ffffff 100%);border-radius:1rem;">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;">
                                            <i data-lucide="bar-chart-2" class="text-primary" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-primary mb-2">Reports &amp; Statistics</h5>
                                        <p class="text-muted mb-4">View blood-group distributions, request fulfillment analytics, and trends.</p>
                                        <a href="{{ route('admin.reports.index') }}" class="btn text-white px-4 py-2 w-100" style="background:linear-gradient(90deg,#2563eb,#1d4ed8);border-radius:50px;font-weight:600;">
                                            <i data-lucide="arrow-right" width="16" class="me-1"></i>View Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#fdf0f0,#fff);border-radius:1rem; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.05);">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;">
                                            <i data-lucide="shield-check" class="text-danger" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-danger mb-1">Manage Campaigns</h5>
                                        <p class="text-muted mb-4 small">Oversee platform operations, blood drives, and community campaigns.</p>
                                        <a href="{{ route('campaigns.index') }}" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold w-100">
                                            <i data-lucide="megaphone" width="16" class="me-1"></i>Manage Campaigns
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background:linear-gradient(135deg,#fff5f5,#fff);border-radius:1rem; border: 1.5px dashed #dc354566 !important;">
                                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                                            <i data-lucide="plus-circle" class="text-danger" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-danger mb-2">Create New Campaign</h5>
                                        <p class="text-muted small mb-4">Schedule and publish a new community blood donation campaign or blood drive.</p>
                                        <a href="{{ route('campaigns.create') }}" class="btn btn-danger w-100 py-2" style="border-radius: 50px; font-weight: 600;">
                                            <i data-lucide="plus" width="16" class="me-1"></i>New Campaign
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif(Auth::user()->role === 'recipient')
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fdf0f0 0%, #ffffff 100%); border-radius: 1rem;">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="fw-bold text-danger mb-3">Your Recipient Profile</h5>
                                        <p class="text-muted mb-4">Keep your blood request details updated so we can match you with the right donor quickly.</p>
                                        <a href="{{ route('recipient.profile.edit') }}" class="btn text-white px-4 py-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); border-radius: 50px; font-weight: 600;">
                                            Manage Profile
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- F07: Create Blood Request card — only for recipients --}}
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border-radius: 1rem; border: 1.5px dashed #dc354566 !important;">
                                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                                            <i data-lucide="heart-handshake" class="text-danger" width="28" height="28"></i>
                                        </div>
                                        <h5 class="fw-bold text-danger mb-3">Create a Blood Request</h5>
                                        <p class="text-muted mb-4">Need blood urgently? Submit a request and our network of donors will be notified.</p>
                                        <a href="{{ route('blood-requests.create') }}" class="btn btn-danger w-100 py-2" style="border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.25);">
                                            <i data-lucide="plus-circle" width="18" class="me-2"></i>New Blood Request
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fdf0f0 0%, #ffffff 100%); border-radius: 1rem;">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="fw-bold text-danger mb-3">Your Donor Profile</h5>
                                        <p class="text-muted mb-3">Keep your availability and location updated to help us match you with urgent requests.</p>
                                        
                                        <div class="mb-4">
                                            @if(Auth::user()->isEligibleToDonate())
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-25" style="border-radius: 50px;">
                                                    <i data-lucide="check-circle" width="14" class="me-1"></i> Eligible to Donate
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-25" style="border-radius: 50px;">
                                                    <i data-lucide="clock" width="14" class="me-1"></i> Eligible on {{ Auth::user()->nextEligibleDonationDate()->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('donor.profile.edit') }}" class="btn text-white px-4 py-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); border-radius: 50px; font-weight: 600;">
                                            Manage Profile
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light" style="border-radius: 1rem;">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="fw-bold text-dark mb-3">Find Blood Nearby</h5>
                                        <p class="text-muted mb-4">Search our verified network of lifesavers in your area by specific criteria.</p>
                                        <a href="{{ route('donors.search') }}" class="btn btn-danger w-100 mb-2" style="border-radius: 50px; font-weight: 600;">
                                            <i data-lucide="search" width="18" class="me-2"></i>Search for a Donor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-md-12 mt-4">
                            <div class="card border-0" style="background: linear-gradient(135deg, #fdf0f0 0%, #ffffff 100%); border-radius: 1rem; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.05);">
                                <div class="card-body p-4 text-center d-flex flex-column flex-md-row align-items-center justify-content-between">
                                    <div class="text-start mb-3 mb-md-0">
                                        <h5 class="fw-bold text-danger mb-1">Donor Database Overview</h5>
                                        <p class="text-muted mb-0">Browse and filter our entire registry of registered blood donors.</p>
                                    </div>
                                    <a href="{{ route('donors.index') }}" class="btn btn-outline-danger px-4 py-2" style="border-radius: 50px; font-weight: 600;">
                                        <i data-lucide="users" width="18" class="me-2"></i>View Donor Database
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Blood Donation Campaigns Overview (Member 3) --}}
                        <div class="col-md-12 mt-4">
                            <div class="card border-0" style="background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border-radius: 1rem; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.05); border-left: 4px solid #2563eb !important;">
                                <div class="card-body p-4 text-center d-flex flex-column flex-md-row align-items-center justify-content-between">
                                    <div class="text-start mb-3 mb-md-0">
                                        <h5 class="fw-bold text-primary mb-1">Blood Donation Campaigns</h5>
                                        <p class="text-muted mb-0">Explore upcoming community blood drives and donation campaigns organized in your area.</p>
                                    </div>
                                    <a href="{{ route('campaigns.index') }}" class="btn btn-outline-primary px-4 py-2" style="border-radius: 50px; font-weight: 600;">
                                        <i data-lucide="megaphone" width="18" class="me-2"></i>View Campaigns
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Upcoming Donation Appointments (Member 3) --}}
                        @if(isset($appointments) && $appointments->isNotEmpty())
                            <div class="col-md-12 mt-4">
                                <div class="card border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(40, 167, 69, 0.08); overflow: hidden; border-left: 4px solid #198754 !important;">
                                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i data-lucide="calendar-check" width="20" height="20"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0 text-dark">Upcoming Donation Appointments</h5>
                                                <small class="text-muted">You have {{ $appointments->count() }} active scheduled appointment{{ $appointments->count() > 1 ? 's' : '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            @foreach($appointments as $app)
                                                <div class="col-md-6">
                                                    <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                <i data-lucide="calendar" width="16" class="text-success me-1"></i>
                                                                {{ \Carbon\Carbon::parse($app->appointment_date)->format('M d, Y') }} at {{ date('h:i A', strtotime($app->appointment_time)) }}
                                                            </div>
                                                            <div class="small text-muted mt-1">
                                                                <i data-lucide="map-pin" width="14" class="text-danger me-1"></i>{{ $app->location }}
                                                            </div>
                                                            <div class="small text-muted">
                                                                {{ Auth::user()->role === 'donor' ? 'Recipient: ' . ($app->recipient->name ?? 'Recipient') : 'Donor: ' . ($app->donor->name ?? 'Donor') }}
                                                                &bull; <span class="badge bg-danger rounded-pill">{{ $app->bloodRequest->blood_group ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('appointments.show', $app->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Blood Requests Listing with Priority Badges --}}
                        <div class="col-md-12 mt-4">
                            <div class="card border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                            <i data-lucide="activity" width="20" height="20"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">
                                                {{ Auth::user()->role === 'recipient' ? 'My Blood Requests' : 'Blood Requests' }}
                                            </h5>
                                            <small class="text-muted">
                                                {{ Auth::user()->role === 'recipient' ? 'Track your submitted blood requests and priority levels' : 'Recent blood requests needing donors' }}
                                            </small>
                                        </div>
                                    </div>
                                    @if(Auth::user()->role === 'recipient')
                                        <a href="{{ route('blood-requests.create') }}" class="btn btn-sm btn-danger rounded-pill px-3 py-2 fw-medium shadow-sm">
                                            <i data-lucide="plus" width="16" class="me-1"></i>New Request
                                        </a>
                                    @endif
                                </div>

                                <div class="card-body p-0">
                                    @php
                                        $displayedRequests = collect();
                                        if (Auth::user()->role === 'recipient') {
                                            $displayedRequests = $bloodRequests ? $bloodRequests->where('user_id', Auth::id()) : collect();
                                        } else {
                                            // Donor: Filter requests to show only ones they are compatible with
                                            $displayedRequests = $bloodRequests ? $bloodRequests->filter(function($req) {
                                                $compatibleGroups = \App\Models\BloodRequest::COMPATIBILITY[$req->blood_group] ?? [$req->blood_group];
                                                return in_array(Auth::user()->blood_group, $compatibleGroups);
                                            }) : collect();
                                        }
                                    @endphp

                                    @if($displayedRequests->isEmpty())
                                        <div class="text-center py-5">
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                                <i data-lucide="inbox" class="text-muted" width="28" height="28"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold mb-1">No Blood Requests Found</h6>
                                            <p class="text-muted small mb-0">
                                                {{ Auth::user()->role === 'recipient' ? 'You have not submitted any blood requests yet.' : 'No active blood requests at this time.' }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead style="background-color: #fdf0f0;">
                                                    <tr>
                                                        <th class="ps-4 py-3 text-dark small fw-bold">Patient Name</th>
                                                        <th class="py-3 text-dark small fw-bold">Blood Group</th>
                                                        <th class="py-3 text-dark small fw-bold">Units</th>
                                                        <th class="py-3 text-dark small fw-bold">Hospital / Location</th>
                                                        <th class="py-3 text-dark small fw-bold">Needed By</th>
                                                        <th class="py-3 text-dark small fw-bold">Priority</th>
                                                        <th class="py-3 text-dark small fw-bold">Status</th>
                                                        <th class="pe-4 py-3 text-dark small fw-bold text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($displayedRequests as $req)
                                                        <tr>
                                                            <td class="ps-4 py-3 fw-medium text-dark">{{ $req->patient_name }}</td>
                                                            <td class="py-3">
                                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.85rem; border-radius: 50px;">
                                                                    <i data-lucide="droplet" width="12" class="me-1"></i>{{ $req->blood_group }}
                                                                </span>
                                                            </td>
                                                            <td class="py-3 text-muted">{{ $req->units_required }} unit{{ $req->units_required > 1 ? 's' : '' }}</td>
                                                            <td class="py-3 text-muted">{{ $req->location }}</td>
                                                            <td class="py-3 text-muted">
                                                                {{ $req->needed_by_date ? \Carbon\Carbon::parse($req->needed_by_date)->format('M d, Y') : '—' }}
                                                            </td>
                                                            <td class="py-3">
                                                                @if($req->priority === 'emergency')
                                                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold shadow-sm">
                                                                        <i data-lucide="alert-circle" width="12" class="me-1"></i>Emergency
                                                                    </span>
                                                                @elseif($req->priority === 'urgent')
                                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">
                                                                        <i data-lucide="alert-triangle" width="12" class="me-1"></i>Urgent
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill">
                                                                        Normal
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="py-3">
                                                                <span class="badge bg-light text-capitalize text-dark border px-2 py-1 rounded-pill">
                                                                    {{ $req->status }}
                                                                </span>
                                                            </td>
                                                            <td class="pe-4 py-3 text-end">
                                                                <a href="{{ route('blood-requests.show', $req->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-medium">
                                                                    View
                                                                </a>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
