<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanPurchase
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            return $next($request);
        }

        if ($request->isMethod('post') && $request->routeIs('cart.add')) {
            session([
                'pending_cart_add' => [
                    'ticket_type_id' => $request->input('ticket_type_id'),
                    'quantity' => (int) $request->input('quantity', 1),
                ],
            ]);
        }

        $intended = $request->routeIs('cart.add') ? route('cart.index') : $request->fullUrl();
        session()->put('url.intended', $intended);

        return redirect()
            ->route('login')
            ->with('info', 'Para continuar con tu compra debes iniciar sesión o crear una cuenta.');
    }
}
