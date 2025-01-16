<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClaimOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'invoice_number' => ['required', 'string', 'regex:/^ESKOOL\d+$/'],
            'full_name' => ['required', 'string'],
            'product' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'amount_excluding_vat' => ['required', 'numeric'],
        ];
    }
}
