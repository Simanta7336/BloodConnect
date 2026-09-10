<x-app-layout>
<div class="container py-5">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;flex-shrink:0;">
                <i data-lucide="clipboard-list" width="24" height="24"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold">Blood Request Management</h4>
                <p class="text-muted mb-0 small">{{ $hospital->hospital_name }} &mdash; Review and manage incoming blood requests</p>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
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
                You have unassigned yourself from that request.
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

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-3" style="border-radius:1rem; background:linear-gradient(135deg,#fff5f5,#fff);">
                <div class="fs-2 fw-bold text-danger">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total Requests</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-3" style="border-radius:1rem; background:linear-gradient(135deg,#fffbeb,#fff);">
                <div class="fs-2 fw-bold text-warning">{{ $stats['pending'] }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-3" style="border-radius:1rem; background:linear-gradient(135deg,#f0fdf4,#fff);">
                <div class="fs-2 fw-bold text-success">{{ $stats['accepted'] }}</div>
                <div class="text-muted small">Accepted</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-3" style="border-radius:1rem; background:linear-gradient(135deg,#eff6ff,#fff);">
                <div class="fs-2 fw-bold text-primary">{{ $stats['mine'] }}</div>
                <div class="text-muted small">Managed by You</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 mb-4 p-3" style="border-radius:1rem; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('hospital.requests.index') }}" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label text-muted small fw-medium mb-1">Status</label>
                <select name="status" class="form-select form-select-sm rounded-pill">
                    <option value="all"     {{ request('status','all') === 'all'      ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending"  {{ request('status') === 'pending'        ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ request('status') === 'accepted'       ? 'selected' : '' }}>Accepted</option>
                    <option value="fulfilled"{{ request('status') === 'fulfilled'      ? 'selected' : '' }}>Fulfilled</option>
                    <option value="cancelled"{{ request('status') === 'cancelled'      ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small fw-medium mb-1">Blood Group</label>
                <select name="blood_group" class="form-select form-select-sm rounded-pill">
                    <option value="all" {{ request('blood_group','all') === 'all' ? 'selected' : '' }}>All Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted small fw-medium mb-1">Priority</label>
                <select name="priority" class="form-select form-select-sm rounded-pill">
                    <option value="all"       {{ request('priority','all') === 'all'   ? 'selected' : '' }}>All Priorities</option>
                    <option value="emergency" {{ request('priority') === 'emergency'   ? 'selected' : '' }}>Emergency</option>
                    <option value="urgent"    {{ request('priority') === 'urgent'      ? 'selected' : '' }}>Urgent</option>
                    <option value="normal"    {{ request('priority') === 'normal'      ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-end">
                <div class="form-check ms-2 mb-1">
                    <input class="form-check-input" type="checkbox" name="mine" id="mine" value="1" {{ request('mine') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label text-muted small" for="mine">Only mine</label>
                </div>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-medium">
                    <i data-lucide="filter" width="14" class="me-1"></i>Filter
                </button>
                <a href="{{ route('hospital.requests.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Reset</a>
            </div>
        </form>
    </div>

    {{-- Requests Grid --}}
    @if($bloodRequests->isEmpty())
        <div class="text-center py-5">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:64px;height:64px;">
                <i data-lucide="inbox" class="text-muted" width="30" height="30"></i>
            </div>
            <h5 class="fw-bold text-dark">No requests found</h5>
            <p class="text-muted small">Try adjusting your filters above.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($bloodRequests as $req)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100 shadow-sm" style="border-radius:1rem; border-top: 3px solid {{ $req->priority === 'emergency' ? '#dc3545' : ($req->priority === 'urgent' ? '#ffc107' : '#6c757d') }} !important;">
                        <div class="card-body p-4">

                            {{-- Priority + Status --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                @if($req->priority === 'emergency')
                                    <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                        <i data-lucide="alert-circle" width="11" class="me-1"></i>Emergency
                                    </span>
                                @elseif($req->priority === 'urgent')
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                        <i data-lucide="alert-triangle" width="11" class="me-1"></i>Urgent
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">Normal</span>
                                @endif

                                @if($req->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">Pending</span>
                                @elseif($req->status === 'accepted')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">Accepted</span>
                                @elseif($req->status === 'fulfilled')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">Fulfilled</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-2 py-1 text-capitalize" style="font-size:0.7rem;">{{ $req->status }}</span>
                                @endif
                            </div>

                            {{-- Blood Group + Patient --}}
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                     style="width:44px;height:44px;font-size:0.8rem;background:linear-gradient(135deg,#dc3545,#c82333);">
                                    {{ $req->blood_group }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size:0.95rem;">{{ $req->patient_name }}</div>
                                    <div class="text-muted small">{{ $req->units_required }} unit{{ $req->units_required > 1 ? 's' : '' }} needed</div>
                                </div>
                            </div>

                            {{-- Location + Date --}}
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-1 text-muted small mb-1">
                                    <i data-lucide="map-pin" width="13" height="13"></i>
                                    <span>{{ $req->location }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-1 text-muted small">
                                    <i data-lucide="calendar" width="13" height="13"></i>
                                    <span>Needed by {{ $req->needed_by_date ? $req->needed_by_date->format('M d, Y') : '—' }}</span>
                                </div>
                            </div>

                            {{-- Managed by badge --}}
                            @if($req->hospital_id === $hospital->id)
                                <div class="d-flex align-items-center gap-1 mb-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                        <i data-lucide="building-2" width="11" class="me-1"></i>Managed by You
                                    </span>
                                </div>
                            @elseif($req->hospital_id)
                                <div class="d-flex align-items-center gap-1 mb-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                        <i data-lucide="building-2" width="11" class="me-1"></i>Managed by another hospital
                                    </span>
                                </div>
                            @endif

                            {{-- View Details Button --}}
                            <a href="{{ route('hospital.requests.show', $req->id) }}"
                               class="btn btn-sm w-100 rounded-pill fw-medium mt-auto"
                               style="background:linear-gradient(90deg,#dc3545,#c82333);color:white;">
                                <i data-lucide="eye" width="14" class="me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $bloodRequests->links() }}
        </div>
    @endif

</div>
</x-app-layout>
