<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Donation History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05);">
                <div class="p-6 text-gray-900">

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i data-lucide="history" width="24" height="24"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Donation History</h4>
                            <p class="text-muted mb-0">
                                {{ Auth::user()->role === 'donor' ? 'A record of blood requests you have helped with.' : 'A record of your fulfilled blood requests.' }}
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-4">

                    @if($history->isEmpty())
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                <i data-lucide="inbox" class="text-muted" width="28" height="28"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-1">No History Found</h6>
                            <p class="text-muted small mb-0">
                                {{ Auth::user()->role === 'donor' ? 'You have not completed any donations yet.' : 'You do not have any fulfilled blood requests yet.' }}
                            </p>
                        </div>
                    @else
                        <div class="table-responsive mt-4">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #fdf0f0;">
                                    <tr>
                                        @if(Auth::user()->role === 'donor')
                                            <th class="ps-4 py-3 text-dark small fw-bold">Patient Name</th>
                                            <th class="py-3 text-dark small fw-bold">Recipient</th>
                                            <th class="py-3 text-dark small fw-bold">Blood Group</th>
                                            <th class="py-3 text-dark small fw-bold">Location</th>
                                            <th class="py-3 text-dark small fw-bold">Date</th>
                                            <th class="py-3 text-dark small fw-bold">Status</th>
                                        @else
                                            <th class="ps-4 py-3 text-dark small fw-bold">Patient Name</th>
                                            <th class="py-3 text-dark small fw-bold">Donor</th>
                                            <th class="py-3 text-dark small fw-bold">Blood Group</th>
                                            <th class="py-3 text-dark small fw-bold">Location</th>
                                            <th class="py-3 text-dark small fw-bold">Date</th>
                                            <th class="py-3 text-dark small fw-bold">Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $item)
                                        @php
                                            if (Auth::user()->role === 'donor') {
                                                $req = $item->bloodRequest;
                                                $date = $item->created_at;
                                                $status = $item->status;
                                            } else {
                                                $req = $item;
                                                $date = $item->created_at;
                                                $status = $item->status;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3 fw-medium text-dark">{{ $req->patient_name }}</td>
                                            @if(Auth::user()->role === 'donor')
                                                <td class="py-3 text-muted">{{ $req->user->name ?? 'Unknown' }}</td>
                                            @else
                                                <td class="py-3 text-muted">{{ $req->acceptedResponse->donor->name ?? 'Pending' }}</td>
                                            @endif
                                            <td class="py-3">
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.85rem; border-radius: 50px;">
                                                    <i data-lucide="droplet" width="12" class="me-1"></i>{{ $req->blood_group }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-muted">{{ $req->location }}</td>
                                            <td class="py-3 text-muted">
                                                {{ $date->format('M d, Y') }}
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill text-capitalize">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
