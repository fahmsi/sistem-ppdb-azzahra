<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionDecisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keputusan_status' => [
                'required',
                'in:diterima,tidak_diterima,perlu_tindak_lanjut,mengundurkan_diri',
            ],
            'keputusan_catatan' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'keputusan_alasan' => [
                'nullable',
                'string',
                'max:2000',
                'required_if:keputusan_status,tidak_diterima',
                'required_if:keputusan_status,perlu_tindak_lanjut',
                'required_if:keputusan_status,mengundurkan_diri',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'keputusan_status.required' => 'Status keputusan harus dipilih.',
            'keputusan_status.in' => 'Status keputusan tidak valid.',
            'keputusan_catatan.max' => 'Catatan keputusan maksimal 2000 karakter.',
            'keputusan_alasan.required_if' => 'Alasan keputusan wajib diisi untuk status selain diterima.',
            'keputusan_alasan.max' => 'Alasan keputusan maksimal 2000 karakter.',
        ];
    }
}
