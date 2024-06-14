<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MinioHelper
{
    public static function uploadFile($file): string
    {
        $fileName = $file->getClientOriginalName();
        $filePath = 'upload/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file->getRealPath()));
        return Storage::disk('s3')->url($filePath);
    }
}
