<?php

namespace App\Exports;

use App\Models\Siswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil data dari database
     */
    public function collection()
    {
        // Mengambil data siswa beserta relasi user (orang tua)
        return Siswa::with('user')->get();
    }

    /**
     * Membuat Baris Pertama (Judul Kolom) di Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN',
            'Jenis Kelamin',
            'Tempat, Tgl Lahir',
            'Nama Orang Tua / Wali',
            'No. HP Wali',
            'Tanggal Didaftarkan',
        ];
    }

    /**
     * Memetakan data dari database ke kolom Excel
     */
    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $siswa->nama,
            $siswa->nisn ?? '-',
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir.', '.($siswa->tanggal_lahir ? Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-'),
            $siswa->user->name ?? '-',
            $siswa->user->no_telpon ?? '-',
            $siswa->created_at->format('d-m-Y H:i'),
        ];
    }
}
