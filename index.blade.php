<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            {{-- Page Header --}}
            <div class="card border-0 mb-4" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="users"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Donor Database</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Browse and sort registered blood donors by availability and other criteria</p>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="card border-0 mb-4" style="border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.04);">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i data-lucide="filter" width="18" class="text-muted"></i>
                            <span class="fw-semibold text-muted small text-uppercase">Filter by Availability:</span>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="{{ route('donors.index', array_merge(request()->query(), ['availability' => 'all', 'page' => 1])) }}"
                               class="btn btn-sm {{ $availability === 'all' ? 'btn-danger' : 'btn-outline-danger' }}" style="border-radius: 50px 0 0 50px; padding: 0.4rem 1.2rem; font-weight: 500;">
                                All Donors
                            </a>
                            <a href="{{ route('donors.index', array_merge(request()->query(), ['availability' => 'available', 'page' => 1])) }}"
                               class="btn btn-sm {{ $availability === 'available' ? 'btn-success' : 'btn-outline-success' }}" style="padding: 0.4rem 1.2rem; font-weight: 500;">
                                <i data-lucide="check-circle" width="14" class="me-1"></i> Available
                            </a>
                            <a href="{{ route('donors.index', array_merge(request()->query(), ['availability' => 'unavailable', 'page' => 1])) }}"
                               class="btn btn-sm {{ $availability === 'unavailable' ? 'btn-secondary' : 'btn-outline-secondary' }}" style="border-radius: 0 50px 50px 0; padding: 0.4rem 1.2rem; font-weight: 500;">
                                <i data-lucide="x-circle" width="14" class="me-1"></i> Unavailable
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Donors Table --}}
            <div class="card border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-body p-0">
                    @if($donors->isEmpty())
                        <div class="text-center py-5">
                            <i data-lucide="users" width="48" height="48" class="text-muted mb-3 d-block mx-auto"></i>
                            <h5 class="text-muted fw-bold">No donors found</h5>
                            <p class="text-muted">Try adjusting your filter criteria.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr style="background-color: #fdf0f0;">
                                        @php
                                            $columns = [
                                                'name' => 'Name',
                                                'blood_group' => 'Blood Group',
                                                'location' => 'Location',
                                                'phone' => 'Phone',
                                                'last_donation_date' => 'Last Donation',
                                                'is_available' => 'Availability',
                                            ];
                                        @endphp
                                        @foreach($columns as $col => $label)
                                            @php
                                                $isActive = $sortBy === $col;
                                                $nextDir = ($isActive && $sortDir === 'asc') ? 'desc' : 'asc';
                                                $arrow = '';
                                                if ($isActive) {
                                                    $arrow = $sortDir === 'asc' ? '↑' : '↓';
                                                }
                                            @endphp
                                            <th class="px-4 py-3" style="border-bottom: 2px solid #f1d5d8;">
                                                <a href="{{ route('donors.index', array_merge(request()->query(), ['sort_by' => $col, 'sort_dir' => $nextDir])) }}"
                                                   class="text-decoration-none d-inline-flex align-items-center gap-1 {{ $isActive ? 'text-danger fw-bold' : 'text-dark' }}"
                                                   style="white-space: nowrap;">
                                                    {{ $label }}
                                                    @if($arrow)
                                                        <span class="text-danger">{{ $arrow }}</span>
                                                    @endif
                                                </a>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donors as $donor)
                                        <tr>
                                            <td class="px-4 py-3 fw-medium">{{ $donor->name }}</td>
                                            <td class="px-4 py-3">
                                                @if($donor->blood_group)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.85rem; border-radius: 50px;">
                                                        <i data-lucide="droplet" width="12" class="me-1"></i>{{ $donor->blood_group }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-muted">{{ $donor->location ?? '—' }}</td>
                                            <td class="px-4 py-3 text-muted">{{ $donor->phone ?? '—' }}</td>
                                            <td class="px-4 py-3 text-muted">
                                                {{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('M d, Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($donor->is_available)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2" style="border-radius: 50px; font-weight: 500;">
                                                        <i data-lucide="check-circle" width="14" class="me-1"></i> Available
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2" style="border-radius: 50px; font-weight: 500;">
                                                        <i data-lucide="x-circle" width="14" class="me-1"></i> Unavailable
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($donors->hasPages())
                            <div class="d-flex justify-content-center py-4 border-top">
                                {{ $donors->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i data-lucide="info" width="14" class="text-muted me-1 d-inline-block"></i>
                    Showing {{ $donors->firstItem() ?? 0 }}–{{ $donors->lastItem() ?? 0 }} of {{ $donors->total() }} donors
                </p>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
