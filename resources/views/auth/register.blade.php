<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Full Name (shown only for donor/recipient) --}}
        <div class="mb-3" id="name-field">
            <label for="name" class="form-label text-muted fw-medium small">Full Name</label>
            <input id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   type="text" name="name" value="{{ old('name') }}" autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="text-danger small mt-1" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-muted fw-medium small">Email Address</label>
            <input id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-medium small">Password</label>
            <input id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-muted fw-medium small">Confirm Password</label>
            <input id="password_confirmation" class="form-control"
                   type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger small mt-1" />
        </div>

        {{-- Role Selection --}}
        <div class="mb-4">
            <label class="form-label text-muted fw-medium small">I want to register as a:</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_donor"
                           value="donor" {{ old('role', 'donor') === 'donor' ? 'checked' : '' }}
                           onchange="toggleHospitalFields()">
                    <label class="form-check-label text-dark" for="role_donor">Donor</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_recipient"
                           value="recipient" {{ old('role') === 'recipient' ? 'checked' : '' }}
                           onchange="toggleHospitalFields()">
                    <label class="form-check-label text-dark" for="role_recipient">Recipient</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_hospital"
                           value="hospital" {{ old('role') === 'hospital' ? 'checked' : '' }}
                           onchange="toggleHospitalFields()">
                    <label class="form-check-label text-dark" for="role_hospital">Hospital</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('role')" class="text-danger small mt-1" />
        </div>

        {{-- Sprint 4 — Hospital-specific registration fields --}}
        <div id="hospital-fields" style="display: none;">
            <hr class="mb-3">
            <p class="text-muted small mb-3 fw-medium">
                <i data-lucide="building-2" style="width:14px;height:14px;display:inline-block;vertical-align:middle;"></i>
                Hospital Information
            </p>

            <div class="mb-3">
                <label for="hospital_name" class="form-label text-muted fw-medium small">
                    Hospital Name <span class="text-danger">*</span>
                </label>
                <input id="hospital_name" class="form-control {{ $errors->has('hospital_name') ? 'is-invalid' : '' }}"
                       type="text" name="hospital_name" value="{{ old('hospital_name') }}" />
                <x-input-error :messages="$errors->get('hospital_name')" class="text-danger small mt-1" />
            </div>

            <div class="mb-3">
                <label for="hospital_address" class="form-label text-muted fw-medium small">Address</label>
                <input id="hospital_address" class="form-control"
                       type="text" name="hospital_address" value="{{ old('hospital_address') }}" />
            </div>

            <div class="mb-3">
                <label for="hospital_phone" class="form-label text-muted fw-medium small">Contact Phone</label>
                <input id="hospital_phone" class="form-control"
                       type="text" name="hospital_phone" value="{{ old('hospital_phone') }}" />
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="hospital_division" class="form-label text-muted fw-medium small">Division</label>
                    <input id="hospital_division" class="form-control"
                           type="text" name="hospital_division" value="{{ old('hospital_division') }}" />
                </div>
                <div class="col-md-6">
                    <label for="hospital_district" class="form-label text-muted fw-medium small">District</label>
                    <input id="hospital_district" class="form-control"
                           type="text" name="hospital_district" value="{{ old('hospital_district') }}" />
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary-custom">Register</button>

        <div class="text-center mt-4">
            <span class="text-muted small">Already have an account? </span>
            <a href="{{ route('login') }}" class="auth-link small">Log in here</a>
        </div>
    </form>

    <script>
        function toggleHospitalFields() {
            const isHospital = document.getElementById('role_hospital').checked;
            const hospitalFields = document.getElementById('hospital-fields');
            const nameField = document.getElementById('name-field');
            const nameInput = document.getElementById('name');

            if (isHospital) {
                hospitalFields.style.display = 'block';
                nameField.style.display = 'none';
                // Disable the name input so it doesn't get submitted
                nameInput.disabled = true;
                nameInput.removeAttribute('required');
            } else {
                hospitalFields.style.display = 'none';
                nameField.style.display = 'block';
                // Re-enable the name input
                nameInput.disabled = false;
                nameInput.setAttribute('required', 'required');
            }
        }

        // Run on page load to handle old() values (e.g. validation failed and returned)
        document.addEventListener('DOMContentLoaded', function () {
            toggleHospitalFields();
            // If validation returned with hospital role, keep hospital fields visible
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole && selectedRole.value === 'hospital') {
                toggleHospitalFields();
            }
        });
    </script>
</x-guest-layout>
