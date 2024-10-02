<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use PlugAndPay\Sdk\Entity\Item;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Enum\PaymentStatus;
use PlugAndPay\Sdk\Filters\OrderFilter;
use PlugAndPay\Sdk\Service\Client;
use PlugAndPay\Sdk\Service\OrderService;

class PlugAndPayOrderService
{
    public function getOrders(int $page = 1): array
    {
        // TODO: Init once
        /** @var string $accessToken */
        $accessToken = config('services.plug_and_pay.api_key');

        $client = new Client(
            accessToken: $accessToken
        );

        $orderFilter = (new OrderFilter())
            ->mode(Mode::LIVE)
            ->invoiceStatus(InvoiceStatus::FINAL)
            ->productGroup('educatie')
            ->page($page)
            ->paymentStatus(PaymentStatus::PAID);

        return (new OrderService($client))
            ->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            )
            ->withPagination()
            ->get($orderFilter);
    }

    public function findOrder(int $id): Order
    {
        /** @var string $accessToken */
        $accessToken = config('services.plug_and_pay.api_key');

        $client = new Client(
            accessToken: $accessToken
        );

        return (new OrderService($client))
            ->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            )
            ->find($id);
    }

    /**
     * @param Order[] $orders
     */
    public function mapOrdersToArray(array $orders): array
    {
        // Convert Order objects to arrays
        return collect($orders)->map(function (Order $order): array {
            $fullName = $order->billing()->contact()->firstName() . ' ' . $order->billing()->contact()->lastName();

            // Get all item labels from itemInternal
            $productLabels = collect($order->items())->flatMap(function (Item $item): array {
                return [$item->label()];
            })->unique()->values();

            return [
                'id' => $order->id(),
                'invoice_number' => $order->invoiceNumber(),
                'invoice_date' => $order->createdAt(),
                'full_name' => $fullName,
                'product' => $productLabels->implode(', '),
                'amount' => $order->amount(),
                'amount_excluding_vat' => $order->amount(),
            ];
        })->toArray();
    }

    public function paginateOrders(mixed $orders, int $total, int $perPage, int $currentPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $orders,
            $total,
            $perPage,
            $currentPage,
            ['path' => '/sales-overview']
        );
    }
}
