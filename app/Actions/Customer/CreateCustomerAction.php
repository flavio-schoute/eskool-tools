<?php

namespace App\Actions\Customer;

use App\Actions\Action;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CreateCustomerAction extends Action
{
    public function execute(mixed ...$attributes): Customer
    {
        $customer = Customer::query()->firstOrCreate(
            attributes: ['email' => $attributes['email']],
            values: $attributes
        );

        Log::info('Retrieved or created a customer: {customer}', ['customer' => $customer]);

        return $customer;
    }
}
