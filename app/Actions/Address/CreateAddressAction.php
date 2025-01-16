<?php

declare(strict_types=1);

namespace App\Actions\Address;

use App\Actions\Action;
use App\Models\Address;
use Illuminate\Support\Facades\Log;

class CreateAddressAction extends Action
{
    public function execute(array $attributes): Address
    {
        $address = Address::query()->firstOrCreate(
            values: $attributes
        );

        Log::info('Retrieved or created an address: {address}', ['address' => $address]);

        return $address;
    }
}
