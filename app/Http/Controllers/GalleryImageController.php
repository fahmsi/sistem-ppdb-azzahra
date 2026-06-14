<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GalleryImageController extends Controller
{
    public function __invoke(Gallery $gallery): StreamedResponse
    {
        abort_if(! $gallery->image || str_starts_with($gallery->image, 'http'), 404);
        abort_unless(Storage::disk('public')->exists($gallery->image), 404);

        return Storage::disk('public')->response(
            $gallery->image,
            null,
            ['Cache-Control' => 'public, max-age=86400']
        );
    }
}
