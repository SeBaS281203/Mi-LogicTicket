<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibroReclamacionRequest;
use App\Mail\ReclamacionRegistradaMail;
use App\Models\Event;
use App\Models\LibroReclamacion;
use App\Services\LibroReclamacionPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controlador público del Libro de Reclamaciones Virtual (INDECOPI).
 * Accesible sin login. Rate limiting aplicado en rutas.
 */
class LibroReclamacionController extends Controller
{
    /**
     * Formulario público de registro de reclamo/queja.
     */
    public function create(Request $request): View
    {
        $eventos = Event::published()->upcoming()->orderBy('start_date')->get(['id', 'title', 'start_date']);
        $eventoId = $request->query('evento_id');

        return view('libro-reclamaciones.create', [
            'eventos' => $eventos,
            'eventoId' => $eventoId ? (int) $eventoId : null,
        ]);
    }

    /**
     * Guardar reclamo/queja. Genera código, envía email y permite descargar PDF.
     */
    public function store(StoreLibroReclamacionRequest $request): RedirectResponse
    {
        $reclamo = new LibroReclamacion;
        $reclamo->codigo_reclamo = LibroReclamacion::generarCodigo();
        $reclamo->tipo_documento = $request->tipo_documento;
        $reclamo->numero_documento = $request->numero_documento;
        $reclamo->nombre_completo = $request->nombre_completo;
        $reclamo->direccion = $request->direccion;
        $reclamo->telefono = $request->telefono;
        $reclamo->email = $request->email;
        $reclamo->tipo_reclamo = $request->tipo_reclamo;
        $reclamo->descripcion = $request->descripcion;
        $reclamo->pedido_consumidor = $request->pedido_consumidor;
        $reclamo->evento_id = $request->evento_id ?: null;
        $reclamo->user_id = $request->user()?->id;
        $reclamo->estado = 'pendiente';
        $reclamo->save();

        try {
            Mail::to($reclamo->email)->send(new ReclamacionRegistradaMail($reclamo));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('libro-reclamaciones.thanks', ['codigo' => $reclamo->codigo_reclamo])
            ->with('reclamo_id', $reclamo->id);
    }

    /**
     * Página de agradecimiento con opción de descargar constancia.
     */
    public function thanks(Request $request): View|RedirectResponse
    {
        $codigo = $request->query('codigo');
        if (! $codigo) {
            return redirect()->route('libro-reclamaciones.create');
        }
        $reclamo = LibroReclamacion::where('codigo_reclamo', $codigo)->first();
        if (! $reclamo) {
            return redirect()->route('libro-reclamaciones.create');
        }

        return view('libro-reclamaciones.thanks', compact('reclamo'));
    }

    /**
     * Descarga de constancia en PDF (público con código).
     */
    public function downloadConstancia(string $codigo): StreamedResponse
    {
        $reclamo = LibroReclamacion::where('codigo_reclamo', $codigo)->firstOrFail();
        $pdfService = app(LibroReclamacionPdfService::class);
        $pdf = $pdfService->generarConstanciaPdf($reclamo);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'constancia-' . $reclamo->codigo_reclamo . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
