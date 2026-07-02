<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'qris' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_qris' => ['nullable', 'boolean'],
            'payment_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'bank_name' => 'nama bank',
            'account_number' => 'nomor rekening',
            'account_holder_name' => 'nama pemilik rekening',
            'amount' => 'nominal pembayaran',
            'qris' => 'gambar QRIS',
            'payment_note' => 'catatan pembayaran',
        ];
    }
}
