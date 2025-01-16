<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\ClaimOrderRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\ClaimedOrder;
use App\Models\Order;
use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\PaymentStatus;

class SaleOverviewController extends Controller
{
    public function __construct(
        private readonly PlugAndPayOrderService $orderService
    ) {
    }

    public function index(PaginationRequest $request)
    {
        $filters = [
            'mode' => Mode::LIVE,
            'invoiceStatus' => InvoiceStatus::FINAL,
            'productGroup' => 'educatie',
            'paymentStatus' => [PaymentStatus::PAID, PaymentStatus::OPEN],
        ];

        $response = $this->orderService->getOrders($filters, $request->getPage());

        $orders = $this->orderService->mapOrdersToArray(
            orders: BodyToOrder::buildMulti($response[0]['data'])
        );

        $paginatedOrders = $this->orderService->paginateOrders(
            orders: $orders,
            meta: $response[0]['meta'],
            path: '/sales-overview'
        );

        return view('sales-overview.index', [
            'orders' => $paginatedOrders,
        ]);
    }

    public function store(ClaimOrderRequest $request)
    {
        $validatedData = $request->validated();

        // Todo: Fix correct data and id
        $order = Order::query()->create([
            'plug_and_play_order_id' => '123',
            'invoice_number' => $validatedData->invoice_number,
            'invoice_date' => '01-01-2024',
            'full_name' => $validatedData->full_name,
            'products' => $validatedData->product,
            'price' => $validatedData->amount_excluding_vat,
            'price_with_tax' => $validatedData->amount,
        ]);

        ClaimedOrder::query()->create([
            'order_id' => $order->id,
            'user_id' => Auth::user()->id,
            'status' => OrderStatus::CLAIMED,
        ]);

        session()->flash('message', 'Post successfully updated.');

        return redirect()->route('sales-overview.index')->with('success', 'Order claimed!');
    }

    // public function show(int $id): View
    // {
    //     $order = $this->orderService->findOrder($id);

    //     return view('sales-overview.show', [
    //         'order' => $order,
    //     ]);
    // }
}
