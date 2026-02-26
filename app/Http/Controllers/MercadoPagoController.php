<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    protected $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    /**
     * API: Crea una preferencia de pago en Mercado Pago.
     */
    public function createPreference(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);

        if ($order->status === 'paid') {
            return response()->json(['error' => 'Esta orden ya ha sido pagada.'], 400);
        }

        try {
            $preference = $this->mpService->createPreference($order);
            
            return response()->json([
                'id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ]);
        } catch (\Exception $e) {
            Log::error('MercadoPago Preference Error: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo generar la preferencia de pago.'], 500);
        }
    }

    /**
     * Redirección de éxito desde el checkout de MP.
     */
    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $orderNumber = $request->query('external_reference');
        
        // Fallback: Si el webhook no ha llegado (común en local), procesamos el pago aquí mismo
        if ($paymentId) {
            $this->processPayment($paymentId);
        }

        // Buscamos la orden por número o por payment_id si ya se procesó
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.event', 'items.tickets'])
            ->first();

        if (!$order && $paymentId) {
            $order = Order::where('payment_id', $paymentId)
                ->with(['items.event', 'items.tickets'])
                ->first();
        }

        // Si la orden no existe o no está pagada (quizás rechazado en API pero redirigido aquí), 
        // redirigimos a una vista de estado genérica o home
        if (!$order || $order->status !== 'paid') {
            return redirect()->route('home');
        }

        return view('payment.success', [
            'order' => $order,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Redirección de fallo desde el checkout de MP.
     */
    public function failure(Request $request)
    {
        return view('payment.status', [
            'status' => 'failure',
            'message' => 'El pago no pudo procesarse.',
            'order_number' => $request->external_reference,
        ]);
    }

    /**
     * Redirección de pendiente desde el checkout de MP.
     */
    public function pending(Request $request)
    {
        return view('payment.status', [
            'status' => 'pending',
            'message' => 'Tu pago está siendo procesado.',
            'order_number' => $request->external_reference,
        ]);
    }

    /**
     * Webhook de Mercado Pago para notificaciones de pago.
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info('MercadoPago Webhook: ' . json_encode($data));

        // MP envía notificaciones por 'type' o 'action'
        if (isset($data['type']) && $data['type'] === 'payment') {
            $paymentId = $data['data']['id'];
            $this->processPayment($paymentId);
        }

        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * Procesa los detalles de un pago individual.
     */
    private function processPayment($paymentId)
    {
        try {
            $accessToken = config('services.mercadopago.access_token');
            $response = Http::get("https://api.mercadopago.com/v1/payments/{$paymentId}?access_token={$accessToken}");

            if ($response->successful()) {
                $paymentData = $response->json();
                $orderNumber = $paymentData['external_reference'];
                $status = $paymentData['status'];

                $order = Order::where('order_number', $orderNumber)->first();

                if ($order && $order->status === 'pending') {
                    if ($status === 'approved') {
                        // Usamos la lógica centralizada de confirmación
                        app(CheckoutController::class)->confirmPaidOrder($order);
                        
                        $order->update([
                            'payment_id' => $paymentId,
                            'payment_method' => 'mercadopago'
                        ]);
                    } elseif (in_array($status, ['rejected', 'cancelled'])) {
                        $order->update(['status' => 'failed']);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing MP payment on webhook: ' . $e->getMessage());
        }
    }
}
