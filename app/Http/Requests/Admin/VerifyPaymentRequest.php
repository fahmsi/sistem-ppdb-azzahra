<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['lunas', 'ditolak'])],
            'catatan_admin' => ['required_if:status,ditolak', 'nullable', 'string', 'max:1000'],
        ];
    }
}
