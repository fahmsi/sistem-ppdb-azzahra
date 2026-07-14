<?php

namespace App\Support;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

trait AuthorizesParentSiswa
{
    protected function authorizeParentSiswa(Siswa $siswa): void
    {
        $user = Auth::user();

        if ($user?->isAdmin()) {
            return;
        }

        abort_unless($user && $siswa->user_id === $user->id, 403, 'Anda tidak memiliki akses ke data ini.');
    }
}
