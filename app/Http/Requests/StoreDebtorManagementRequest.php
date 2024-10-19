<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDebtorManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Todo: Check for permissions
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric'],
            'invoice_number' => ['required', 'string', 'starts_with:ESKOOL'],
            'invoice_status' => ['required', 'string'],
            'invoice_mode' => ['required', 'string'],
            'customer_full_name' => ['required', 'string'],
            'customer_email' => ['required', 'email:rfc,dns'],
            'customer_first_name' => ['required', 'string'],
            'customer_last_name' => ['required', 'string'],
            'customer_phone_number' => ['required', 'string'],
            'customer_tax_exempt' => ['required', 'string'],
            'customer_address' => ['required', 'string'],
            'customer_street' => ['required', 'string'],
            'customer_house_number' => ['required', 'string'],
            'customer_zipcode' => ['required', 'string'],
            'customer_city' => ['required', 'string'],
            'customer_country' => ['required', 'string'],
            'product' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'amount_with_tax' => ['required', 'numeric'],
            'mollie_customer_id' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
            'payment_provider' => ['required', 'string'],
            'payment_status' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
        ];
    }
}
