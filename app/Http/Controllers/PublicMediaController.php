<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicMediaController extends Controller
{
    public function show(string $path): BinaryFileResponse
    {
        $cleanPath = ltrim($path, '/');

        if ($cleanPath === '' || str_contains($cleanPath, '..')) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($cleanPath)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($cleanPath);

        return response()->file($fullPath, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
