<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isOrganizer()) {
            abort(403, 'No tienes permiso para acceder al panel de organizador.');
        }

        return $next($request);
    }
}
