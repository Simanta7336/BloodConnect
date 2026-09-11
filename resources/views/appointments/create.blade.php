<x-app-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                {{-- ========================================================= --}}
                {{-- PAGE HEADER --}}
                {{-- ========================================================= --}}

                <div class="text-center mb-4">

                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:56px;height:56px;">

                        <i data-lucide="calendar-plus"
                           width="28">
                        </i>

                    </div>

                    <h3 class="fw-bold mb-2">
                        Schedule Donation
                    </h3>

                    <p class="text-muted mb-0">
                        Choose a date, time, and location for the blood donation.
                    </p>

                </div>


                {{-- ========================================================= --}}
                {{-- VALIDATION ERRORS --}}
                {{-- ========================================================= --}}

                @if($errors->any())

                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">

                        <strong>
                            Please fix the following:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- ========================================================= --}}
                {{-- BLOOD REQUEST INFORMATION --}}
                {{-- ========================================================= --}}

                <div class="card border-0 rounded-4 shadow-sm mb-4">

                    <div class="card-header bg-danger text-white border-0 rounded-top-4 py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i data-lucide="droplet"
                               width="18"
                               class="me-2">
                            </i>

                            Blood Request

                        </h5>

                    </div>


                    <div class="card-body p-4">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <span class="text-muted small d-block mb-1">
                                    Patient
                                </span>

                                <strong>
                                    {{ $bloodRequest->patient_name }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <span class="text-muted small d-block mb-1">
                                    Blood Group
                                </span>

                                <strong class="text-danger">
                                    {{ $bloodRequest->blood_group }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <span class="text-muted small d-block mb-1">
                                    Units Required
                                </span>

                                <strong>
                                    {{ $bloodRequest->units_required }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <span class="text-muted small d-block mb-1">
                                    Donation Location
                                </span>

                                <strong>
                                    {{ $bloodRequest->location }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- ACCEPTED DONOR --}}
                {{-- ========================================================= --}}

                @if($bloodRequest->acceptedResponse && $bloodRequest->acceptedResponse->donor)

                    <div class="card border-0 rounded-4 shadow-sm mb-4">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-center">

                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width:48px;height:48px;">

                                    <i data-lucide="heart"
                                       width="22">
                                    </i>

                                </div>

                                <div>

                                    <span class="text-muted small d-block">
                                        Accepted Donor
                                    </span>

                                    <strong>
                                        {{ $bloodRequest->acceptedResponse->donor->name }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ========================================================= --}}
                {{-- APPOINTMENT FORM --}}
                {{-- ========================================================= --}}

                <div class="card border-0 rounded-4 shadow-sm">

                    <div class="card-header bg-light border-0 py-3">

                        <h5 class="mb-0 fw-semibold">
                            Donation Appointment Details
                        </h5>

                    </div>


                    <div class="card-body p-4 p-md-5">

                        <form method="POST"
                              action="{{ route('appointments.store') }}">

                            @csrf


                            {{-- Blood Request ID --}}

                            <input type="hidden"
                                   name="blood_request_id"
                                   value="{{ $bloodRequest->id }}">


                            {{-- ================================================= --}}
                            {{-- DATE --}}
                            {{-- ================================================= --}}

                            <div class="mb-4">

                                <label for="appointment_date"
                                       class="form-label fw-semibold">

                                    Donation Date

                                </label>

                                <input type="date"
                                       id="appointment_date"
                                       name="appointment_date"
                                       class="form-control rounded-3 @error('appointment_date') is-invalid @enderror"
                                       value="{{ old('appointment_date') }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>

                                @error('appointment_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ================================================= --}}
                            {{-- TIME --}}
                            {{-- ================================================= --}}

                            <div class="mb-4">

                                <label for="appointment_time"
                                       class="form-label fw-semibold">

                                    Donation Time

                                </label>

                                <input type="time"
                                       id="appointment_time"
                                       name="appointment_time"
                                       class="form-control rounded-3 @error('appointment_time') is-invalid @enderror"
                                       value="{{ old('appointment_time') }}"
                                       required>

                                @error('appointment_time')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ================================================= --}}
                            {{-- LOCATION --}}
                            {{-- ================================================= --}}

                            <div class="mb-4">

                                <label for="location"
                                       class="form-label fw-semibold">

                                    Donation Location

                                </label>

                                <input type="text"
                                       id="location"
                                       name="location"
                                       class="form-control rounded-3 @error('location') is-invalid @enderror"
                                       value="{{ old('location', $bloodRequest->location) }}"
                                       placeholder="Enter hospital or donation center"
                                       required>

                                @error('location')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    You can use the blood request location or enter a different donation location.
                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- INFORMATION MESSAGE --}}
                            {{-- ================================================= --}}

                            <div class="alert alert-info border-0 rounded-4 mb-4">

                                <div class="d-flex align-items-start">

                                    <i data-lucide="info"
                                       width="20"
                                       class="me-2 flex-shrink-0">
                                    </i>

                                    <div class="small">

                                        <strong>
                                            Appointment Confirmation
                                        </strong>

                                        <div class="mt-1">
                                            Once you confirm this appointment, the accepted donor will be able to view the donation date, time, and location.
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- BUTTONS --}}
                            {{-- ================================================= --}}

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top">

                                <a href="{{ route('blood-requests.show', $bloodRequest->id) }}"
                                   class="btn btn-outline-secondary rounded-pill px-4 py-2">

                                    <i data-lucide="arrow-left"
                                       width="16"
                                       class="me-1">
                                    </i>

                                    Back

                                </a>


                                <button type="submit"
                                        class="btn btn-success rounded-pill px-4 py-2">

                                    <i data-lucide="calendar-check"
                                       width="16"
                                       class="me-1">
                                    </i>

                                    Confirm Appointment

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>