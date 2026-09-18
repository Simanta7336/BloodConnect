<x-guest-layout>
    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label text-muted fw-medium small">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-medium small">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-muted small">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a class="auth-link small" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary-custom">Log in</button>
        
        <div class="text-center mt-4">
            <span class="text-muted small">Don't have an account? </span>
            <a href="{{ route('register') }}" class="auth-link small">Register here</a>
        </div>
    </form>
</x-guest-layout>
