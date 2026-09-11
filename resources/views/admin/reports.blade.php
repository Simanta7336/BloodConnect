<x-app-layout>
<div class="container py-5">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;flex-shrink:0;">
                <i data-lucide="bar-chart-2" width="24" height="24"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold">Reports &amp; Statistics</h4>
                <p class="text-muted mb-0 small">Blood-group statistics and platform analytics</p>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger rounded-pill px-4 fw-medium">
            <i data-lucide="arrow-left" width="16" class="me-2"></i>Back to Dashboard
        </a>
    </div>

    {{-- ── SECTION 1: Key Metrics ──────────────────────────────── --}}
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-4 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fff5f5,#fff);">
                <div class="fs-1 fw-bold text-danger">{{ $totalRequests }}</div>
                <div class="text-muted small fw-medium">Total Blood Requests</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-4 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#f0fff4,#fff);">
                <div class="fs-1 fw-bold text-success">{{ $totalFulfilled }}</div>
                <div class="text-muted small fw-medium">Fulfilled Requests</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-4 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#eff6ff,#fff);">
                <div class="fs-1 fw-bold text-primary">{{ $fulfillmentRate }}%</div>
                <div class="text-muted small fw-medium">Fulfillment Rate</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 text-center p-4 h-100" style="border-radius:1rem;background:linear-gradient(135deg,#fffbeb,#fff);">
                <div class="fs-1 fw-bold text-warning">{{ $availableDonors }}</div>
                <div class="text-muted small fw-medium">Available Donors</div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2: Blood Group Charts (side by side) ─────────── --}}
    <div class="row g-4 mb-5">
        {{-- Donors by Blood Group --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:1rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="users" width="18" class="text-danger"></i>
                        <h6 class="mb-0 fw-bold">Donors by Blood Group</h6>
                    </div>
                    <p class="text-muted small mb-0 mt-1">Number of registered donors per blood group</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <canvas id="donorChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Requests by Blood Group --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:1rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="droplet" width="18" class="text-danger"></i>
                        <h6 class="mb-0 fw-bold">Blood Requests by Blood Group</h6>
                    </div>
                    <p class="text-muted small mb-0 mt-1">Total vs fulfilled requests per blood group</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <canvas id="requestChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 3: Pie Charts ──────────────────────────────── --}}
    <div class="row g-4 mb-5">
        {{-- User Role Breakdown --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:1rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="pie-chart" width="18" class="text-danger"></i>
                        <h6 class="mb-0 fw-bold">User Role Breakdown</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                    <div style="max-width:280px; width:100%;">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Request Status Breakdown --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:1rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="pie-chart" width="18" class="text-danger"></i>
                        <h6 class="mb-0 fw-bold">Request Status Breakdown</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                    <div style="max-width:280px; width:100%;">
                        <canvas id="requestStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 4: Monthly Trend ─────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius:1rem;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="trending-up" width="18" class="text-danger"></i>
                <h6 class="mb-0 fw-bold">Monthly Blood Requests — {{ now()->format('Y') }}</h6>
            </div>
            <p class="text-muted small mb-0 mt-1">Number of blood requests submitted each month this year</p>
        </div>
        <div class="card-body px-4 pb-4">
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>

    {{-- ── SECTION 5: Blood Group Summary Table ────────────────── --}}
    <div class="card border-0 shadow-sm" style="border-radius:1rem;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4" style="border-radius:1rem 1rem 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="table" width="18" class="text-danger"></i>
                <h6 class="mb-0 fw-bold">Blood Group Summary Table</h6>
            </div>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Blood Group</th>
                            <th class="text-center">Registered Donors</th>
                            <th class="text-center">Total Requests</th>
                            <th class="text-center">Fulfilled</th>
                            <th class="text-center">Fulfillment Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bloodGroups as $i => $bg)
                        @php
                            $donors    = $donorCounts[$i];
                            $requests  = $requestCounts[$i];
                            $fulfilled = $fulfilledCounts[$i];
                            $rate      = $requests > 0 ? round(($fulfilled / $requests) * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold fs-6">{{ $bg }}</span>
                            </td>
                            <td class="text-center fw-semibold text-success">{{ $donors }}</td>
                            <td class="text-center fw-semibold">{{ $requests }}</td>
                            <td class="text-center fw-semibold text-primary">{{ $fulfilled }}</td>
                            <td class="text-center">
                                @if($requests > 0)
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <div class="progress flex-grow-1" style="height:8px;max-width:80px;">
                                            <div class="progress-bar bg-danger" style="width:{{ $rate }}%"></div>
                                        </div>
                                        <span class="small fw-medium text-muted">{{ $rate }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const bloodGroups   = @json($bloodGroups);
    const donorCounts   = @json($donorCounts);
    const requestCounts = @json($requestCounts);
    const fulfilledCounts = @json($fulfilledCounts);
    const monthNames    = @json($monthNames);
    const monthlyCounts = @json($monthlyCounts);

    const COLORS = ['#dc3545','#fd7e14','#ffc107','#198754','#0d6efd','#6f42c1','#d63384','#20c997'];

    // ── Donors by Blood Group (bar) ──────────────────────────────
    new Chart(document.getElementById('donorChart'), {
        type: 'bar',
        data: {
            labels: bloodGroups,
            datasets: [{
                label: 'Donors',
                data: donorCounts,
                backgroundColor: COLORS,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // ── Requests vs Fulfilled (grouped bar) ─────────────────────
    new Chart(document.getElementById('requestChart'), {
        type: 'bar',
        data: {
            labels: bloodGroups,
            datasets: [
                {
                    label: 'Total Requests',
                    data: requestCounts,
                    backgroundColor: 'rgba(220,53,69,0.7)',
                    borderRadius: 8,
                },
                {
                    label: 'Fulfilled',
                    data: fulfilledCounts,
                    backgroundColor: 'rgba(25,135,84,0.7)',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // ── User Role Pie ────────────────────────────────────────────
    new Chart(document.getElementById('userRoleChart'), {
        type: 'doughnut',
        data: {
            labels: ['Donors', 'Recipients', 'Hospitals', 'Admins'],
            datasets: [{
                data: [
                    {{ $userRoleBreakdown['donors'] }},
                    {{ $userRoleBreakdown['recipients'] }},
                    {{ $userRoleBreakdown['hospitals'] }},
                    {{ $userRoleBreakdown['admins'] }}
                ],
                backgroundColor: ['#198754','#0d6efd','#ffc107','#dc3545'],
                borderWidth: 2,
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // ── Request Status Pie ───────────────────────────────────────
    new Chart(document.getElementById('requestStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Accepted', 'Fulfilled', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $requestStatusBreakdown['pending'] }},
                    {{ $requestStatusBreakdown['accepted'] }},
                    {{ $requestStatusBreakdown['fulfilled'] }},
                    {{ $requestStatusBreakdown['cancelled'] }}
                ],
                backgroundColor: ['#ffc107','#0d6efd','#198754','#6c757d'],
                borderWidth: 2,
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // ── Monthly Trend (line) ─────────────────────────────────────
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Blood Requests',
                data: monthlyCounts,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.08)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#dc3545',
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
</x-app-layout>
