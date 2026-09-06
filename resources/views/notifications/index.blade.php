<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-11">

            {{-- Flash messages --}}
            @if(session('status') === 'notification-marked-read')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                        <span>Notification marked as read.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status') === 'all-notifications-marked-read')
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle-2" class="me-2 text-success"></i>
                        <span>All notifications marked as read.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Page Header --}}
            <div class="card border-0 mb-4" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05); overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545 0%, #e35d6a 100%); color: white; padding: 2rem 1.5rem; border-bottom: none; text-align: center;">
                    <div class="icon-wrapper bg-white text-danger" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i data-lucide="bell"></i>
                    </div>
                    <h4 style="font-weight: 600; margin: 0; font-size: 1.5rem;">Blood Donation Requests &amp; Alerts</h4>
                    <p class="text-white-50 mb-0 mt-1 small">Notifications for blood requests matching your donor profile</p>
                </div>
            </div>

            {{-- Top Action Bar --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 px-1">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">
                        Total: <strong>{{ $notifications->total() }}</strong> notification{{ $notifications->total() !== 1 ? 's' : '' }}
                    </span>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger rounded-pill px-2 py-1 small">
                            {{ Auth::user()->unreadNotifications->count() }} unread
                        </span>
                    @endif
                </div>

                @if(Auth::user()->unreadNotifications->count() > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-medium shadow-sm">
                            <i data-lucide="check-check" width="14" class="me-1"></i>Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            {{-- Notifications List --}}
            @if($notifications->isEmpty())
                <div class="card border-0 text-center py-5 rounded-4 shadow-sm bg-white">
                    <div class="card-body">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i data-lucide="bell-off" class="text-muted" width="28" height="28"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-1">No Notifications Yet</h5>
                        <p class="text-muted small mb-0">When a recipient submits a blood request matching your blood group, you will receive an alert here.</p>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = $notification->unread();
                            $priority = strtolower($data['priority'] ?? 'normal');
                        @endphp

                        <div class="card border-0 rounded-4 transition-all {{ $isUnread ? 'bg-white shadow-sm border-start border-4 border-danger' : 'bg-light border' }}" style="border-radius: 1rem;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i data-lucide="heart-pulse" width="18" height="18"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">
                                                {{ $data['title'] ?? 'New Blood Donation Request' }}
                                            </h6>
                                            <span class="text-muted small">
                                                Patient: <strong>{{ $data['patient_name'] ?? 'Recipient' }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Priority Badge --}}
                                        @if($priority === 'emergency')
                                            <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold shadow-sm">
                                                <i data-lucide="alert-circle" width="12" class="me-1"></i>Emergency
                                            </span>
                                        @elseif($priority === 'urgent')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">
                                                <i data-lucide="alert-triangle" width="12" class="me-1"></i>Urgent
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill">
                                                Normal
                                            </span>
                                        @endif

                                        {{-- Read / Unread Status --}}
                                        @if($isUnread)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill small">
                                                <i data-lucide="circle-dot" width="10" class="me-1"></i>Unread
                                            </span>
                                        @else
                                            <span class="text-muted small d-inline-flex align-items-center">
                                                <i data-lucide="check-check" width="14" class="text-success me-1"></i>Read
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Request Details --}}
                                <div class="row g-2 text-muted small mb-3">
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center gap-1">
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill">
                                            <i data-lucide="droplet" width="12" class="me-1"></i>{{ $data['blood_group'] ?? '—' }}
                                        </span>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center gap-1">
                                        <i data-lucide="flask-conical" width="14" class="text-secondary"></i>
                                        <span><strong>{{ $data['units_required'] ?? 1 }}</strong> unit(s) needed</span>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center gap-1">
                                        <i data-lucide="map-pin" width="14" class="text-secondary"></i>
                                        <span>{{ $data['location'] ?? 'Not specified' }}</span>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center gap-1">
                                        <i data-lucide="calendar" width="14" class="text-secondary"></i>
                                        <span>Needed: {{ $data['needed_by_date'] ?? 'As soon as possible' }}</span>
                                    </div>
                                </div>

                                {{-- Footer Bar --}}
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                                    <span class="text-muted small d-flex align-items-center gap-1">
                                        <i data-lucide="clock" width="12"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>

                                    <div class="d-flex align-items-center gap-2">
                                        @if(isset($data['blood_request_id']))
                                            <a href="{{ route('blood-requests.show', $data['blood_request_id']) }}" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-medium shadow-sm">
                                                <i data-lucide="heart-handshake" width="14" class="me-1"></i>View Request &amp; Respond
                                            </a>
                                        @endif

                                        @if($isUnread)
                                            <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
                                                    <i data-lucide="check" width="14" class="me-1"></i>Mark as read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div class="d-flex justify-content-center py-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
</x-app-layout>
