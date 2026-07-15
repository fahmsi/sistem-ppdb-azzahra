<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PaymentSetting;
use App\Models\Pembayaran;
use App\Models\PendaftaranDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Store a newly created payment proof.
     */
    public function store(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $detail->loadMissing('siswa', 'pembayaran');

        if ($detail->siswa?->user_id !== auth()->id() || ! $detail->canSubmitPayment()) {
            abort(403, 'Akses ditolak.');
        }

        $jumlah = (int) round((float) PaymentSetting::current()?->amount);

        if ($jumlah <= 0) {
            return back()->with('error', 'Informasi pembayaran belum dikonfigurasi. Silakan hubungi admin.');
        }

        $request->validate([
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $isReupload = (bool) $detail->pembayaran;
        $oldPath = $detail->pembayaran?->bukti_bayar;
        $buktiBayarPath = $request->file('bukti_bayar')->store('pembayaran', 'local');

        try {
            DB::transaction(function () use ($detail, $jumlah, $buktiBayarPath, $oldPath) {
                $lockedDetail = PendaftaranDetail::whereKey($detail->id)->lockForUpdate()->firstOrFail();
                $lockedDetail->load('pembayaran');
                if (! $lockedDetail->canSubmitPayment()) {
                    throw new \RuntimeException('Pembayaran daftar ulang tidak dapat diunggah pada status saat ini.');
                }
                Pembayaran::updateOrCreate(
                    ['pendaftaran_detail_id' => $detail->id],
                    [
                        'jumlah' => $jumlah,
                        'bukti_bayar' => $buktiBayarPath,
                        'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI,
                        'catatan_admin' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                    ]
                );

                DB::afterCommit(function () use ($oldPath, $buktiBayarPath) {
                    if ($oldPath && $oldPath !== $buktiBayarPath) {
                        Storage::disk('local')->delete($oldPath);
                    }
                });
            });
        } catch (\Throwable $e) {
            // Clean up newly uploaded file on database failure
            Storage::disk('local')->delete($buktiBayarPath);
            throw $e;
        }

        ActivityLog::log(
            $isReupload ? 'payment_reuploaded' : 'payment_uploaded',
            $detail,
            $isReupload
                ? "Wali murid mengunggah ulang bukti pembayaran daftar ulang: {$detail->nomor_pendaftaran}"
                : "Wali murid mengunggah bukti pembayaran daftar ulang: {$detail->nomor_pendaftaran}"
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Silakan hubungi admin untuk konfirmasi pembayaran agar proses daftar ulang dapat segera diverifikasi.');
    }

    /**
     * Generate a printable payment receipt (HTML-based, print-to-PDF).
     */
    public function receipt(PendaftaranDetail $detail)
    {
        $detail->load(['siswa.user', 'pendaftaran', 'pembayaran.verifiedBy']);

        // Authorization
        if ($detail->siswa?->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if (! $detail->isSiswaResmiTerdaftar()
            || ! $detail->pembayaran
            || ! $detail->pembayaran->isLunas()
        ) {
            abort(403, 'Akses ditolak.');
        }

        return view('parent.pembayaran.receipt', compact('detail'));
    }
}
