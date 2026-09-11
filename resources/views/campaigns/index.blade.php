<x-app-layout>

    <div class="container py-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold text-dark mb-1">
                    Blood Donation Campaigns
                </h2>

                <p class="text-muted mb-0">
                    Find and participate in upcoming blood donation campaigns.
                </p>
            </div>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('campaigns.create') }}"
                   class="btn btn-danger rounded-pill px-4 py-2 fw-semibold">

                    <i data-lucide="plus-circle"
                       width="17"
                       class="me-1">
                    </i>

                    Create Campaign

                </a>
            @endif

        </div>


        {{-- Success Messages --}}
        @if(session('status') === 'campaign-created')

            <div class="alert alert-success border-0 rounded-3 shadow-sm">
                <i data-lucide="check-circle-2"
                   width="18"
                   class="me-2">
                </i>

                Campaign created successfully.
            </div>

        @elseif(session('status') === 'campaign-updated')

            <div class="alert alert-success border-0 rounded-3 shadow-sm">
                <i data-lucide="check-circle-2"
                   width="18"
                   class="me-2">
                </i>

                Campaign updated successfully.
            </div>

        @elseif(session('status') === 'campaign-deleted')

            <div class="alert alert-success border-0 rounded-3 shadow-sm">
                <i data-lucide="check-circle-2"
                   width="18"
                   class="me-2">
                </i>

                Campaign deleted successfully.
            </div>

        @endif


        {{-- Campaigns --}}
        @if($campaigns->count() > 0)

            <div class="row g-4">

                @foreach($campaigns as $campaign)

                    <div class="col-lg-6">

                        <div class="card border-0 h-100"
                             style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.08); overflow: hidden;">

                            <div class="card-body p-4">

                                {{-- Title --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">

                                    <div>

                                        <h4 class="fw-bold text-dark mb-1">
                                            {{ $campaign->title }}
                                        </h4>

                                        <div class="text-muted small">
                                            Organized by {{ $campaign->organizer }}
                                        </div>

                                    </div>


                                    {{-- Status --}}
                                    @if($campaign->status === 'upcoming')

                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            Upcoming
                                        </span>

                                    @elseif($campaign->status === 'ongoing')

                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            Ongoing
                                        </span>

                                    @elseif($campaign->status === 'completed')

                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            Cancelled
                                        </span>

                                    @endif

                                </div>


                                {{-- Description --}}
                                <p class="text-muted mb-4">
                                    {{ \Illuminate\Support\Str::limit($campaign->description, 150) }}
                                </p>


                                {{-- Date --}}
                                <div class="d-flex align-items-center mb-3">

                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 42px; height: 42px;">

                                        <i data-lucide="calendar"
                                           width="20">
                                        </i>

                                    </div>

                                    <div>

                                        <div class="text-muted small">
                                            Date
                                        </div>

                                        <div class="fw-semibold">
                                            {{ $campaign->campaign_date->format('M d, Y') }}
                                        </div>

                                    </div>

                                </div>


                                {{-- Time --}}
                                <div class="d-flex align-items-center mb-3">

                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 42px; height: 42px;">

                                        <i data-lucide="clock"
                                           width="20">
                                        </i>

                                    </div>

                                    <div>

                                        <div class="text-muted small">
                                            Time
                                        </div>

                                        <div class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($campaign->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($campaign->end_time)->format('h:i A') }}
                                        </div>

                                    </div>

                                </div>


                                {{-- Location --}}
                                <div class="d-flex align-items-center mb-4">

                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 42px; height: 42px;">

                                        <i data-lucide="map-pin"
                                           width="20">
                                        </i>

                                    </div>

                                    <div>

                                        <div class="text-muted small">
                                            Location
                                        </div>

                                        <div class="fw-semibold">
                                            {{ $campaign->location }}
                                        </div>

                                    </div>

                                </div>


                                {{-- View Button --}}
                                <a href="{{ route('campaigns.show', $campaign->id) }}"
                                   class="btn btn-outline-danger rounded-pill px-4 fw-semibold">

                                    View Campaign

                                    <i data-lucide="arrow-right"
                                       width="16"
                                       class="ms-1">
                                    </i>

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body text-center py-5">

                    <i data-lucide="calendar-x"
                       width="50"
                       height="50"
                       class="text-muted mb-3">
                    </i>

                    <h4 class="fw-bold">
                        No Campaigns Yet
                    </h4>

                    <p class="text-muted mb-0">
                        There are currently no blood donation campaigns available.
                    </p>

                </div>

            </div>

        @endif

    </div>

</x-app-layout>