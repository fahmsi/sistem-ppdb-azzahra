<?php

namespace App\Http\Requests\Concerns;

trait SiswaValidationRules
{
    protected function siswaRules(bool $documentsRequired, bool $isStore = true): array
    {
        $documentPresence = $documentsRequired ? 'required' : 'nullable';

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['required', 'string', 'max:50'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'nis' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'agama' => ['required', 'string', 'max:50'],
            'anak_ke' => ['required', 'integer', 'min:1'],
            'jumlah_saudara' => ['required', 'integer', 'min:0'],
            'hobi' => ['nullable', 'string', 'max:255'],
            'cita_cita' => ['nullable', 'string', 'max:255'],
            'no_telpon' => ['required', 'string', 'max:20'],
            'jenis_tempat_tinggal' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:500'],
            'kelurahan' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'transportasi' => ['nullable', 'string', 'max:50'],
            'no_kk' => ['required', 'numeric', 'digits:16'],
            'kepala_keluarga' => ['required', 'string', 'max:255'],
            'nama_ayah' => ['required', 'string', 'max:255'],
            'nik_ayah' => ['required', 'string', 'size:16'],
            'tanggal_lahir_ayah' => ['required', 'date', 'before:today'],
            'pendidikan_ayah' => ['required', 'string', 'max:50'],
            'pekerjaan_ayah' => ['required', 'string', 'max:100'],
            'penghasilan_ayah' => ['required', 'string', 'max:50'],
            'nama_ibu' => ['required', 'string', 'max:255'],
            'nik_ibu' => ['required', 'string', 'size:16'],
            'tanggal_lahir_ibu' => ['required', 'date', 'before:today'],
            'pendidikan_ibu' => ['required', 'string', 'max:50'],
            'pekerjaan_ibu' => ['required', 'string', 'max:100'],
            'penghasilan_ibu' => ['required', 'string', 'max:50'],
            'foto' => [$documentPresence, 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kk' => [$documentPresence, 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_akta' => [$documentPresence, 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'tinggal_bersama' => ['required', 'in:orang_tua,wali'],
        ];

        // Conditional guardian details based on tinggal_bersama
        if ($this->input('tinggal_bersama') === 'wali') {
            $rules['nama_wali'] = ['required', 'string', 'max:255'];
            $rules['nik_wali'] = ['required', 'numeric', 'digits:16'];
            $rules['hubungan_wali'] = ['required', 'string', 'max:100'];
            $rules['no_telpon_wali'] = ['required', 'string', 'max:20'];
        } else {
            $rules['nama_wali'] = ['nullable', 'string', 'max:255'];
            $rules['nik_wali'] = ['nullable', 'numeric', 'digits:16'];
            $rules['hubungan_wali'] = ['nullable', 'string', 'max:100'];
            $rules['no_telpon_wali'] = ['nullable', 'string', 'max:20'];
        }

        // KTP Ayah
        $ktpAyahRules = ['image', 'mimes:jpg,jpeg,png', 'max:2048'];
        if ($this->input('tinggal_bersama') === 'orang_tua') {
            if ($isStore) {
                $ktpAyahRules[] = 'required';
            } else {
                $siswa = $this->route('siswa');
                if (is_numeric($siswa)) {
                    $siswa = \App\Models\Siswa::find($siswa);
                }
                $hasExisting = $siswa && $siswa->foto_ktp_ayah && $siswa->tinggal_bersama === 'orang_tua';
                $ktpAyahRules[] = $hasExisting ? 'nullable' : 'required';
            }
        } else {
            $ktpAyahRules[] = 'nullable';
        }
        $rules['foto_ktp_ayah'] = $ktpAyahRules;

        // KTP Ibu
        $ktpIbuRules = ['image', 'mimes:jpg,jpeg,png', 'max:2048'];
        if ($this->input('tinggal_bersama') === 'orang_tua') {
            if ($isStore) {
                $ktpIbuRules[] = 'required';
            } else {
                $siswa = $this->route('siswa');
                if (is_numeric($siswa)) {
                    $siswa = \App\Models\Siswa::find($siswa);
                }
                $hasExisting = $siswa && $siswa->foto_ktp_ibu && $siswa->tinggal_bersama === 'orang_tua';
                $ktpIbuRules[] = $hasExisting ? 'nullable' : 'required';
            }
        } else {
            $ktpIbuRules[] = 'nullable';
        }
        $rules['foto_ktp_ibu'] = $ktpIbuRules;

        // KTP Wali
        $ktpWaliRules = ['image', 'mimes:jpg,jpeg,png', 'max:2048'];
        if ($this->input('tinggal_bersama') === 'wali') {
            if ($isStore) {
                $ktpWaliRules[] = 'required';
            } else {
                $siswa = $this->route('siswa');
                if (is_numeric($siswa)) {
                    $siswa = \App\Models\Siswa::find($siswa);
                }
                $hasExisting = $siswa && $siswa->foto_ktp_wali && $siswa->tinggal_bersama === 'wali';
                $ktpWaliRules[] = $hasExisting ? 'nullable' : 'required';
            }
        } else {
            $ktpWaliRules[] = 'nullable';
        }
        $rules['foto_ktp_wali'] = $ktpWaliRules;

        return $rules;
    }

    protected function siswaAttributes(): array
    {
        return [
            'nama' => 'nama anak',
            'nama_panggilan' => 'nama panggilan',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'anak_ke' => 'anak ke',
            'jumlah_saudara' => 'jumlah saudara',
            'no_telpon' => 'nomor telepon',
            'jenis_tempat_tinggal' => 'jenis tempat tinggal',
            'no_kk' => 'nomor KK',
            'kepala_keluarga' => 'kepala keluarga',
            'nama_ayah' => 'nama ayah',
            'nik_ayah' => 'NIK ayah',
            'tanggal_lahir_ayah' => 'tanggal lahir ayah',
            'pendidikan_ayah' => 'pendidikan ayah',
            'pekerjaan_ayah' => 'pekerjaan ayah',
            'penghasilan_ayah' => 'penghasilan ayah',
            'nama_ibu' => 'nama ibu',
            'nik_ibu' => 'NIK ibu',
            'tanggal_lahir_ibu' => 'tanggal lahir ibu',
            'pendidikan_ibu' => 'pendidikan ibu',
            'pekerjaan_ibu' => 'pekerjaan ibu',
            'penghasilan_ibu' => 'penghasilan ibu',
            'foto' => 'foto anak',
            'foto_kk' => 'foto Kartu Keluarga',
            'foto_akta' => 'foto akta kelahiran',
            'tinggal_bersama' => 'tinggal bersama',
            'nama_wali' => 'nama wali',
            'nik_wali' => 'NIK wali',
            'hubungan_wali' => 'hubungan wali',
            'no_telpon_wali' => 'nomor telepon wali',
            'foto_ktp_ayah' => 'foto KTP ayah',
            'foto_ktp_ibu' => 'foto KTP ibu',
            'foto_ktp_wali' => 'foto KTP wali',
        ];
    }

    protected function siswaMessages(): array
    {
        return [
            // Data Pribadi Anak
            'nama.required' => 'Mohon isi nama lengkap anak sesuai akta kelahiran.',
            'nama.max' => 'Nama anak terlalu panjang. Maksimal 255 karakter.',
            'nama_panggilan.required' => 'Mohon isi nama panggilan anak.',
            'nama_panggilan.max' => 'Nama panggilan terlalu panjang. Maksimal 50 karakter.',
            'jenis_kelamin.required' => 'Mohon pilih jenis kelamin anak.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid. Silakan pilih Laki-laki atau Perempuan.',
            'tempat_lahir.required' => 'Mohon isi tempat lahir anak sesuai akta kelahiran.',
            'tanggal_lahir.required' => 'Mohon isi tanggal lahir anak.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid. Gunakan format yang benar.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'agama.required' => 'Mohon isi agama anak.',
            'anak_ke.required' => 'Mohon isi anak ke berapa dalam keluarga.',
            'anak_ke.integer' => 'Anak ke harus diisi dengan angka.',
            'anak_ke.min' => 'Anak ke minimal 1.',
            'jumlah_saudara.required' => 'Mohon isi jumlah saudara kandung.',
            'jumlah_saudara.integer' => 'Jumlah saudara harus diisi dengan angka.',
            'jumlah_saudara.min' => 'Jumlah saudara tidak boleh kurang dari 0.',

            // Kontak & Alamat
            'no_telpon.required' => 'Mohon isi nomor telepon/WhatsApp yang bisa dihubungi.',
            'jenis_tempat_tinggal.required' => 'Mohon pilih jenis tempat tinggal.',
            'alamat.required' => 'Mohon isi alamat lengkap tempat tinggal.',
            'alamat.max' => 'Alamat terlalu panjang. Maksimal 500 karakter.',
            'kelurahan.required' => 'Mohon isi kelurahan/desa.',
            'kecamatan.required' => 'Mohon isi kecamatan.',
            'kota.required' => 'Mohon isi kota/kabupaten.',
            'provinsi.required' => 'Mohon isi provinsi.',

            // Data Keluarga
            'no_kk.required' => 'Mohon isi Nomor Kartu Keluarga (KK).',
            'no_kk.numeric' => 'Nomor KK hanya boleh berisi angka.',
            'no_kk.digits' => 'Nomor Kartu Keluarga harus 16 digit angka. Cek kembali dokumen KK Anda.',
            'kepala_keluarga.required' => 'Mohon isi nama kepala keluarga sesuai KK.',
            'nama_ayah.required' => 'Mohon isi nama lengkap ayah.',
            'nik_ayah.required' => 'Mohon isi NIK ayah (16 digit sesuai KTP).',
            'nik_ayah.size' => 'NIK ayah harus tepat 16 digit. Cek kembali KTP ayah.',
            'tanggal_lahir_ayah.required' => 'Mohon isi tanggal lahir ayah.',
            'tanggal_lahir_ayah.before' => 'Tanggal lahir ayah harus sebelum hari ini.',
            'pendidikan_ayah.required' => 'Mohon pilih pendidikan terakhir ayah.',
            'pekerjaan_ayah.required' => 'Mohon isi pekerjaan ayah.',
            'penghasilan_ayah.required' => 'Mohon pilih kisaran penghasilan ayah.',
            'nama_ibu.required' => 'Mohon isi nama lengkap ibu.',
            'nik_ibu.required' => 'Mohon isi NIK ibu (16 digit sesuai KTP).',
            'nik_ibu.size' => 'NIK ibu harus tepat 16 digit. Cek kembali KTP ibu.',
            'tanggal_lahir_ibu.required' => 'Mohon isi tanggal lahir ibu.',
            'tanggal_lahir_ibu.before' => 'Tanggal lahir ibu harus sebelum hari ini.',
            'pendidikan_ibu.required' => 'Mohon pilih pendidikan terakhir ibu.',
            'pekerjaan_ibu.required' => 'Mohon isi pekerjaan ibu.',
            'penghasilan_ibu.required' => 'Mohon pilih kisaran penghasilan ibu.',

            // Dokumen Upload
            'foto.required' => 'Mohon unggah foto anak (pas foto terbaru).',
            'foto.image' => 'File foto anak harus berupa gambar (JPG/PNG).',
            'foto.mimes' => 'Format foto anak harus JPG atau PNG.',
            'foto.max' => 'Ukuran foto anak terlalu besar (maks. 2 MB). Coba foto ulang atau perkecil ukuran file.',
            'foto_kk.required' => 'Mohon unggah foto Kartu Keluarga (KK).',
            'foto_kk.image' => 'File KK harus berupa gambar (JPG/PNG). Jika KK berbentuk PDF, silakan screenshot terlebih dahulu.',
            'foto_kk.mimes' => 'Format foto KK harus JPG atau PNG.',
            'foto_kk.max' => 'Ukuran foto KK terlalu besar (maks. 2 MB). Coba foto ulang atau perkecil ukuran file.',
            'foto_akta.required' => 'Mohon unggah foto Akta Kelahiran.',
            'foto_akta.image' => 'File akta kelahiran harus berupa gambar (JPG/PNG).',
            'foto_akta.mimes' => 'Format foto akta harus JPG atau PNG.',
            'foto_akta.max' => 'Ukuran foto akta terlalu besar (maks. 2 MB). Coba foto ulang atau perkecil ukuran file.',

            // Living together and Wali
            'tinggal_bersama.required' => 'Mohon pilih dengan siapa anak tinggal.',
            'tinggal_bersama.in' => 'Pilihan tinggal bersama tidak valid.',
            'nama_wali.required' => 'Mohon isi nama lengkap wali anak.',
            'nik_wali.required' => 'Mohon isi NIK wali (16 digit sesuai KTP).',
            'nik_wali.numeric' => 'NIK wali hanya boleh berisi angka.',
            'nik_wali.digits' => 'NIK wali harus tepat 16 digit. Cek kembali KTP wali.',
            'hubungan_wali.required' => 'Mohon isi hubungan wali dengan anak.',
            'no_telpon_wali.required' => 'Mohon isi nomor telepon/WhatsApp wali yang bisa dihubungi.',
            'foto_ktp_ayah.required' => 'Mohon unggah foto KTP ayah kandung.',
            'foto_ktp_ayah.image' => 'File KTP ayah harus berupa gambar (JPG/PNG).',
            'foto_ktp_ayah.mimes' => 'Format foto KTP ayah harus JPG atau PNG.',
            'foto_ktp_ayah.max' => 'Ukuran foto KTP ayah terlalu besar (maks. 2 MB).',
            'foto_ktp_ibu.required' => 'Mohon unggah foto KTP ibu kandung.',
            'foto_ktp_ibu.image' => 'File KTP ibu harus berupa gambar (JPG/PNG).',
            'foto_ktp_ibu.mimes' => 'Format foto KTP ibu harus JPG atau PNG.',
            'foto_ktp_ibu.max' => 'Ukuran foto KTP ibu terlalu besar (maks. 2 MB).',
            'foto_ktp_wali.required' => 'Mohon unggah foto KTP wali.',
            'foto_ktp_wali.image' => 'File KTP wali harus berupa gambar (JPG/PNG).',
            'foto_ktp_wali.mimes' => 'Format foto KTP wali harus JPG atau PNG.',
            'foto_ktp_wali.max' => 'Ukuran foto KTP wali terlalu besar (maks. 2 MB).',
        ];
    }
}
