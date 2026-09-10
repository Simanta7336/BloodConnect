<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            {{-- Success alert — same flash key and design as donor profile --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                        <strong>Success!</strong>&nbsp;Your profile has been updated successfully.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Profile card — identical structure, border-radius, shadow, and overflow to donor card --}}
            <div class="card" style="border: none; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); background: #ffffff; overflow: hidden;">

                {{-- Red gradient header --}}
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="heart-pulse"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Recipient Profile &amp; Blood Request</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Manage your information and blood request details</p>
                </div>

                <div class="card-body" style="padding: 2.5rem 2rem;">
                    <form method="POST" action="{{ route('recipient.profile.update') }}">
                        @csrf
                        @method('put')

                        <div class="row g-4">

                            {{-- Full Name --}}
                            <div class="col-md-12">
                                <label for="name" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="user-circle" class="text-muted" width="18"></i></span>
                                    <input type="text" id="name" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Blood Group — select, reuses existing users.blood_group column --}}
                            <div class="col-md-6">
                                <label for="blood_group" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Blood Group Needed</label>
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

                            {{-- Phone — reuses existing users.phone column --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="phone" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 01712345678" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date of Birth — new recipient field --}}
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="calendar" class="text-muted" width="18"></i></span>
                                    <input type="date" class="form-control border-start-0 ps-0 @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('date_of_birth')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gender — new recipient field --}}
                            <div class="col-md-6">
                                <label for="gender" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Gender</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="user" class="text-muted" width="18"></i></span>
                                    <select class="form-select border-start-0 ps-0 @error('gender') is-invalid @enderror" id="gender" name="gender" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                        <option value="">Select Gender</option>
                                        <option value="male"   {{ old('gender', $user->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other"  {{ old('gender', $user->gender) == 'other'  ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                @error('gender')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Division — new recipient field --}}
                            <div class="col-md-6">
                                <label for="division" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Division</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="map-pin" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('division') is-invalid @enderror" id="division" name="division" value="{{ old('division', $user->division) }}" placeholder="e.g. Dhaka" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('division')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- District — new recipient field --}}
                            <div class="col-md-6">
                                <label for="district" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">District</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="map" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district', $user->district) }}" placeholder="e.g. Mirpur" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('district')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Hospital Name — new recipient field --}}
                            <div class="col-md-12">
                                <label for="hospital_name" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Hospital Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="building-2" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('hospital_name') is-invalid @enderror" id="hospital_name" name="hospital_name" value="{{ old('hospital_name', $user->hospital_name) }}" placeholder="e.g. Dhaka Medical College Hospital" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('hospital_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Blood Units Needed — new recipient field --}}
                            <div class="col-md-6">
                                <label for="blood_units_needed" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Blood Units Needed</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="droplets" class="text-danger" width="18"></i></span>
                                    <input type="number" class="form-control border-start-0 ps-0 @error('blood_units_needed') is-invalid @enderror" id="blood_units_needed" name="blood_units_needed" value="{{ old('blood_units_needed', $user->blood_units_needed) }}" placeholder="e.g. 2" min="1" max="50" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('blood_units_needed')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Required By Date — new recipient field --}}
                            <div class="col-md-6">
                                <label for="required_by_date" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Required By Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="clock" class="text-muted" width="18"></i></span>
                                    <input type="date" class="form-control border-start-0 ps-0 @error('required_by_date') is-invalid @enderror" id="required_by_date" name="required_by_date" value="{{ old('required_by_date', $user->required_by_date) }}" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('required_by_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Location / Address — reuses existing users.location column --}}
                            <div class="col-md-12">
                                <label for="location" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">Location / Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i data-lucide="map-pin" class="text-muted" width="18"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $user->location) }}" placeholder="e.g. 15 Mirpur Road, Dhaka" style="border-radius: 0 0.75rem 0.75rem 0; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                </div>
                                @error('location')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr class="my-4 text-muted">

                        {{-- Medical Condition — new recipient field, placed below the divider for visual separation --}}
                        <div class="mb-4">
                            <label for="medical_condition" class="form-label" style="font-weight: 500; color: #555; margin-bottom: 0.5rem;">
                                <i data-lucide="clipboard" width="16" class="text-muted d-inline-block me-1"></i>
                                Medical Condition / Reason for Request
                            </label>
                            <textarea class="form-control @error('medical_condition') is-invalid @enderror" id="medical_condition" name="medical_condition" rows="3" placeholder="Briefly describe the medical condition or reason for the blood request..." style="border-radius: 0.75rem; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; background-color: #f8fafc; resize: vertical;">{{ old('medical_condition', $user->medical_condition) }}</textarea>
                            @error('medical_condition')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit button — identical style to donor profile --}}
                        <div class="text-center pt-2">
                            <button type="submit" class="btn w-100 py-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); color: white; border: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                                <i data-lucide="save" width="20"></i> Save Recipient Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer note — identical style to donor profile --}}
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i data-lucide="shield-check" width="14" class="text-success me-1 d-inline-block"></i>
                    Your data is secure and will only be shared when a verified donor match is found.
                </p>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
