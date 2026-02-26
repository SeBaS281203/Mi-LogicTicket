<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'token' => ['required'],
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'token.required' => 'El token de restablecimiento es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico no tiene un formato válido.',
                'email.exists' => 'No encontramos ningún usuario registrado con ese correo.',
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('password.reset.success');
        }

        return back()->withErrors([
            'email' => ['Este enlace de restablecimiento no es válido o ha expirado. Solicita uno nuevo.'],
        ]);
    }
}
