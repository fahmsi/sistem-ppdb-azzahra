<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RescheduleObservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => ['required', 'date', 'after:now'],
            'reschedule_reason' => ['required', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'scheduled_at' => 'jadwal baru',
            'reschedule_reason' => 'alasan penjadwalan ulang',
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.after' => 'Jadwal baru harus setelah waktu saat ini.',
            'reschedule_reason.required' => 'Alasan penjadwalan ulang wajib diisi.',
        ];
    }
}
