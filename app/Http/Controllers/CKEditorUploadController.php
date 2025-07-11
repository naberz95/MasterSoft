<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'public');

            return response()->json([
                'uploaded' => 1,
                'fileName' => $filename,
                'url' => asset('storage/' . $path)
            ]);

        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
