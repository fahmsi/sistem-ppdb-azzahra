<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => ['required', 'date', 'after:now'],
            'catatan_jadwal' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'scheduled_at' => 'jadwal observasi',
            'catatan_jadwal' => 'catatan jadwal',
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.after' => 'Jadwal observasi harus setelah waktu saat ini.',
        ];
    }
}
