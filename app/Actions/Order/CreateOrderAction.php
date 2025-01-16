<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Actions\Action;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOrderAction extends Action
{
    // Maybe single responsabileity
    public function execute(mixed ...$attributes): Order
    {
        /** @var Order $order */
        $order = DB::transaction(function () use ($attributes): Order {
            $orderTransaction = new Order($attributes);

            Log::info('A new order has been created');

            /** @var Customer $customer */
            $customer = $orderTransaction->customer()->firstOrCreate(
                attributes: ['email' => $attributes['email']],
                values: $attributes
            );

            Log::info('Retrieved or created a customer: {customer}', ['customer' => $customer]);

            /** @var Address $address */
            $address = $customer->address()->firstOrCreate(
                values: $attributes
            );

            Log::info('Retrieved or created an address: {address}', ['address' => $address]);

            $orderTransaction->billingAddress()->associate($address);

            $orderTransaction->save();

            Log::info('Order with id {id} has been saved', ['id' => $orderTransaction]);

            return $orderTransaction;
        }, attempts: 3);

        return $order;
    }
}
