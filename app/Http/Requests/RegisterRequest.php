<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'terms_accepted' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mohon isi nama lengkap Anda sebagai orang tua/wali.',
            'name.min' => 'Nama lengkap minimal harus terdiri dari 3 karakter.',
            'name.max' => 'Nama lengkap terlalu panjang (maksimal 255 karakter).',
            'email.required' => 'Alamat email diperlukan untuk login ke sistem.',
            'email.email' => 'Format alamat email tidak valid (contoh: nama@email.com).',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
            'password.required' => 'Mohon tentukan kata sandi untuk keamanan akun Anda.',
            'password.min' => 'Kata sandi minimal harus terdiri dari 8 karakter agar akun Anda aman.',
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, dan angka.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms_accepted.accepted' => 'Anda harus menyetujui Syarat dan Ketentuan serta Kebijakan Privasi untuk melanjutkan pendaftaran akun.',
        ];
    }
}
