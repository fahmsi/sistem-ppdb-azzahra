<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SiswaValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreSiswaRequest extends FormRequest
{
    use SiswaValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return $this->siswaRules(documentsRequired: true, isStore: true);
    }

    public function attributes(): array
    {
        return $this->siswaAttributes();
    }
}
