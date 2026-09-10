<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            // 'name' is only required for donor/recipient (hospital uses hospital_name instead)
            'name'     => $request->role === 'hospital' ? ['nullable', 'string', 'max:255'] : ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'in:donor,recipient,hospital'],
        ];

        // Sprint 4 — Additional validation for hospital registration
        if ($request->role === 'hospital') {
            $rules['hospital_name']     = ['required', 'string', 'max:255'];
            $rules['hospital_address']  = ['nullable', 'string', 'max:500'];
            $rules['hospital_phone']    = ['nullable', 'string', 'max:20'];
            $rules['hospital_division'] = ['nullable', 'string', 'max:100'];
            $rules['hospital_district'] = ['nullable', 'string', 'max:100'];
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->role === 'hospital' ? $request->hospital_name : $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Sprint 4 — Create hospital profile if registering as hospital
        if ($request->role === 'hospital') {
            Hospital::create([
                'user_id' => $user->id,
                'hospital_name' => $request->hospital_name,
                'address' => $request->hospital_address,
                'contact_phone' => $request->hospital_phone,
                'contact_email' => $user->email,
                'division' => $request->hospital_division,
                'district' => $request->hospital_district,
                'is_verified' => false,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
