<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SelesaiObservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tinggi_badan_cm' => ['required', 'numeric', 'between:30,200'],
            'berat_badan_kg' => ['required', 'numeric', 'between:5,100'],
            'catatan_wawancara_orang_tua' => ['required', 'string', 'max:5000'],
            'catatan_aktivitas_anak' => ['required', 'string', 'max:5000'],
            'catatan_kesiapan_anak' => ['required', 'string', 'max:5000'],
            'membutuhkan_dukungan_khusus' => ['required', 'boolean'],
            'catatan_kebutuhan_dukungan_khusus' => ['nullable', 'required_if:membutuhkan_dukungan_khusus,1', 'string', 'max:5000'],
            'catatan_sekolah' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tinggi_badan_cm' => 'tinggi badan (cm)',
            'berat_badan_kg' => 'berat badan (kg)',
            'catatan_wawancara_orang_tua' => 'catatan wawancara orang tua',
            'catatan_aktivitas_anak' => 'catatan aktivitas anak',
            'catatan_kesiapan_anak' => 'catatan kesiapan anak',
            'membutuhkan_dukungan_khusus' => 'kebutuhan dukungan khusus',
            'catatan_kebutuhan_dukungan_khusus' => 'catatan kebutuhan dukungan khusus',
            'catatan_sekolah' => 'catatan sekolah',
        ];
    }

    public function messages(): array
    {
        return [
            'tinggi_badan_cm.between' => 'Tinggi badan harus antara 30 dan 200 cm.',
            'berat_badan_kg.between' => 'Berat badan harus antara 5 dan 100 kg.',
            'catatan_kebutuhan_dukungan_khusus.required_if' => 'Catatan kebutuhan dukungan khusus wajib diisi jika membutuhkan dukungan khusus dipilih.',
        ];
    }
}
