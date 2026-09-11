```blade
<x-app-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0"
                     style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.08); overflow: hidden;">

                    <div class="card-header border-0 text-white p-4"
                         style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);">

                        <h4 class="fw-bold mb-1">
                            Edit Campaign
                        </h4>

                        <p class="mb-0 opacity-75">
                            Update the campaign information.
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
                              action="{{ route('campaigns.update', $campaign->id) }}">

                            @csrf

                            @method('PUT')


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Campaign Title
                                </label>

                                <input type="text"
                                       name="title"
                                       value="{{ old('title', $campaign->title) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="5"
                                          class="form-control"
                                          required>{{ old('description', $campaign->description) }}</textarea>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Campaign Date
                                </label>

                                <input type="date"
                                       name="campaign_date"
                                       value="{{ old('campaign_date', $campaign->campaign_date->format('Y-m-d')) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Start Time
                                    </label>

                                    <input type="time"
                                           name="start_time"
                                           value="{{ old('start_time', \Carbon\Carbon::parse($campaign->start_time)->format('H:i')) }}"
                                           class="form-control"
                                           required>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        End Time
                                    </label>

                                    <input type="time"
                                           name="end_time"
                                           value="{{ old('end_time', \Carbon\Carbon::parse($campaign->end_time)->format('H:i')) }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Location
                                </label>

                                <input type="text"
                                       name="location"
                                       value="{{ old('location', $campaign->location) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Organizer
                                </label>

                                <input type="text"
                                       name="organizer"
                                       value="{{ old('organizer', $campaign->organizer) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Contact Information
                                </label>

                                <input type="text"
                                       name="contact"
                                       value="{{ old('contact', $campaign->contact) }}"
                                       class="form-control"
                                       required>

                            </div>


                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select"
                                        required>

                                    <option value="upcoming"
                                        {{ old('status', $campaign->status) === 'upcoming' ? 'selected' : '' }}>
                                        Upcoming
                                    </option>

                                    <option value="ongoing"
                                        {{ old('status', $campaign->status) === 'ongoing' ? 'selected' : '' }}>
                                        Ongoing
                                    </option>

                                    <option value="completed"
                                        {{ old('status', $campaign->status) === 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled"
                                        {{ old('status', $campaign->status) === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                            </div>


                            <div class="d-flex gap-2">

                                <a href="{{ route('campaigns.show', $campaign->id) }}"
                                   class="btn btn-light px-4 py-2 rounded-pill">

                                    Cancel

                                </a>

                                <button type="submit"
                                        class="btn btn-danger px-4 py-2 rounded-pill fw-semibold">

                                    <i data-lucide="save"
                                       width="17"
                                       class="me-1">
                                    </i>

                                    Save Changes

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>