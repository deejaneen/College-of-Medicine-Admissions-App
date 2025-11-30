<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download($filePath)
    {
        // Decode URL-encoded path (so %2F becomes /)
        $filePath = urldecode($filePath);

        // Check if file exists in the public disk
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Return the file for download
        return Storage::disk('public')->download($filePath);
    }
}
