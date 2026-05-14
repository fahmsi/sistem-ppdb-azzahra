<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranDetail;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Store a newly created payment proof.
     */
    public function store(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        // Ensure the registration belongs to the parent and is accepted
        if ($detail->siswa->user_id !== auth()->id() || $detail->status !== 'diterima') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Delete old proof if it exists
        if ($detail->pembayaran && $detail->pembayaran->bukti_bayar) {
            Storage::disk('local')->delete($detail->pembayaran->bukti_bayar);
        }

        $buktiBayarPath = $request->file('bukti_bayar')->store('pembayaran', 'local');

        Pembayaran::updateOrCreate(
            ['pendaftaran_detail_id' => $detail->id],
            [
                'jumlah' => $request->jumlah,
                'bukti_bayar' => $buktiBayarPath,
                'status' => 'pending',
                'catatan_admin' => null, // reset notes on re-upload
            ]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.');
    }

    /**
     * Generate a printable payment receipt (HTML-based, print-to-PDF).
     */
    public function receipt(PendaftaranDetail $detail)
    {
        // Authorization
        if ($detail->siswa->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $detail->load(['siswa', 'pendaftaran', 'pembayaran']);

        if (!$detail->pembayaran || $detail->pembayaran->status !== 'lunas') {
            abort(403, 'Bukti bayar belum diverifikasi.');
        }

        return view('parent.pembayaran.receipt', compact('detail'));
    }
}
