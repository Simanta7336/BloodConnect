<x-app-layout>
<div class="container py-5">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;flex-shrink:0;">
                <i data-lucide="shield" width="24" height="24"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold">Admin Dashboard</h4>
                <p class="text-muted mb-0 small">Platform overview &mdash; manage users, hospitals, and blood requests</p>
            </div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-danger rounded-pill px-4 fw-medium">
            <i data-lucide="bar-chart-2" width="16" class="me-2"></i>View Reports
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('status') === 'hospital-verified')
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="check-circle-2" class="text-success"></i>
                <strong>Verified!</strong>&nbsp;The hospital has been approved.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('status') === 'hospital-rejected')
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="x-circle" class="text-danger"></i>
                The hospital application has been rejected and removed from the system.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('status') === 'hospital-revoked')
        <div class="alert alert-warning alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="alert-triangle" class="text-warning"></i>
                The hospital verification has been revoked.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── SECTION 1: Platform Stats ──────────────────────────────── --}}
    <h6 class="text-uppercase text-muted fw-bold small mb-3 mt-2">
        <i data-lucide="users" width="14" class="me-1"></i>User Overview
    </h6>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fff5f5,#fff);">
                <div class="fs-2 fw-bold text-danger">{{ $userStats['total'] }}</div>
                <div class="text-muted small">Total Users</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#f0fff4,#fff);">
                <div class="fs-2 fw-bold text-success">{{ $userStats['donors'] }}</div>
                <div class="text-muted small">Donors</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#eff6ff,#fff);">
                <div class="fs-2 fw-bold text-primary">{{ $userStats['recipients'] }}</div>
                <div class="text-muted small">Recipients</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fffbeb,#fff);">
                <div class="fs-2 fw-bold text-warning">{{ $userStats['hospitals'] }}</div>
                <div class="text-muted small">Hospitals</div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2: Blood Request Stats ────────────────────────── --}}
    <h6 class="text-uppercase text-muted fw-bold small mb-3">
        <i data-lucide="droplet" width="14" class="me-1"></i>Blood Request Overview
    </h6>
    <div class="row g-3 mb-5">
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fff5f5,#fff);">
                <div class="fs-2 fw-bold text-danger">{{ $requestStats['total'] }}</div>
                <div class="text-muted small">Total Requests</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fffbeb,#fff);">
                <div class="fs-2 fw-bold text-warning">{{ $requestStats['pending'] }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#eff6ff,#fff);">
                <div class="fs-2 fw-bold text-primary">{{ $requestStats['accepted'] }}</div>
                <div class="text-muted small">Accepted</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#f0fff4,#fff);">
                <div class="fs-2 fw-bold text-success">{{ $requestStats['fulfilled'] }}</div>
                <div class="text-muted small">Fulfilled</div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 text-center p-3 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#f5f5f5,#fff);">
                <div class="fs-2 fw-bold text-secondary">{{ $requestStats['cancelled'] }}</div>
                <div class="text-muted small">Cancelled</div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 3: Pending Hospital Verifications ──────────────── --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius:1rem;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between" style="border-radius:1rem 1rem 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="building-2" width="20" class="text-danger"></i>
                <h6 class="mb-0 fw-bold">Pending Hospital Verifications</h6>
            </div>
            @if($pendingHospitals->count() > 0)
                <span class="badge bg-danger rounded-pill">{{ $pendingHospitals->count() }} pending</span>
            @endif
        </div>
        <div class="card-body px-4 pb-4">
            @if($pendingHospitals->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i data-lucide="check-circle-2" width="40" height="40" class="text-success mb-2"></i>
                    <p class="mb-0">No hospitals pending verification.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hospital Name</th>
                                <th>Contact</th>
                                <th>Location</th>
                                <th>License No.</th>
                                <th>Registered</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingHospitals as $hospital)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $hospital->hospital_name }}</div>
                                    <div class="text-muted small">{{ $hospital->user->email ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $hospital->contact_phone ?? '—' }}</div>
                                    <div class="small text-muted">{{ $hospital->contact_email ?? '—' }}</div>
                                </td>
                                <td class="small">{{ $hospital->district }}, {{ $hospital->division }}</td>
                                <td class="small text-muted">{{ $hospital->license_number ?? '—' }}</td>
                                <td class="small text-muted">{{ $hospital->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('admin.hospitals.verify', $hospital->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-medium"
                                                onclick="return confirm('Verify {{ addslashes($hospital->hospital_name) }}?')">
                                                <i data-lucide="check" width="14" class="me-1"></i>Verify
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.hospitals.reject', $hospital->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium"
                                                onclick="return confirm('Reject {{ addslashes($hospital->hospital_name) }}?')">
                                                <i data-lucide="x" width="14" class="me-1"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ── SECTION 4: All Hospitals ──────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius:1rem;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2" style="border-radius:1rem 1rem 0 0;">
            <i data-lucide="hospital" width="20" class="text-danger"></i>
            <h6 class="mb-0 fw-bold">All Registered Hospitals</h6>
        </div>
        <div class="card-body px-4 pb-4">
            @if($allHospitals->isEmpty())
                <p class="text-muted text-center py-3 mb-0">No hospitals registered yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hospital Name</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allHospitals as $hospital)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $hospital->hospital_name }}</div>
                                    <div class="text-muted small">{{ $hospital->user->email ?? '—' }}</div>
                                </td>
                                <td class="small">{{ $hospital->district }}, {{ $hospital->division }}</td>
                                <td class="small">{{ $hospital->contact_phone ?? '—' }}</td>
                                <td>
                                    @if($hospital->is_verified)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            <i data-lucide="shield-check" width="12" class="me-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                            <i data-lucide="clock" width="12" class="me-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($hospital->is_verified)
                                            <form method="POST" action="{{ route('admin.hospitals.revoke', $hospital->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-medium"
                                                    onclick="return confirm('Revoke verification for {{ addslashes($hospital->hospital_name) }}?')">
                                                    <i data-lucide="x" width="14" class="me-1"></i>Revoke
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.hospitals.verify', $hospital->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-medium"
                                                    onclick="return confirm('Verify {{ addslashes($hospital->hospital_name) }}?')">
                                                    <i data-lucide="check" width="14" class="me-1"></i>Verify
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.hospitals.reject', $hospital->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium"
                                                onclick="return confirm('Delete and remove {{ addslashes($hospital->hospital_name) }}?')">
                                                <i data-lucide="trash-2" width="14" class="me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ── SECTION 5: Recent Blood Requests ────────────────────── --}}
    <div class="card border-0 shadow-sm" style="border-radius:1rem;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2" style="border-radius:1rem 1rem 0 0;">
            <i data-lucide="droplet" width="20" class="text-danger"></i>
            <h6 class="mb-0 fw-bold">Recent Blood Requests</h6>
        </div>
        <div class="card-body px-4 pb-4">
            @if($recentRequests->isEmpty())
                <p class="text-muted text-center py-3 mb-0">No blood requests yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Blood Group</th>
                                <th>Location</th>
                                <th>Priority</th>
                                <th>Hospital</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $req)
                            <tr>
                                <td>
                                    <div class="fw-semibold small">{{ $req->patient_name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">by {{ $req->user->name ?? '—' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">{{ $req->blood_group }}</span>
                                </td>
                                <td class="small text-muted">{{ $req->location }}</td>
                                <td>
                                    @if($req->priority === 'critical')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Critical</span>
                                    @elseif($req->priority === 'urgent')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2">Urgent</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">Normal</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $req->hospital->hospital_name ?? '—' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending'   => 'warning',
                                            'accepted'  => 'primary',
                                            'fulfilled' => 'success',
                                            'cancelled' => 'secondary',
                                        ];
                                        $color = $statusColors[$req->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }} rounded-pill px-2 text-capitalize">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $req->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
</x-app-layout>
