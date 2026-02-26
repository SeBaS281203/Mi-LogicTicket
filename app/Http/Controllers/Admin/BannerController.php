<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->orderBy('id')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        if ($request->hasFile('image')) {
            $validated['image'] = app(ImageOptimizationService::class)->storeOptimized($request->file('image'), 'banners');
        }
        Banner::create($validated);
        Cache::forget('banners_active');
        return redirect()->route('admin.banners.index')->with('success', 'Banner creado.');
    }

    /**
     * Carga masiva de imágenes para el carrusel principal.
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|max:4096',
            'title_prefix' => 'nullable|string|max:120',
            'subtitle' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ], [
            'images.required' => 'Debes seleccionar al menos una imagen.',
            'images.*.image' => 'Todos los archivos deben ser imágenes válidas.',
        ]);

        $titlePrefix = trim((string) ($validated['title_prefix'] ?? 'Banner destacado'));
        if ($titlePrefix === '') {
            $titlePrefix = 'Banner destacado';
        }
        $nextSortOrder = (int) Banner::max('sort_order') + 1;
        $active = $request->boolean('is_active', true);
        $startsAt = $validated['starts_at'] ?? null;
        $endsAt = $validated['ends_at'] ?? null;
        $subtitle = $validated['subtitle'] ?? null;
        $linkUrl = $validated['link_url'] ?? null;
        $linkText = $validated['link_text'] ?? null;

        foreach ($request->file('images', []) as $index => $image) {
            $path = app(ImageOptimizationService::class)->storeOptimized($image, 'banners');
            Banner::create([
                'title' => $titlePrefix . ' #' . ($index + 1),
                'subtitle' => $subtitle,
                'image' => $path,
                'link_url' => $linkUrl,
                'link_text' => $linkText,
                'is_active' => $active,
                'sort_order' => $nextSortOrder + $index,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);
        }

        Cache::forget('banners_active');
        return redirect()->route('admin.banners.index')->with('success', 'Imágenes cargadas en el slider correctamente.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = app(ImageOptimizationService::class)->storeOptimized($request->file('image'), 'banners');
        }
        $banner->update($validated);
        Cache::forget('banners_active');
        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        Cache::forget('banners_active');
        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado.');
    }
}
