<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
{
    /**
     * Normalize browser-localized values (08.00) and SQL TIME values
     * (08:00:00) before the strict H:i rules are evaluated.
     */
    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach (['jam_mpls_mulai', 'jam_mpls_selesai', 'jam_masuk_kbm'] as $field) {
            $value = $this->input($field);
            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if (preg_match('/^\d{2}\.\d{2}$/', $value)) {
                $normalized[$field] = str_replace('.', ':', $value);
            } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
                $normalized[$field] = substr($value, 0, 5);
            }
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (! preg_match('/^(\d{4})\/(\d{4})$/', $value, $matches)) {
                        $fail('Format tahun ajaran harus YYYY/YYYY (contoh: 2026/2027).');

                        return;
                    }
                    $year1 = (int) $matches[1];
                    $year2 = (int) $matches[2];
                    if ($year2 !== $year1 + 1) {
                        $fail('Tahun kedua harus tepat satu tahun setelah tahun pertama (contoh: 2026/2027).');
                    }
                },
            ],
            'gelombang' => ['required', 'string', 'max:50'],
            'kuota' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:buka,tutup'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tanggal_mpls' => ['required', 'date', 'after:tanggal_selesai'],
            'jam_mpls_mulai' => ['nullable', 'date_format:H:i'],
            'jam_mpls_selesai' => [
                'nullable',
                'date_format:H:i',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $mulai = $this->input('jam_mpls_mulai');

                    // Format masing-masing field tetap ditangani oleh date_format.
                    if (! is_string($mulai) || ! is_string($value)
                        || ! preg_match('/^\d{2}:\d{2}$/', $mulai)
                        || ! preg_match('/^\d{2}:\d{2}$/', $value)) {
                        return;
                    }

                    if (Carbon::createFromFormat('!H:i', $value)
                        ->lessThanOrEqualTo(Carbon::createFromFormat('!H:i', $mulai))) {
                        $fail('Jam MPLS selesai harus lebih besar dari jam MPLS mulai.');
                    }
                },
            ],
            'lokasi_mpls' => ['nullable', 'string', 'max:255'],
            'informasi_mpls' => ['nullable', 'string', 'max:2000'],
            'tanggal_mulai_kbm' => ['nullable', 'date', 'after_or_equal:tanggal_mpls'],
            'jam_masuk_kbm' => ['nullable', 'date_format:H:i'],
            'informasi_kbm' => ['nullable', 'string', 'max:2000'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
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
            'tahun_ajaran' => 'tahun ajaran',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'tanggal_mpls' => 'tanggal MPLS',
            'jam_mpls_mulai' => 'jam MPLS mulai',
            'jam_mpls_selesai' => 'jam MPLS selesai',
            'lokasi_mpls' => 'lokasi MPLS',
            'tanggal_mulai_kbm' => 'tanggal mulai KBM',
            'jam_masuk_kbm' => 'jam masuk KBM',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'date_format' => 'Kolom :attribute harus menggunakan format HH:MM.',
            'after' => 'Kolom :attribute harus setelah :date.',
            'after_or_equal' => 'Kolom :attribute harus setelah atau sama dengan :date.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'min' => 'Kolom :attribute tidak memenuhi nilai minimum.',
            'in' => 'Pilihan :attribute tidak valid.',
            'max' => 'Kolom :attribute melebihi batas yang diizinkan.',
            'image' => 'File gambar harus berupa gambar yang valid.',
            'mimes' => 'Format file gambar harus JPG atau PNG.',
        ];
    }
}
