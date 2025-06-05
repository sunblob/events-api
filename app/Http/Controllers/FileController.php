<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\File;
use App\Exceptions\NotFoundException;


class FileController
{

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'page_id' => 'required|exists:pages,id',
            'is_editor_only' => 'required|string',
        ]);

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $extension;
        $mimetype = $file->getMimeType();

        if (strlen($mimetype) > 45) {
            $mimetype = $extension;
        }

        // Store file in the 'public' disk under 'uploads' directory
        $path = $file->storeAs('uploads', $fileName, 'public');

        // Create file record in database
        $fileRecord = File::create([
            'filename' => $fileName,
            'path' => $path,
            'mimetype' => $mimetype,
            'page_id' => $request->page_id,
            'is_editor_only' => $request->is_editor_only === 'true',
            'originalName' => $originalName,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => [
                'id' => $fileRecord->id,
                'original_name' => $originalName,
                'path' => $path,
                'filename' => $fileName,
                'url' => Storage::url($path),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'page_id' => $fileRecord->page_id,
                'is_editor_only' => $fileRecord->is_editor_only ? true : false,
            ]
        ], 201);
    }

    public function attachToPage(Request $request, $fileId)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
        ]);

        $file = File::find($fileId);
        if (!$file) {
            throw new NotFoundException('File not found');
        }

        $file->update(['page_id' => $request->page_id]);

        return response()->json([
            'message' => 'File attached to page successfully',
            'file' => $file
        ]);
    }

    public function detachFromPage($fileId)
    {
        $file = File::find($fileId);

        if (!$file) {
            throw new NotFoundException('File not found');
        }

        $file->delete();

        return response()->json([
            'message' => 'File deleted from page successfully',
            'file' => $file
        ]);
    }

    public function download($filename)
    {
        $path = 'uploads/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download(Storage::disk('public')->path($path));
    }

    public function getFile($filename)
    {
        $path = 'uploads/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }


        return response()->file(Storage::disk('public')->path($path));
    }


    public function list()
    {
        $files = Storage::disk('public')->files('uploads');

        $fileList = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'path' => $file,
                'filename' => basename($file),
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