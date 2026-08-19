<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label text-muted fw-medium small">Full Name</label>
            <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="text-danger small mt-1" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-muted fw-medium small">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-medium small">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-muted fw-medium small">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger small mt-1" />
        </div>

        <button type="submit" class="btn btn-primary-custom">Register</button>
        
        <div class="text-center mt-4">
            <span class="text-muted small">Already have an account? </span>
            <a href="{{ route('login') }}" class="auth-link small">Log in here</a>
        </div>
    </form>
</x-guest-layout>
