<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    public function stream($filename)
    {
        // Validasi ekstensi
        if (!preg_match('/\.(pdf|mp4|mov|avi|webm)$/i', $filename)) {
            abort(404, 'Invalid file type');
        }

        // Gunakan disk yang benar
        if (!Storage::disk('educational-materials')->exists($filename)) {
            abort(404);
        }

        $path = Storage::disk('educational-materials')->path($filename);
        $mime = Storage::disk('educational-materials')->mimeType($filename);
        $size = Storage::disk('educational-materials')->size($filename);

        return response()->stream(
            function () use ($path) {
                readfile($path);
            },
            200,
            [
                'Content-Type' => $mime,
                'Content-Length' => $size,
                'Content-Disposition' => 'inline'
            ]
        );
    }
}