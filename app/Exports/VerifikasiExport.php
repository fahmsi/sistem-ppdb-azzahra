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
        return PendaftaranDetail::with(['siswa.user', 'pendaftaran', 'observasiTerbaru', 'keputusanDiputuskanOleh'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Siswa',
            'Nama Orang Tua / Wali',
            'Gelombang',
            'Tahun Ajaran',
            'Usia (Bulan) Saat 1 Juli',
            'Rekomendasi Kelompok',
            'Kelompok Final',
            'Status Administrasi',
            'Status Observasi',
            'Jadwal Observasi',
            'Catatan Admin',
            'Keputusan Status',
            'Tanggal Keputusan',
            'Diputuskan Oleh',
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
        $observasi = $detail->observasiTerbaru;
        $observasiStatus = $observasi ? ucwords(str_replace('_', ' ', $observasi->status)) : '-';
        $jadwalObservasi = $observasi?->scheduled_at?->format('d/m/Y H:i') ?? '-';
        $keputusanStatus = $detail->keputusan_status ? ucwords(str_replace('_', ' ', $detail->keputusan_status)) : '-';
        $tanggalKeputusan = $detail->keputusan_diputuskan_at ? $detail->keputusan_diputuskan_at->format('d/m/Y H:i') : '-';
        $diputuskanOleh = $detail->keputusanDiputuskanOleh?->name ?? '-';

        return [
            $no,
            $detail->nomor_pendaftaran,
            $detail->siswa->nama ?? '-',
            $detail->siswa?->user?->name ?? '-',
            $detail->pendaftaran->gelombang ?? '-',
            $detail->pendaftaran->tahun_ajaran ?? '-',
            $detail->usia_bulan_saat_acuan ?? '-',
            $detail->kelompok_rekomendasi ?? '-',
            $detail->kelompok_final ?? '-',
            $statusFormatted,
            $observasiStatus,
            $jadwalObservasi,
            $detail->catatan ?? '-',
            $keputusanStatus,
            $tanggalKeputusan,
            $diputuskanOleh,
            $detail->created_at ? $detail->created_at->format('d/m/Y H:i').' WIB' : '-',
        ];
    }
}
