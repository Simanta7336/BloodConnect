<x-app-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0"
                     style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.08); overflow: hidden;">

                    <div class="card-header border-0 text-white p-4"
                         style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);">

                        <h4 class="fw-bold mb-1">
                            Create Blood Donation Campaign
                        </h4>

                        <p class="mb-0 opacity-75">
                            Add the details of your upcoming blood donation campaign.
                        </p>

                    </div>


                    <div class="card-body p-4">

                        @if($errors->any())

                            <div class="alert alert-danger rounded-3">

                                <strong>Please fix the following errors:</strong>

                                <ul class="mb-0 mt-2">

                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form method="POST"
                              action="{{ route('campaigns.store') }}">

                            @csrf


                            {{-- Title --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Campaign Title
                                </label>

                                <input type="text"
                                       name="title"
                                       value="{{ old('title') }}"
                                       class="form-control"
                                       placeholder="e.g. Blood Donation Drive 2026"
                                       required>

                            </div>


                            {{-- Description --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="5"
                                          class="form-control"
                                          placeholder="Describe the campaign..."
                                          required>{{ old('description') }}</textarea>

                            </div>


                            {{-- Date --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Campaign Date
                                </label>

                                <input type="date"
                                       name="campaign_date"
                                       value="{{ old('campaign_date') }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="row">

                                {{-- Start Time --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Start Time
                                    </label>

                                    <input type="time"
                                           name="start_time"
                                           value="{{ old('start_time') }}"
                                           class="form-control"
                                           required>

                                </div>


                                {{-- End Time --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        End Time
                                    </label>

                                    <input type="time"
                                           name="end_time"
                                           value="{{ old('end_time') }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- Location --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Location
                                </label>

                                <input type="text"
                                       name="location"
                                       value="{{ old('location') }}"
                                       class="form-control"
                                       placeholder="e.g. BRAC University Campus"
                                       required>

                            </div>


                            {{-- Organizer --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Organizer
                                </label>

                                <input type="text"
                                       name="organizer"
                                       value="{{ old('organizer') }}"
                                       class="form-control"
                                       placeholder="Organization or person name"
                                       required>

                            </div>


                            {{-- Contact --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Contact Information
                                </label>

                                <input type="text"
                                       name="contact"
                                       value="{{ old('contact') }}"
                                       class="form-control"
                                       placeholder="Phone number or email"
                                       required>

                            </div>


                            {{-- Status --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select"
                                        required>

                                    <option value="upcoming"
                                        {{ old('status', 'upcoming') === 'upcoming' ? 'selected' : '' }}>
                                        Upcoming
                                    </option>

                                    <option value="ongoing"
                                        {{ old('status') === 'ongoing' ? 'selected' : '' }}>
                                        Ongoing
                                    </option>

                                    <option value="completed"
                                        {{ old('status') === 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled"
                                        {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                            </div>


                            {{-- Buttons --}}
                            <div class="d-flex gap-2">

                                <a href="{{ route('campaigns.index') }}"
                                   class="btn btn-light px-4 py-2 rounded-pill">

                                    Cancel

                                </a>

                                <button type="submit"
                                        class="btn btn-danger px-4 py-2 rounded-pill fw-semibold">

                                    <i data-lucide="plus-circle"
                                       width="17"
                                       class="me-1">
                                    </i>

                                    Create Campaign

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>