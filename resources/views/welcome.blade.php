<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Connecting Lifesavers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #fdfdfc;
            color: #1b1b18;
            overflow-x: hidden;
        }
        .navbar {
            background-color: rgba(253, 253, 252, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(220, 53, 69, 0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #dc3545 !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .hero-section {
            min-height: 90vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #ffffff 0%, #fff2f2 100%);
            position: relative;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .text-primary-custom {
            color: #dc3545;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: #706f6c;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        .btn-custom {
            border-radius: 8px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary-custom {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }
        .btn-outline-custom {
            border: 2px solid #dc3545;
            color: #dc3545;
            background: transparent;
        }
        .btn-outline-custom:hover {
            background: #dc3545;
            color: white;
        }
        .feature-card {
            border: 1px solid rgba(220, 53, 69, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            background: white;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(220, 53, 69, 0.1);
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .bg-red-light { background-color: #fff2f2; color: #dc3545; }
        .bg-blue-light { background-color: #f0f4ff; color: #0d6efd; }
        .bg-green-light { background-color: #f0fdf4; color: #198754; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i data-lucide="heart" fill="white"></i>
            </div>
            BloodConnect
        </a>
        <div>
            @if (Route::has('login'))
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-custom">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn text-dark fw-medium">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary-custom">Sign up</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center bg-red-light rounded-pill px-3 py-1 mb-4 border border-danger border-opacity-10">
                    <span class="badge bg-danger rounded-pill me-2">New</span>
                    <span class="small fw-medium text-danger">GPS-Powered matching is live!</span>
                </div>
                <h1 class="hero-title">Connecting <span class="text-primary-custom">lifesavers</span>,<br>one drop at a time</h1>
                <p class="hero-subtitle">BloodConnect bridges the gap between blood donors and recipients through smart location-based matching and real-time alerts.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-primary-custom">
                        <i data-lucide="user-plus" width="20"></i> Join as Donor
                    </a>
                    <a href="#features" class="btn btn-outline-custom">
                        <i data-lucide="search" width="20"></i> Find Blood
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center relative">
                <img src="https://images.unsplash.com/photo-1615461066841-6116e61058f4?q=80&w=800&auto=format&fit=crop" alt="Blood Donation" class="img-fluid rounded-4 shadow-lg" style="border: 8px solid white;">
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Powerful Features for <span class="text-primary-custom">Life-Saving</span> Connections</h2>
            <p class="text-muted fs-5">Everything you need to coordinate blood donations securely and instantly.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-circle bg-blue-light">
                        <i data-lucide="map-pin" width="32"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Location-Based Search</h4>
                    <p class="text-muted mb-0">Find nearby donors instantly with our advanced matching system for faster response times in emergencies.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-circle bg-red-light">
                        <i data-lucide="shield-check" width="32"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Verified Profiles</h4>
                    <p class="text-muted mb-0">All donors are securely registered to ensure safe, compatible, and reliable blood donations for everyone.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-circle bg-green-light">
                        <i data-lucide="bell" width="32"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Instant Alerts</h4>
                    <p class="text-muted mb-0">Get notified immediately when your blood type is urgently needed at a nearby hospital or clinic.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-light py-4 border-top">
    <div class="container text-center text-muted small">
        &copy; {{ date('Y') }} BloodConnect. All rights reserved.
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Initialize Lucide Icons -->
<script>
  lucide.createIcons();
</script>
</body>
</html>
