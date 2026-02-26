<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TicketPdfService;

class PaymentController extends Controller
{
    protected $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    public function createPreference(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);

        if ($order->status === 'paid') {
            return response()->json(['error' => 'La orden ya está pagada.'], 400);
        }

        try {
            $preference = $this->mpService->createPreference($order);
            
            return response()->json([
                'id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ]);
        } catch (\Exception $e) {
            Log::error('MP Create Preference Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la preferencia de pago.'], 500);
        }
    }

    public function success(Request $request)
    {
        return view('payment.status', [
            'status' => 'success',
            'message' => '¡Pago exitoso! Tu orden fue confirmada.',
            'order_number' => $request->external_reference,
            'payment_id' => $request->payment_id,
        ]);
    }

    public function failure(Request $request)
    {
        return view('payment.status', [
            'status' => 'failure',
            'message' => 'El pago no pudo procesarse.',
            'order_number' => $request->external_reference,
        ]);
    }

    public function pending(Request $request)
    {
        return view('payment.status', [
            'status' => 'pending',
            'message' => 'Tu pago está siendo procesado.',
            'order_number' => $request->external_reference,
        ]);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info('MercadoPago Webhook received: ' . json_encode($data));

        if (isset($data['type']) && $data['type'] === 'payment') {
            $paymentId = $data['data']['id'];
            
            // Get payment details from MP API
            try {
                $accessToken = config('services.mercadopago.access_token');
                $response = \Illuminate\Support\Facades\Http::get("https://api.mercadopago.com/v1/payments/{$paymentId}?access_token={$accessToken}");
                
                if ($response->successful()) {
                    $paymentData = $response->json();
                    $orderNumber = $paymentData['external_reference'];
                    $status = $paymentData['status'];

                    $order = Order::where('order_number', $orderNumber)->first();

                    if ($order) {
                        if ($status === 'approved') {
                            $order->update([
                                'status' => 'paid',
                                'payment_id' => $paymentId,
                                'payment_method' => 'mercadopago'
                            ]);

                            // Trigger ticket generation if not already done
                            if ($order->items()->whereDoesntHave('tickets')->exists()) {
                                // Here you would call the logic to generate tickets
                                // For now, let's assume tickets are generated upon order update to 'paid' 
                                // via a model observer or similar, or we trigger it here.
                                $this->generateTicketsForOrder($order);
                            }
                        } elseif ($status === 'rejected' || $status === 'cancelled') {
                            $order->update(['status' => 'failed']);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Webhook Payment processing error: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function generateTicketsForOrder(Order $order)
    {
        // Logic to generate actual Ticket records if they don't exist
        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                $item->tickets()->create([
                    'code' => \App\Models\Ticket::generateUniqueCode(),
                    'is_used' => false,
                ]);
            }
        }
        
        // Possibly send email with tickets
        try {
            $pdfService = app(TicketPdfService::class);
            // $pdfService->generateOrderTicketsPdf($order);
            // Send email logic here...
        } catch (\Exception $e) {
            Log::error('Error generating tickets PDF on webhook: ' . $e->getMessage());
        }
    }
}
