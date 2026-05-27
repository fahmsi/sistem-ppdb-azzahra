<?php

namespace App\Exports;

use App\Models\PendaftaranDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VerifikasiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil data dari database beserta relasinya
     */
    public function collection()
    {
        // Memuat relasi siswa (beserta user/wali) dan pendaftaran (gelombang)
        return PendaftaranDetail::with(['siswa.user', 'pendaftaran'])->get();
    }

    /**
     * Membuat Baris Pertama (Judul Kolom) di Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Nama Orang Tua / Wali',
            'Gelombang',
            'Tahun Ajaran',
            'Status Dokumen',
            'Catatan Admin',
            'Tanggal Upload',
        ];
    }

    /**
     * Memetakan data dari database ke kolom Excel
     */
    public function map($detail): array
    {
        static $no = 0;
        $no++;

        // Memformat status agar lebih rapi (misal: perlu_revisi -> Perlu Revisi)
        $statusFormatted = ucwords(str_replace('_', ' ', $detail->status));

        return [
            $no,
            $detail->siswa->nama ?? '-',
            $detail->siswa->user->name ?? '-',
            $detail->pendaftaran->gelombang ?? '-',
            $detail->pendaftaran->tahun_ajaran ?? '-',
            $statusFormatted,
            $detail->catatan ?? '-',
            $detail->created_at ? $detail->created_at->format('d-m-Y H:i') : '-',
        ];
    }
}
