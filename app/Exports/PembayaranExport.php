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
            'Nama Orang Tua / Pembayar',
            'Gelombang Pendaftaran',
            'Metode Pembayaran',
            'Jumlah Bayar',
            'Status Pembayaran',
            'Tanggal Bayar',
            'Catatan Admin',
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
                        ? Carbon::parse($pembayaran->created_at)->format('d-m-Y')
                        : '-';

        // Ambil nama dari relasi pendaftaranDetail -> siswa -> user
        $namaPembayar = $pembayaran->pendaftaranDetail->siswa->user->name ?? '-';

        $gelombang = $pembayaran->pendaftaranDetail->pendaftaran->gelombang ?? '-';

        // Metode pembayaran tidak disimpan di tabel pada skema saat ini; tampilkan '-' jika tidak ada
        $metode = $pembayaran->metode ?? '-';

        return [
            $no,
            $namaPembayar,
            $gelombang,
            strtoupper($metode),
            $jumlahBayar,
            ucfirst($pembayaran->status),
            $tanggalBayar,
            $pembayaran->catatan_admin ?? '-',
        ];
    }
}
