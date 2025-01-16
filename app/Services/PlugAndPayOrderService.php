<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use PlugAndPay\Sdk\Entity\Item;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Filters\OrderFilter;
use PlugAndPay\Sdk\Service\Client;
use PlugAndPay\Sdk\Service\OrderService;
use PlugAndPay\Sdk\Support\Parameters;

class PlugAndPayOrderService extends OrderService
{
    /** @var OrderIncludes[] */
    public array $includes = [];

    public function __construct(
        private readonly Client $client
    ) {
        parent::__construct($client);
    }

    public function include(OrderIncludes ...$includes): self
    {
        $this->includes = $includes;

        return $this;
    }

    public function getOrders(array $filters = [], int $page = 1, array $includes = []): array
    {
        $orderFilter = $this->buildOrderFilter($filters, $page);

        if ($includes !== []) {
            $this->include(...$includes);
        } else {
            $this->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            );
        }

        return $this->get($orderFilter);
    }

    public function get(?OrderFilter $orderFilter = null): array
    {
        $parameters = $orderFilter instanceof \PlugAndPay\Sdk\Filters\OrderFilter ? $orderFilter->parameters() : [];
        if ($this->includes !== []) {
            $parameters['include'] = $this->includes;
        }

        $query = Parameters::toString($parameters);

        $response =  $this->client->get("/v2/orders$query");

        return $response->body();
    }

    public function mapOrdersToArray(array $orders): array
    {
        return collect($orders)->map(function (Order $order): array {
            $customer = $order->billing()->contact();
            $fullName = $customer->firstName() . ' ' . $customer->lastName();

            $productLabels = collect($order->items())->flatMap(fn (Item $item): array => [$item->label()])->unique()->values();

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

    // @phpstan-ignore missingType.generics
    public function paginateOrders(array $orders, array $meta, string $path = ''): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $orders,
            $meta['total'],
            $meta['per_page'],
            $meta['current_page'],
            ['path' => $path]
        );
    }

    /**
     * @template TValue
     * @param array<string, TValue> $filters
     */
    private function buildOrderFilter(array $filters, int $page): OrderFilter
    {
        $orderFilter = (new OrderFilter())->page($page);

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
