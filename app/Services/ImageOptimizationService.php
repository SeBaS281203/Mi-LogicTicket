<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    protected int $maxWidth = 1920;

    protected int $quality = 85;

    /**
     * Almacena una imagen optimizada. Si GD está disponible, redimensiona y comprime.
     */
    public function storeOptimized(UploadedFile $file, string $directory): string
    {
        if (!$this->isImage($file) || !$this->canProcess()) {
            return $file->store($directory, 'public');
        }

        $image = $this->loadImage($file);
        if (!$image) {
            return $file->store($directory, 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $this->maxWidth) {
            imagedestroy($image);
            return $file->store($directory, 'public');
        }

        $newWidth = $this->maxWidth;
        $newHeight = (int) round($height * ($this->maxWidth / $width));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        if (!$resized) {
            imagedestroy($image);
            return $file->store($directory, 'public');
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        $ext = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $path = $directory . '/' . uniqid() . '.' . $ext;
        $fullPath = Storage::disk('public')->path($path);
        @mkdir(dirname($fullPath), 0755, true);

        $saved = match ($ext) {
            'png' => imagepng($resized, $fullPath, (int) round(9 * $this->quality / 100)),
            'gif' => imagegif($resized, $fullPath),
            'webp' => function_exists('imagewebp') ? imagewebp($resized, $fullPath, $this->quality) : imagejpeg($resized, $fullPath, $this->quality),
            default => imagejpeg($resized, $fullPath, $this->quality),
        };
        imagedestroy($resized);

        return $saved ? $path : $file->store($directory, 'public');
    }

    protected function isImage(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    protected function canProcess(): bool
    {
        return extension_loaded('gd');
    }

    /**
     * @return \GdImage|false
     */
    protected function loadImage(UploadedFile $file)
    {
        $path = $file->getRealPath();
        $mime = $file->getMimeType();

        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }
}
