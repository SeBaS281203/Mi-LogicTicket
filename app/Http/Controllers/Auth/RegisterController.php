<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthRedirectService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private AuthRedirectService $authRedirect
    ) {}

    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect($this->authRedirect->defaultUrlForUser(Auth::user()));
        }
        return redirect()->route('home', ['auth' => 'register']);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $isOrganizer = ($validated['role'] ?? 'client') === 'organizer';

        $user = User::create([
            'name' => $fullName,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'],
            'gender' => $validated['gender'],
            'ruc' => $isOrganizer ? ($validated['ruc'] ?? null) : null,
            'organization_name' => $isOrganizer ? ($validated['organization_name'] ?? null) : null,
            'organization_address' => $isOrganizer ? ($validated['organization_address'] ?? null) : null,
            'marketing_consent' => (bool) ($validated['marketing_consent'] ?? false),
            'terms_accepted_at' => now(),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->authRedirect->redirectAfterRegister(Auth::user(), redirect());
    }
}
