<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ImageService
{
    private const MAX_WIDTH   = 1920;
    private const WEBP_QUALITY = 85;
    private const ALLOWED_MIMES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    /**
     * Convierte y guarda una imagen como WebP en el disco public.
     * Redimensiona si el ancho supera MAX_WIDTH manteniendo el ratio.
     *
     * @return string  Ruta relativa dentro del disco public (ej: "inmuebles/uuid.webp")
     */
    public function uploadAndConvert(UploadedFile $file, string $folder = 'inmuebles'): string
    {
        $mime = $file->getMimeType();

        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            throw new InvalidArgumentException("Formato no permitido: {$mime}. Use JPG, PNG o WebP.");
        }

        $source = $this->createGdResource($file->getRealPath(), $mime);
        $source = $this->resizeIfNeeded($source, $mime);

        $filename     = Str::uuid() . '.webp';
        $relativePath = $folder . '/' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);

        Storage::disk('public')->makeDirectory($folder);

        imagewebp($source, $absolutePath, self::WEBP_QUALITY);
        imagedestroy($source);

        return $relativePath;
    }

    /** Elimina una lista de rutas del disco public. */
    public function deleteMany(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function createGdResource(string $realPath, string $mime): \GdImage
    {
        return match(true) {
            in_array($mime, ['image/jpeg', 'image/jpg'], true) => imagecreatefromjpeg($realPath),
            $mime === 'image/png'  => imagecreatefrompng($realPath),
            $mime === 'image/webp' => imagecreatefromwebp($realPath),
            default => throw new InvalidArgumentException("MIME no soportado: {$mime}"),
        };
    }

    private function resizeIfNeeded(\GdImage $source, string $mime): \GdImage
    {
        $width  = imagesx($source);
        $height = imagesy($source);

        if ($width <= self::MAX_WIDTH) {
            return $source;
        }

        $ratio     = self::MAX_WIDTH / $width;
        $newWidth  = self::MAX_WIDTH;
        $newHeight = (int) round($height * $ratio);

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        // Preservar transparencia en PNG
        if ($mime === 'image/png') {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);

        return $canvas;
    }
}
