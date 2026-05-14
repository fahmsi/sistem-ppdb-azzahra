<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
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

            // Documents optional on update
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kk' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_akta' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama anak',
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
        ];
    }
}
