<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DiscontinueEnrollmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'final_alasan' => ['required', 'string', 'max:2000'],
            'final_catatan' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
