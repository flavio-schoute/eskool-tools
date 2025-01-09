<?php

namespace App\Traits\Order;

use Illuminate\Support\Collection;
use PlugAndPay\Sdk\Entity\Item;
use PlugAndPay\Sdk\Entity\Order;

trait RetrieveUniqueProductLabels
{
    /**
     * Retrieve unique product labels for each item in the current order.
     *
     * @return \Illuminate\Support\Collection<int, string> Collection
     */
    public function getUniqueProductLabels(Order $order): Collection
    {
        return collect($order->items())
            ->flatMap(function (Item $item): array {
                return [$item->label()];
            })
            ->unique()
            ->values();
    }
}
