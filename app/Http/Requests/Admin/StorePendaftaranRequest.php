<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
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
            'tanggal_mpls' => ['nullable', 'date', 'after:tanggal_selesai'],
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
        ];
    }
}
