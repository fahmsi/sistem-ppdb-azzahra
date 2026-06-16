<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Show private document.
     */
    public function show(Siswa $siswa, string $field, ?Pembayaran $pembayaran = null)
    {
        $user = auth()->user();

        if (! $user || (! $user->isAdmin() && $siswa->user_id !== $user->id)) {
            abort(403, 'Akses ditolak.');
        }

        $path = match ($field) {
            'foto_kk', 'foto_akta' => $siswa->{$field},
            'bukti_bayar' => $this->paymentProofPath($siswa, $pembayaran),
            default => null,
        };

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('local')->response($path);
    }

    private function paymentProofPath(Siswa $siswa, ?Pembayaran $pembayaran): ?string
    {
        if (! $pembayaran) {
            return null;
        }

        $pembayaran->loadMissing('pendaftaranDetail');

        if ($pembayaran->pendaftaranDetail?->siswa_id !== $siswa->id) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return $pembayaran->bukti_bayar;
    }
}
