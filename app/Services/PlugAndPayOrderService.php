<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use PlugAndPay\Sdk\Entity\Item;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Filters\OrderFilter;
use PlugAndPay\Sdk\Service\OrderService;

class PlugAndPayOrderService
{
    public function __construct(
        private OrderService $orderService
    ) {
    }

    public function getOrders(array $filters = [], int $page = 1, array $includes = []): array
    {
        $orderFilter = $this->buildOrderFilter($filters, $page);

        $orderService = $this->orderService->withPagination();

        if (!empty($includes)) {
            $orderService->include(...$includes);
        } else {
            $orderService->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            );
        }

        return $orderService->get($orderFilter);
    }

    public function findOrder(int $id, array $includes = []): Order
    {
        $orderService = $this->orderService;

        if (!empty($included)) {
            $orderService->include(...$includes);
        } else {
            $orderService->include(
                OrderIncludes::BILLING,
                OrderIncludes::COMMENTS,
                OrderIncludes::DISCOUNTS,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES
            );
        }

        return $orderService->find($id);
    }

    public function mapOrdersToArray(array $orders): array
    {
        return collect($orders)->map(function (Order $order): array {
            $customer = $order->billing()->contact();
            $fullName = $customer->firstName() . ' ' . $customer->lastName();

            $productLabels = collect($order->items())->flatMap(function (Item $item): array {
                return [$item->label()];
            })->unique()->values();

            $address = $order->billing()->address();
            $street = $address->street();
            $houseNumber = $address->houseNumber();

            $payment = $order->payment();

            return [
                // General information
                'id' => $order->id(),
                'invoice_number' => $order->invoiceNumber(),
                'invoice_status' => $order->invoiceStatus()->value,
                'invoice_date' => Carbon::parse($order->createdAt(), 'Europe/Amsterdam')->format('d M. \'y H:i'),
                'invoice_mode' => $order->mode()->value,

                // Customer information
                'customer_full_name' => $fullName,
                'customer_email' => $customer->email(),
                'customer_first_name' => $customer->firstName(),
                'customer_last_name' => $customer->lastName(),
                'customer_phone_number' => $customer->telephone(),
                'customer_company' => $customer->company(),
                'customer_vat_id' => $customer->vatIdNumber(),
                'customer_tax_exempt' => $customer->taxExempt()->value,

                // Customer address information
                'customer_address' => "$street $houseNumber",
                'customer_street' => $street,
                'customer_house_number' => $houseNumber,
                'customer_zipcode' => $address->zipcode(),
                'customer_city' => $address->city(),
                'customer_country' => $address->country()->value,

                // Product information
                'product' => $productLabels->implode(', '),
                'amount' => $order->amount(),
                'amount_with_tax' => $order->amountWithTax(),

                // Payment information
                'mollie_customer_id' => $payment->customerId(),
                'payment_method' => $payment->method()?->value,
                'invoice_paid_at' => $payment->paidAt(),
                'payment_provider' => $payment->provider()?->value,
                'payment_status' => $payment->status()->value,
                'transaction_id' => $payment->transactionId(),
            ];
        })->toArray();
    }

    public function paginateOrders(mixed $orders, array $meta, string $path = ''): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $orders,
            $meta['total'],
            $meta['per_page'],
            $meta['current_page'],
            ['path' => $path]
        );
    }

    private function buildOrderFilter(array $filters, int $page): OrderFilter
    {
        $orderFilter = new OrderFilter();
        $orderFilter->page($page);

        foreach ($filters as $method => $value) {
            if (method_exists($orderFilter, $method)) {
                if (is_array($value)) {
                    $orderFilter->{$method}(...$value);
                } else {
                    $orderFilter->{$method}($value);
                }
            }
        }

        return $orderFilter;
    }
}
