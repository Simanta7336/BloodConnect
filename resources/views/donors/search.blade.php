<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 mb-4" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="search"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Find a Blood Donor</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Search our network of lifesavers by blood group and location.</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="GET" action="{{ route('donors.search') }}" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="blood_group" class="form-label text-muted fw-medium small">Blood Group</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i data-lucide="droplet" class="text-danger" width="18"></i></span>
                                <select class="form-select border-start-0 ps-0" id="blood_group" name="blood_group" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                    <option value="">Any Blood Group</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ (isset($bloodGroup) && $bloodGroup == $bg) ? 'selected' : '' }}>
                                            {{ $bg }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="location" class="form-label text-muted fw-medium small">Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i data-lucide="map-pin" class="text-muted" width="18"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="location" name="location" value="{{ $location ?? '' }}" placeholder="City, Area, or Zip Code" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-danger w-100 py-3 d-flex justify-content-center align-items-center gap-2" style="border-radius: 0.75rem; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);">
                                <i data-lucide="search" width="18"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(request()->has('blood_group') || request()->has('location'))
                <div class="card border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
                    <div class="card-body p-0">
                        @if($donors->isEmpty())
                            <div class="text-center py-5 px-3">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i data-lucide="user-x" class="text-muted" width="32" height="32"></i>
                                </div>
                                <h5 class="text-dark fw-bold mb-2">No Donors Found</h5>
                                <p class="text-muted">We couldn't find any available donors matching your criteria. Please try broadening your search.</p>
                            </div>
                        @else
                            <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center rounded-top-4">
                                <h5 class="mb-0 fw-bold text-dark">Search Results ({{ $donors->count() }})</h5>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                                    <i data-lucide="check-circle" width="14" class="me-1"></i> Available Now
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-3 text-uppercase text-muted small fw-semibold">Donor Name</th>
                                            <th class="py-3 text-uppercase text-muted small fw-semibold">Blood Group</th>
                                            <th class="py-3 text-uppercase text-muted small fw-semibold">Location</th>
                                            <th class="pe-4 py-3 text-uppercase text-muted small fw-semibold text-end">Contact</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($donors as $donor)
                                            <tr>
                                                <td class="ps-4 py-3 fw-medium text-dark">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 40px; height: 40px;">
                                                            {{ substr($donor->name, 0, 1) }}
                                                        </div>
                                                        {{ $donor->name }}
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.85rem;">
                                                        <i data-lucide="droplet" width="12" class="me-1"></i>{{ $donor->blood_group }}
                                                    </span>
                                                </td>
                                                <td class="py-3 text-muted">
                                                    <i data-lucide="map-pin" width="14" class="me-1 text-secondary"></i>
                                                    {{ $donor->location ?? 'Not Specified' }}
                                                </td>
                                                <td class="pe-4 py-3 text-end">
                                                    @if($donor->phone)
                                                        <a href="tel:{{ $donor->phone }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                            <i data-lucide="phone" width="14" class="me-1"></i> {{ $donor->phone }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">Not provided</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
</x-app-layout>