<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tendencia;
use App\Services\ImageOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TendenciaController extends Controller
{
    public function index(): View
    {
        $tendencias = Tendencia::orderBy('orden')->orderBy('id')->paginate(10);
        return view('admin.tendencias.index', compact('tendencias'));
    }

    public function create(): View
    {
        return view('admin.tendencias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'imagen' => 'required|image|max:2048',
            'link' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        $validated['activo'] = $request->boolean('activo', true);
        $validated['orden'] = (int) ($validated['orden'] ?? 0);
        $validated['imagen'] = app(ImageOptimizationService::class)->storeOptimized($request->file('imagen'), 'tendencias');
        Tendencia::create($validated);
        return redirect()->route('admin.tendencias.index')->with('success', 'Publicidad creada.');
    }

    public function edit(Tendencia $tendencia): View
    {
        return view('admin.tendencias.edit', compact('tendencia'));
    }

    public function update(Request $request, Tendencia $tendencia): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:2048',
            'link' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        $validated['activo'] = $request->boolean('activo', true);
        $validated['orden'] = (int) ($validated['orden'] ?? 0);
        if ($request->hasFile('imagen')) {
            if ($tendencia->imagen) {
                Storage::disk('public')->delete($tendencia->imagen);
            }
            $validated['imagen'] = app(ImageOptimizationService::class)->storeOptimized($request->file('imagen'), 'tendencias');
        }
        $tendencia->update($validated);
        return redirect()->route('admin.tendencias.index')->with('success', 'Publicidad actualizada.');
    }

    public function destroy(Tendencia $tendencia): RedirectResponse
    {
        if ($tendencia->imagen) {
            Storage::disk('public')->delete($tendencia->imagen);
        }
        $tendencia->delete();
        return redirect()->route('admin.tendencias.index')->with('success', 'Publicidad eliminada.');
    }
}
