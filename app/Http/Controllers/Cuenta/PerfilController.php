<?php

namespace App\Http\Controllers\Cuenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function edit(): View
    {
        return view('cuenta.perfil.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'ruc' => 'nullable|string|max:20',
            'current_password' => 'nullable|current_password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Ese correo ya está en uso.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        if ($user->isOrganizer()) {
            $user->ruc = $validated['ruc'] ?? null;
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('cuenta.perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
