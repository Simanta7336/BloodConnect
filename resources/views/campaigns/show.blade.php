<x-app-layout>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                @if(session('status') === 'campaign-updated')

                    <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">

                        <i data-lucide="check-circle-2"
                           width="18"
                           class="me-2">
                        </i>

                        Campaign updated successfully.

                    </div>

                @endif


                <div class="card border-0"
                     style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.08); overflow: hidden;">

                    {{-- Header --}}
                    <div class="card-header border-0 text-white p-4"
                         style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <h3 class="fw-bold mb-1">
                                    {{ $campaign->title }}
                                </h3>

                                <p class="mb-0 opacity-75">
                                    Blood Donation Campaign
                                </p>

                            </div>


                            @if($campaign->status === 'upcoming')

                                <span class="badge bg-light text-primary rounded-pill px-3 py-2">
                                    Upcoming
                                </span>

                            @elseif($campaign->status === 'ongoing')

                                <span class="badge bg-light text-success rounded-pill px-3 py-2">
                                    Ongoing
                                </span>

                            @elseif($campaign->status === 'completed')

                                <span class="badge bg-light text-secondary rounded-pill px-3 py-2">
                                    Completed
                                </span>

                            @else

                                <span class="badge bg-light text-danger rounded-pill px-3 py-2">
                                    Cancelled
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="card-body p-4">

                        {{-- Description --}}
                        <div class="mb-4">

                            <h5 class="fw-bold">
                                About This Campaign
                            </h5>

                            <p class="text-muted mb-0">
                                {{ $campaign->description }}
                            </p>

                        </div>


                        {{-- Date --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="calendar"
                                   width="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Date
                                </div>

                                <div class="fw-bold">
                                    {{ $campaign->campaign_date->format('M d, Y') }}
                                </div>

                            </div>

                        </div>


                        {{-- Time --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="clock"
                                   width="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Time
                                </div>

                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($campaign->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($campaign->end_time)->format('h:i A') }}
                                </div>

                            </div>

                        </div>


                        {{-- Location --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="map-pin"
                                   width="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Location
                                </div>

                                <div class="fw-bold">
                                    {{ $campaign->location }}
                                </div>

                            </div>

                        </div>


                        {{-- Organizer --}}
                        <div class="d-flex align-items-center border-bottom py-3">

                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="building-2"
                                   width="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Organizer
                                </div>

                                <div class="fw-bold">
                                    {{ $campaign->organizer }}
                                </div>

                            </div>

                        </div>


                        {{-- Contact --}}
                        <div class="d-flex align-items-center py-3">

                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">

                                <i data-lucide="phone"
                                   width="21">
                                </i>

                            </div>

                            <div>

                                <div class="text-muted small">
                                    Contact
                                </div>

                                <div class="fw-bold">
                                    {{ $campaign->contact }}
                                </div>

                            </div>

                        </div>


                        {{-- Admin Controls --}}
                        @if(auth()->user()->role === 'admin')

                            <div class="border-top pt-4 mt-3">

                                <div class="d-flex gap-2">

                                    <a href="{{ route('campaigns.edit', $campaign->id) }}"
                                       class="btn btn-warning rounded-pill px-4 fw-semibold">

                                        <i data-lucide="pencil"
                                           width="16"
                                           class="me-1">
                                        </i>

                                        Edit

                                    </a>


                                    <form method="POST"
                                          action="{{ route('campaigns.destroy', $campaign->id) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this campaign?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger rounded-pill px-4 fw-semibold">

                                            <i data-lucide="trash-2"
                                               width="16"
                                               class="me-1">
                                            </i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endif


                        <div class="text-center mt-4">

                            <a href="{{ route('campaigns.index') }}"
                               class="btn btn-light rounded-pill px-4">

                                <i data-lucide="arrow-left"
                                   width="17"
                                   class="me-1">
                                </i>

                                Back to Campaigns

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>