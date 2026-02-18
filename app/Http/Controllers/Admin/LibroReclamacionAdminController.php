<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibroReclamacion;
use App\Services\LibroReclamacionPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Panel administrativo del Libro de Reclamaciones.
 * Solo administradores. Listado, filtros, responder, exportar, métricas.
 */
class LibroReclamacionAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = LibroReclamacion::with('evento')->orderByDesc('created_at');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('tipo_reclamo')) {
            $query->where('tipo_reclamo', $request->tipo_reclamo);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $reclamos = $query->paginate(15)->withQueryString();

        return view('admin.libro-reclamaciones.index', compact('reclamos'));
    }

    public function show(LibroReclamacion $libro_reclamacion): View
    {
        $libro_reclamacion->load('evento');
        return view('admin.libro-reclamaciones.show', ['reclamo' => $libro_reclamacion]);
    }

    public function respond(Request $request, LibroReclamacion $libro_reclamacion): RedirectResponse
    {
        $request->validate([
            'respuesta_empresa' => ['required', 'string', 'max:5000'],
            'estado' => ['required', 'string', 'in:pendiente,atendido,cerrado'],
        ]);

        $libro_reclamacion->respuesta_empresa = $request->respuesta_empresa;
        $libro_reclamacion->estado = $request->estado;
        $libro_reclamacion->fecha_respuesta = now();
        $libro_reclamacion->save();

        return redirect()
            ->route('admin.libro-reclamaciones.show', $libro_reclamacion)
            ->with('success', 'Respuesta registrada correctamente.');
    }

    public function updateEstado(Request $request, LibroReclamacion $libro_reclamacion): RedirectResponse
    {
        $request->validate(['estado' => ['required', 'string', 'in:pendiente,atendido,cerrado']]);
        $libro_reclamacion->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = LibroReclamacion::with('evento')->orderByDesc('created_at');
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        $reclamos = $query->get();

        $filename = 'libro-reclamaciones-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Response::stream(function () use ($reclamos) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, [
                'Código', 'Fecha', 'Tipo doc', 'Nº doc', 'Nombre', 'Email', 'Teléfono', 'Tipo', 'Estado',
                'Evento', 'Respuesta fecha',
            ]);
            foreach ($reclamos as $r) {
                fputcsv($out, [
                    $r->codigo_reclamo,
                    $r->created_at->format('Y-m-d H:i'),
                    $r->tipo_documento,
                    $r->numero_documento,
                    $r->nombre_completo,
                    $r->email,
                    $r->telefono,
                    $r->tipo_reclamo,
                    $r->estado,
                    $r->evento?->title ?? '-',
                    $r->fecha_respuesta?->format('Y-m-d H:i') ?? '-',
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = LibroReclamacion::with('evento')->orderByDesc('created_at');
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        $reclamos = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.libro-reclamaciones.export-pdf', compact('reclamos'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('libro-reclamaciones-' . now()->format('Y-m-d') . '.pdf');
    }

    public function dashboard(): View
    {
        $esteMes = LibroReclamacion::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $pendientes = LibroReclamacion::pendientes()->count();
        $promedioDias = LibroReclamacion::whereNotNull('fecha_respuesta')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, fecha_respuesta)) as dias')
            ->value('dias');

        return view('admin.libro-reclamaciones.dashboard', [
            'este_mes' => $esteMes,
            'pendientes' => $pendientes,
            'tiempo_promedio_dias' => round($promedioDias ?? 0, 1),
        ]);
    }
}
