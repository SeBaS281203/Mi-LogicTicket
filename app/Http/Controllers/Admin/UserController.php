<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,organizer,client',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);
        unset($validated['password']);
        $user->update($validated);
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }
}
