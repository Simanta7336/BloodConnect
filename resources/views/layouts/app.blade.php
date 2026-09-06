<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BloodConnect') }}</title>

        <!-- Google Fonts: Inter -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #ffffff 0%, #fdf0f0 100%);
                min-height: 100vh;
                color: #333;
            }
            .navbar {
                background-color: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(220, 53, 69, 0.1);
            }
            .navbar-brand {
                font-weight: 700;
                color: #dc3545 !important;
                display: flex;
                align-items: center;
                gap: 8px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <i data-lucide="droplet" class="text-danger"></i>
                    BloodConnect
                </a>
                <div class="d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="nav-link text-dark fw-medium me-4 {{ request()->routeIs('dashboard') ? 'text-danger' : '' }}">Dashboard</a>
                    <a href="{{ route('donors.index') }}" class="nav-link text-dark fw-medium me-4 {{ request()->routeIs('donors.*') ? 'text-danger' : '' }}">Donors</a>
                    {{-- PB04: route to the correct profile page based on the user's role --}}
                    @if(Auth::user()->role === 'recipient')
                        <a href="{{ route('recipient.profile.edit') }}" class="nav-link text-dark fw-medium me-4 {{ request()->routeIs('recipient.profile.*') ? 'text-danger' : '' }}">Profile</a>
                    @else
                        <a href="{{ route('donor.profile.edit') }}" class="nav-link text-dark fw-medium me-4 {{ request()->routeIs('donor.profile.*') ? 'text-danger' : '' }}">Profile</a>
                    @endif

                    {{-- F12: Notifications bell with unread badge --}}
                    <a href="{{ route('notifications.index') }}" class="nav-link text-dark position-relative me-4 {{ request()->routeIs('notifications.*') ? 'text-danger' : '' }}" title="Notifications">
                        <i data-lucide="bell" width="20" height="20"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    
                    <span class="text-muted me-3 fw-medium d-none d-sm-inline border-start ps-4">Hello, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Initialize Lucide Icons -->
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
