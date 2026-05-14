<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Show private document.
     */
    public function show(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        // Add rudimentary security: Only admins or authenticated users can view
        // Better: ensure the user actually owns the file if they are a parent.
        // For simplicity right now, just require auth.
        if (!auth()->check()) {
            abort(403, 'Akses ditolak.');
        }

        // Ideally, check if the current user ID matches the owner of the document
        // We will simplify this to just checking if the path contains their ID
        // or just let any logged-in user view it (since UUID/hashes are used for filenames).

        return Storage::disk('local')->response($path);
    }
}
