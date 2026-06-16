<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PendaftaranDetail;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Store a newly created payment proof.
     */
    public function store(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $detail->loadMissing('siswa', 'pembayaran');

        if ($detail->siswa?->user_id !== auth()->id() || $detail->status !== PendaftaranDetail::STATUS_DITERIMA) {
            abort(403, 'Akses ditolak.');
        }

        if ($detail->pembayaran?->isLunas()) {
            return back()->with('error', 'Pembayaran daftar ulang sudah diverifikasi dan tidak dapat diunggah ulang.');
        }

        $jumlah = (int) config('ppdb.daftar_ulang_amount', 0);

        if ($jumlah <= 0) {
            return back()->with('error', 'Nominal daftar ulang belum dikonfigurasi. Silakan hubungi admin.');
        }

        $request->validate([
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $isReupload = (bool) $detail->pembayaran;

        // Delete old proof if it exists
        if ($detail->pembayaran && $detail->pembayaran->bukti_bayar) {
            Storage::disk('local')->delete($detail->pembayaran->bukti_bayar);
        }

        $buktiBayarPath = $request->file('bukti_bayar')->store('pembayaran', 'local');

        Pembayaran::updateOrCreate(
            ['pendaftaran_detail_id' => $detail->id],
            [
                'jumlah' => $jumlah,
                'bukti_bayar' => $buktiBayarPath,
                'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI,
                'catatan_admin' => null, // reset notes on re-upload
            ]
        );

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
        $detail->load(['siswa', 'pendaftaran', 'pembayaran']);

        // Authorization
        if ($detail->siswa?->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if (!$detail->pembayaran || ! $detail->pembayaran->isLunas()) {
            abort(403, 'Bukti bayar belum diverifikasi.');
        }

        return view('parent.pembayaran.receipt', compact('detail'));
    }
}
