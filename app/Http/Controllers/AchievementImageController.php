<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AchievementImageController extends Controller
{
    public function __invoke(Achievement $achievement): StreamedResponse
    {
        abort_unless($achievement->is_active, 404);
        abort_if(! $achievement->image || str_starts_with($achievement->image, 'http'), 404);
        abort_unless(Storage::disk('public')->exists($achievement->image), 404);

        return Storage::disk('public')->response(
            $achievement->image,
            null,
            ['Cache-Control' => 'public, max-age=86400']
        );
    }
}
