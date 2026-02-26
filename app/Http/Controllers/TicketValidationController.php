<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketValidationController extends Controller
{
    /**
     * Valida un ticket a través de su código único.
     */
    public function validateTicket(string $ticketCode): View
    {
        $ticket = Ticket::where('code', $ticketCode)
            ->with(['orderItem.event', 'orderItem.order'])
            ->first();

        if (!$ticket) {
            return view('tickets.validate', [
                'status' => 'invalid',
                'message' => 'El código de ticket no existe en nuestro sistema.',
                'ticket' => null
            ]);
        }

        $order = $ticket->orderItem->order;
        
        // Verificar si la orden está pagada
        if ($order->status !== 'paid') {
            return view('tickets.validate', [
                'status' => 'invalid',
                'message' => 'Este ticket pertenece a una orden que no ha sido pagada o ha sido cancelada.',
                'ticket' => $ticket
            ]);
        }

        // Verificar si ya fue usado
        if ($ticket->is_used) {
            return view('tickets.validate', [
                'status' => 'used',
                'message' => 'Este ticket ya fue utilizado el ' . $ticket->scanned_at->format('d/m/Y H:i:s') . '.',
                'ticket' => $ticket
            ]);
        }

        // Si llegamos aquí, el ticket es válido y no ha sido usado
        return view('tickets.validate', [
            'status' => 'valid',
            'message' => 'Ticket válido. Listo para el ingreso.',
            'ticket' => $ticket
        ]);
    }

    /**
     * Marca un ticket como usado (solo para usuarios autorizados).
     */
    public function markAsUsed(Request $request, string $ticketCode)
    {
        // Aquí podrías agregar un middleware o check de permisos (Admin/Organizador)
        // Por ahora, permitiremos que el botón de la vista llame a esto si el usuario está autenticado.
        
        $ticket = Ticket::where('code', $ticketCode)->firstOrFail();
        
        if (!$ticket->is_used) {
            $ticket->markAsUsed();
            return back()->with('success', 'Ticket marcado como ingresado correctamente.');
        }

        return back()->with('error', 'El ticket ya estaba marcado como usado.');
    }
}
