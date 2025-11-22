<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload and convert image to WebP format
     */
    public function uploadAndConvertToWebP(UploadedFile $file, string $directory = 'disaster_reports'): string
    {
        // Generate unique filename
        $filename = uniqid() . '.webp';
        $path = "{$directory}/{$filename}";

        // Read and convert image to WebP
        $image = $this->manager->read($file->getRealPath());
        
        // Resize if too large (max 1920px width)
        if ($image->width() > 1920) {
            $image->scale(width: 1920);
        }

        // Encode to WebP with 85% quality
        $encoded = $image->toWebp(quality: 85);

        // Store in public disk
        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    /**
     * Delete image from storage
     */
    public function deleteImage(?string $path): bool
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    /**
     * Get full URL for image
     */
    public function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
