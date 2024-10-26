<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class ValidateOrderIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Todo: Check for permissions
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'integer']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => (int) $this->id
        ]);
    }
}
