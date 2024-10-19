<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreDebtorManagementRequest;
use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\PaymentStatus;

class DebtorManagementController extends Controller
{
    public function __construct(
        private readonly PlugAndPayOrderService $orderService
    ) {
    }

    public function index(PaginationRequest $request): View
    {
        // Refactor this deplicated of SalesOverview controller
        $filters = [
            'mode' => Mode::LIVE,
            'invoiceStatus' => InvoiceStatus::FINAL,
            'productGroup' => 'educatie',
            'paymentStatus' => [PaymentStatus::PAID, PaymentStatus::OPEN],
        ];

        $orderResponse = $this->orderService->getOrders($filters, $request->getPage());

        $orders = $this->orderService->mapOrdersToArray(
            orders: BodyToOrder::buildMulti($orderResponse['data'])
        );

        $paginatedOrders = $this->orderService->paginateOrders(
            orders: $orders,
            meta: $orderResponse['meta'],
            path: '/sales-overview'
        );

        return view('debtor-management.index', [
            'orders' => $paginatedOrders,
        ]);
    }

    public function store(StoreDebtorManagementRequest $request)
    {
        //
    }
}
