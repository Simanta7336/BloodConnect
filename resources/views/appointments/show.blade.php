<x-app-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8 col-md-10">

                {{-- Success Message --}}
                @if(session('status') === 'appointment-created')
                    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4"
                         role="alert">

                        <div class="d-flex align-items-center">

                            <i data-lucide="check-circle-2"
                               class="me-2 text-success"
                               width="22"
                               height="22">
                            </i>

                            <div>
                                <strong>Appointment Confirmed!</strong>
                                <div class="small">
                                    Your blood donation appointment has been successfully scheduled.
                                </div>
                            </div>

                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>

                    </div>
                @endif


                {{-- Appointment Card --}}
                <div class="card border-0"
                     style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.08); overflow: hidden;">

                    {{-- Header --}}
                    <div class="card-header border-0 text-white p-4"
                         style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);">

                        <div class="d-flex align-items-center">

                            <div class="bg-white text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px;">

                                <i data-lucide="calendar-check"
                                   width="26"
                                   height="26">
                                </i>

                            </div>

                            <div>

                                <h4 class="mb-1 fw-bold">
                                    Donation Appointment
                                </h4>

                                <p class="mb-0 opacity-75">
                                    Your blood donation has been scheduled successfully.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Appointment Details --}}
                    <div class="card-body p-4">

                        <div class="mb-4">

                            <h5 class="fw-bold text-dark mb-1">
                                Appointment Details
                            </h5>

                            <p class="text-muted mb-0">
                                Please keep these details for your donation.
                            </p>

                        </div>


                        {{-- Date --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="calendar"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Date
                                </div>

                                <div class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                </div>

                            </div>

                        </div>


                        {{-- Time --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="clock"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Time
                                </div>

                                <div class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </div>

                            </div>

                        </div>


                        {{-- Location --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="map-pin"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Location
                                </div>

                                <div class="fw-bold text-dark">
                                    {{ $appointment->location }}
                                </div>

                            </div>

                        </div>


                        {{-- Donor --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="user"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Donor
                                </div>

                                <div class="fw-bold text-dark">
                                    {{ $appointment->donor->name }}
                                </div>

                                @if($appointment->donor->phone)
                                    <div class="text-muted small">
                                        {{ $appointment->donor->phone }}
                                    </div>
                                @endif

                            </div>

                        </div>


                        {{-- Recipient --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="user-round"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Recipient
                                </div>

                                <div class="fw-bold text-dark">
                                    {{ $appointment->recipient->name }}
                                </div>

                            </div>

                        </div>


                        {{-- Blood Request --}}
                        <div class="d-flex align-items-center py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="droplet"
                                   width="21"
                                   height="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Blood Group
                                </div>

                                <div class="fw-bold text-danger">
                                    {{ $appointment->bloodRequest->blood_group }}
                                </div>

                            </div>

                        </div>


                        @if($appointment->notes)
                            <div class="d-flex align-items-center border-top py-3">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px;">
                                    <i data-lucide="file-text" width="21" height="21"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Special Notes / Instructions</div>
                                    <div class="text-dark small">{{ $appointment->notes }}</div>
                                </div>
                            </div>
                        @endif

                        {{-- Status --}}
                        <div class="mt-4 p-3 rounded-3"
                             style="background: #fdf0f0;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">
                                        Appointment Status
                                    </div>
                                    <div class="fw-bold {{ $appointment->status === 'completed' ? 'text-primary' : ($appointment->status === 'cancelled' ? 'text-secondary' : 'text-success') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </div>
                                </div>

                                @if($appointment->status === 'completed')
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        Completed
                                    </span>
                                @elseif($appointment->status === 'cancelled')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        Confirmed
                                    </span>
                                @endif
                            </div>

                            @if($appointment->status === 'completed' && $appointment->bloodRequest && $appointment->bloodRequest->acceptedResponse && $appointment->bloodRequest->acceptedResponse->completed_at)
                                <div class="mt-3 pt-2 border-top small text-muted">
                                    <span>Donation confirmed completed on <strong>{{ $appointment->bloodRequest->acceptedResponse->completed_at->format('M d, Y \a\t h:i A') }}</strong></span>
                                    @if($appointment->bloodRequest->acceptedResponse->confirmedByUser)
                                        <span>by <strong>{{ $appointment->bloodRequest->acceptedResponse->confirmedByUser->name }}</strong></span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Appointment Action Buttons --}}
                        @if($appointment->status === 'confirmed')
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                        <i data-lucide="check-check" width="17" class="me-1"></i> Mark as Completed
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold">
                                        <i data-lucide="x" width="17" class="me-1"></i> Cancel
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Back Navigation --}}
                        <div class="d-flex justify-content-center gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('blood-requests.show', $appointment->blood_request_id) }}"
                               class="btn btn-outline-secondary px-4 py-2"
                               style="border-radius: 50px; font-weight: 500;">
                                <i data-lucide="droplet" width="17" class="me-1"></i> View Blood Request
                            </a>

                            <a href="{{ route('dashboard') }}"
                               class="btn btn-danger px-4 py-2"
                               style="border-radius: 50px; font-weight: 600;">
                                <i data-lucide="layout-dashboard" width="17" class="me-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>