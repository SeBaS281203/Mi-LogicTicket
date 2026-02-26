<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isStrictOrganizer($request->user())) {
            abort(403, 'No tienes permiso para acceder al panel de organizador.');
        }

        return $next($request);
    }

    private function isStrictOrganizer(?Authenticatable $user): bool
    {
        return $user !== null && ($user->role ?? null) === 'organizer';
    }
}
