<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-0" style="border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05);">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Welcome back, {{ Auth::user()->name }}!</h4>
                            <p class="text-muted mb-0">You're logged in to BloodConnect.</p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fdf0f0 0%, #ffffff 100%); border-radius: 1rem;">
                                <div class="card-body p-4 text-center">
                                    <h5 class="fw-bold text-danger mb-3">Your Donor Profile</h5>
                                    <p class="text-muted mb-4">Keep your availability and location updated to help us match you with urgent requests.</p>
                                    <a href="{{ route('donor.profile.edit') }}" class="btn text-white px-4 py-2" style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); border-radius: 50px; font-weight: 600;">
                                        Manage Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light" style="border-radius: 1rem;">
                                <div class="card-body p-4 text-center">
                                    <h5 class="fw-bold text-dark mb-3">Find Blood Nearby</h5>
                                    <p class="text-muted mb-4">Looking for a donor? Search our verified network of lifesavers in your area.</p>
                                    <button class="btn btn-outline-danger px-4 py-2" style="border-radius: 50px; font-weight: 600;" disabled>
                                        Coming Soon
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
