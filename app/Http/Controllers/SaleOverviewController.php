<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;
use PlugAndPay\Sdk\Entity\Item;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Enum\PaymentStatus;
use PlugAndPay\Sdk\Filters\OrderFilter;
use PlugAndPay\Sdk\Service\Client;
use PlugAndPay\Sdk\Service\OrderService;

class SaleOverviewController extends Controller
{
    public function index(Request $request): View
    {
        /** @var int $page */
        $page = $request->get('page') ?? 1;

        /** @var string $accessToken */
        $accessToken = config('services.plug_and_pay.api_key');

        $client = new Client(
            accessToken: $accessToken
        );

        $orderService = new OrderService($client);

        $orderFilter = (new OrderFilter())
            ->mode(Mode::LIVE)
            ->invoiceStatus(InvoiceStatus::FINAL)
            ->productGroup('educatie')
            ->page($page)
            ->paymentStatus(PaymentStatus::PAID);

        $orderResponse  = $orderService
            ->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            )
            ->get($orderFilter);

        $orders = BodyToOrder::buildMulti($orderResponse->body()['data']);

        // Convert Order objects to arrays
        $orders = collect($orders)->map(function (Order $order): array {
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
                'amount_excluding_vat' => $order->amount(),
            ];
        })->toArray();

        $currentPage = $orderResponse->body()['meta']['current_page'];
        $perPage = $orderResponse->body()['meta']['per_page'];
        $total = $orderResponse->body()['meta']['total'];

        $paginatedOrders = new LengthAwarePaginator(
            $orders,         // The data to paginate (array of orders)
            $total,          // Total number of orders
            $perPage,        // Number of items per page
            $currentPage,    // Current page number
            ['path' => '/sales-overview'] // Pagination path for generating links
        );

        return view('sales-overview.index', [
            'orders' => $paginatedOrders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): View
    {
        // Todo: Implement
        return view('');
    }

    public function show(int $id): View
    {
        /** @var string $accessToken */
        $accessToken = config('services.plug_and_pay.api_key');

        $client = new Client(
            accessToken: $accessToken
        );

        $orderService = new OrderService($client);

        $order = $orderService
            ->include(
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            )
            ->find($id);

        return view('sales-overview.show', [
            'order' => $order,
        ]);
    }
}
