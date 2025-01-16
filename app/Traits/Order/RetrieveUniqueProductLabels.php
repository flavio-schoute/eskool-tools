<?php

declare(strict_types=1);

namespace App\Traits\Order;

use Illuminate\Support\Collection;
use PlugAndPay\Sdk\Entity\Order;

/**
 * @template TValue
 */
trait RetrieveUniqueProductLabels
{
    /**
     * Retrieve unique product labels for each item in the current order.
     *
     * @return \Illuminate\Support\Collection<int, TValue> Collection
     */
    public function getUniqueProductLabels(Order $order): Collection
    {
        return collect($order->items())
            ->pluck('label')
            ->unique()
            ->values();
    }
}
