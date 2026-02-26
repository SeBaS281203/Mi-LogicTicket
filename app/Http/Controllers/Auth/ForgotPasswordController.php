<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'email' => ['required', 'email', 'exists:users,email'],
            ],
            [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico no tiene un formato válido.',
                'email.exists' => 'No encontramos ningún usuario registrado con ese correo.',
            ]
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña.');
        }

        return back()->withErrors([
            'email' => 'No pudimos enviar el enlace de restablecimiento. Inténtalo de nuevo más tarde.',
        ]);
    }
}
