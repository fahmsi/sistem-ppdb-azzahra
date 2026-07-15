<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil data dari database
     */
    public function collection()
    {
        // Memuat relasi user (orang tua yang bayar) dan pendaftarannya
        return Pembayaran::with([
            'pendaftaranDetail.siswa.user',
            'pendaftaranDetail.pendaftaran',
        ])->get();
    }

    /**
     * Membuat Baris Pertama (Judul Kolom) di Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Orang Tua / Pembayar',
            'Nama Siswa',
            'Gelombang Pendaftaran',
            'Tahun Ajaran',
            'Keputusan',
            'Status Akhir',
            'Jumlah Bayar',
            'Status Pembayaran',
            'Diverifikasi Pada',
            'Finalisasi Pada',
        ];
    }

    /**
     * Memetakan data dari database ke kolom Excel
     */
    public function map($pembayaran): array
    {
        static $no = 0;
        $no++;

        // Memformat Nominal Rupiah
        $jumlahBayar = 'Rp '.number_format($pembayaran->jumlah ?? 0, 0, ',', '.');

        // Gunakan created_at sebagai tanggal pembayaran jika tidak ada kolom khusus
        $tanggalBayar = $pembayaran->created_at
                        ? Carbon::parse($pembayaran->created_at)->format('d/m/Y')
                        : '-';

        // Ambil nama dari relasi pendaftaranDetail -> siswa -> user
        $namaPembayar = $pembayaran->pendaftaranDetail?->siswa?->user?->name ?? '-';
        $detail = $pembayaran->pendaftaranDetail;

        $gelombang = $pembayaran->pendaftaranDetail?->pendaftaran?->gelombang ?? '-';

        return [
            $no,
            $detail?->nomor_pendaftaran ?? '-',
            $namaPembayar,
            $detail?->siswa?->nama ?? '-',
            $gelombang,
            $detail?->pendaftaran?->tahun_ajaran ?? '-',
            $detail?->keputusan_status ? ucwords(str_replace('_', ' ', $detail->keputusan_status)) : '-',
            $detail?->final_status ? ucwords(str_replace('_', ' ', $detail->final_status)) : '-',
            $jumlahBayar,
            ucfirst($pembayaran->status),
            $pembayaran->verified_at?->format('d/m/Y H:i') ?? '-',
            $detail?->final_ditetapkan_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
