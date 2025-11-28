<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Check if a file exists in storage
     * 
     * @param string|null $path
     * @return bool
     */
    public static function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get storage URL with existence check
     * Returns URL if file exists, null otherwise
     * 
     * @param string|null $path
     * @return string|null
     */
    public static function getUrlIfExists(?string $path): ?string
    {
        if (self::exists($path)) {
            return Storage::url($path);
        }
        
        return null;
    }

    /**
     * Get file size in human readable format
     * 
     * @param string|null $path
     * @return string|null
     */
    public static function getSize(?string $path): ?string
    {
        if (!self::exists($path)) {
            return null;
        }

        $bytes = Storage::disk('public')->size($path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file MIME type
     * 
     * @param string|null $path
     * @return string|null
     */
    public static function getMimeType(?string $path): ?string
    {
        if (!self::exists($path)) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($path);
        return mime_content_type($fullPath);
    }

    /**
     * Check if file is a PDF
     * 
     * @param string|null $path
     * @return bool
     */
    public static function isPdf(?string $path): bool
    {
        $mimeType = self::getMimeType($path);
        return $mimeType === 'application/pdf';
    }

    /**
     * Check if file is an image
     * 
     * @param string|null $path
     * @return bool
     */
    public static function isImage(?string $path): bool
    {
        $mimeType = self::getMimeType($path);
        return in_array($mimeType, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }
}
