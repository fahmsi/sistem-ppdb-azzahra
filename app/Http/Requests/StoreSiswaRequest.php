<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SiswaValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    use SiswaValidationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->siswaRules(documentsRequired: true);
    }

    public function attributes(): array
    {
        return $this->siswaAttributes();
    }
}
