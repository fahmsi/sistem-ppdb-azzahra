<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SiswaValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    use SiswaValidationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->siswaRules(documentsRequired: false, isStore: false);
    }

    public function messages(): array
    {
        return $this->siswaMessages();
    }

    public function attributes(): array
    {
        return $this->siswaAttributes();
    }
}
