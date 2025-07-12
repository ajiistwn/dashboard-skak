<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class FileController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $url)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getFile(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json(['error' => 'No URL provided'], 400);
        }

        // Cari posisi "/uploads/"
        $uploadsPos = strpos($url, '/uploads/');
        if ($uploadsPos === false) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        // Dapatkan path relatif setelah "/uploads/"
        $relativePath = substr($url, $uploadsPos + strlen('/uploads/'));  // agenda/foufo_image.png

        // Path lengkap di storage
        $storagePath = 'public/uploads/' . $relativePath;

        if (!Storage::exists($storagePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Ambil MIME dan file dari storage lokal (bukan dari URL)
        $filePath = Storage::path($storagePath);
        $mime = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }



}
