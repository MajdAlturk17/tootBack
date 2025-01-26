<?php

namespace App\Utilities\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUtilities
{

    /**
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string|null
     */
    public static function storeFile(UploadedFile $file, string $directory = 'uploads'): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $path = $file->store($directory, 'public');

        return Storage::url($path);
    }
}
