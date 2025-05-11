<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Upload a file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $extension;
        
        // Store file in the 'public' disk under 'uploads' directory
        $path = $file->storeAs('uploads', $fileName, 'public');

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => [
                'original_name' => $originalName,
                'path' => $path,
                'url' => Storage::url($path),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]
        ], 201);
    }

    /**
     * Download a file
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        $path = 'uploads/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * List all uploaded files
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $files = Storage::disk('public')->files('uploads');
        
        $fileList = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file)
            ];
        });

        return response()->json(['files' => $fileList]);
    }

    /**
     * Delete a file
     *
     * @param string $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($filename)
    {
        $path = 'uploads/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json(['message' => 'File deleted successfully']);
    }
} 