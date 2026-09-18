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
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .auth-card {
                background: white;
                border-radius: 1.5rem;
                box-shadow: 0 15px 35px rgba(220, 53, 69, 0.08);
                padding: 3rem;
                width: 100%;
                max-width: 450px;
                border: 1px solid rgba(220, 53, 69, 0.05);
            }
            .auth-logo {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
            }
            .form-control {
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
            }
            .form-control:focus {
                background-color: #ffffff;
                border-color: #dc3545;
                box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
            }
            .btn-primary-custom {
                background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
                color: white;
                border: none;
                border-radius: 50px;
                padding: 0.75rem;
                font-weight: 600;
                width: 100%;
                margin-top: 1rem;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
                color: white;
            }
            .auth-link {
                color: #dc3545;
                text-decoration: none;
                font-weight: 500;
            }
            .auth-link:hover {
                text-decoration: underline;
                color: #c82333;
            }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <a href="/" class="text-decoration-none">
                <div class="auth-logo">
                    <i data-lucide="heart" class="text-white" width="32" height="32"></i>
                </div>
                <h3 class="text-center fw-bold text-dark mb-4">BloodConnect</h3>
            </a>

            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show text-sm mb-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show text-sm mb-3" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot }}
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Initialize Lucide Icons -->
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
