<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function upload(UploadedFile $file, string $path = 'products'): string
    {
        $storedPath = $file->store($path, 'public');
        return '/storage/' . $storedPath;
    }

    public function delete(?string $imagePath): void
    {
        if (!$imagePath) return;

        $relativePath = str_replace('/storage/', '', $imagePath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}