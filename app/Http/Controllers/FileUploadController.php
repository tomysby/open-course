<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,mp4,mp3,mov,avi|max:20480',
        ]);

        $file = $request->file('file');
        $path = $file->store('materials', 'uploads');

        return response()->json([
            'path' => '/uploads/'.$path,
            'type' => $this->getFileType($file->getMimeType()),
        ]);
    }

    protected function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }

        return 'other';
    }
}