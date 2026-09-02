<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <div class="card border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="heart-handshake"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Create a Blood Request</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Fill in the details below so we can find a matching donor quickly.</p>
                </div>

                <div class="card-body" style="padding: 2.5rem 2rem;">
                    <form method="POST" action="{{ route('blood-requests.store') }}">
                        @csrf

                        <div class="row g-4">

                            {{-- Patient Name --}}
                            <div class="col-md-12">
                                <label for="patient_name" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Patient Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="user" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('patient_name') is-invalid @enderror" id="patient_name" name="patient_name" value="{{ old('patient_name') }}" placeholder="Full name of the patient" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('patient_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-md-6">
                                <label for="blood_group" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Blood Group Required</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="droplet" class="text-danger" width="18"></i></span>
                                    <select class="form-select border-start-0 ps-0 @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                        <option value="">Select Blood Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('blood_group')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Units Required --}}
                            <div class="col-md-6">
                                <label for="units_required" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Units Required</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="flask-conical" class="text-muted" width="18"></i></span>
                                    <input type="number" class="form-control border-start-0 ps-0 @error('units_required') is-invalid @enderror" id="units_required" name="units_required" value="{{ old('units_required', 1) }}" min="1" max="20" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('units_required')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Location --}}
                            <div class="col-md-12">
                                <label for="location" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Hospital / Location</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="map-pin" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="e.g. Dhaka Medical College Hospital, Dhaka" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('location')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Needed By Date --}}
                            <div class="col-md-6">
                                <label for="needed_by_date" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Needed By Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="calendar" class="text-muted" width="18"></i></span>
                                    <input type="date" class="form-control border-start-0 ps-0 @error('needed_by_date') is-invalid @enderror" id="needed_by_date" name="needed_by_date" value="{{ old('needed_by_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('needed_by_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Priority Level --}}
                            <div class="col-md-6">
                                <label for="priority" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Priority Level</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="alert-triangle" class="text-danger" width="18"></i></span>
                                    <select class="form-select border-start-0 ps-0 @error('priority') is-invalid @enderror" id="priority" name="priority" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                        <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="emergency" {{ old('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    </select>
                                </div>
                                @error('priority')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Additional Notes --}}
                            <div class="col-md-12">
                                <label for="notes" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Additional Notes <span class="text-muted fw-normal small">(optional)</span></label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Any additional information about the patient's condition or special requirements..." style="border-radius: 0.75rem; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-50 py-3 rounded-pill fw-medium">
                                Cancel
                            </a>
                            <button type="submit" class="btn w-50 py-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); color: white; border: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                                <i data-lucide="send" width="20"></i> Submit Request
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i data-lucide="shield-check" width="14" class="text-success me-1 d-inline-block"></i>
                    Your request will be visible to verified donors in our network.
                </p>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
