<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                        <strong>Success!</strong> &nbsp;Your profile has been updated successfully.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card" style="border: none; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); background: #ffffff; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="user"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Donor Profile & Availability</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Manage your personal information and donation status</p>
                </div>
                
                <div class="card-body" style="padding: 2.5rem 2rem;">
                    <form method="POST" action="{{ route('donor.profile.update') }}">
                        @csrf
                        @method('put')

                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="user-circle" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" value="{{ $user->name }}" disabled style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; background-color: #e9ecef; border: 1px solid #e2e8f0;">
                                </div>
                                <div class="form-text text-muted small"><i data-lucide="info" width="14" class="d-inline-block me-1"></i>Name cannot be changed here.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="blood_group" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Blood Group</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="droplet" class="text-danger" width="18"></i></span>
                                    <select class="form-select border-start-0 ps-0 @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                        <option value="">Select Blood Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>
                                                {{ $bg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('blood_group')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="phone" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 890" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="location" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Location / Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="map-pin" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $user->location) }}" placeholder="e.g. 123 Main St, City" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('location')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="last_donation_date" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Last Donation Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="calendar" class="text-muted" width="18"></i></span>
                                    <input type="date" class="form-control border-start-0 ps-0 @error('last_donation_date') is-invalid @enderror" id="last_donation_date" name="last_donation_date" value="{{ old('last_donation_date', $user->last_donation_date) }}" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('last_donation_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex align-items-center justify-content-between bg-light p-3 rounded-3 mb-4 border border-light">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">Available for Blood Donation</h6>
                                <p class="text-muted small mb-0">Turn this off if you are temporarily unavailable (e.g. recently donated).</p>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="is_available" value="0">
                                <input class="form-check-input fs-4 m-0" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $user->is_available) ? 'checked' : '' }} style="cursor:pointer;">
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn w-100 py-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); color: white; border: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                                <i data-lucide="save" width="20"></i> Save Profile Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i data-lucide="shield-check" width="14" class="text-success me-1 d-inline-block"></i>
                    Your data is secure and will only be shared when a verified emergency match is found.
                </p>
            </div>

        </div>
    </div>
</div>
</x-app-layout>

