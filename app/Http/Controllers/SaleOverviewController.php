<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;

class SaleOverviewController extends Controller
{
    public function __construct(
        private readonly PlugAndPayOrderService $orderService
    ) {
    }

    public function index(PaginationRequest $request): View
    {
        $orderResponse = $this->orderService->getOrders(
            $request->getPage()
        );

        $orders = $this->orderService->mapOrdersToArray(
            BodyToOrder::buildMulti($orderResponse['data'])
        );

        $paginatedOrders = $this->orderService->paginateOrders(
            orders: $orders,
            total: $orderResponse['meta']['total'],
            perPage: $orderResponse['meta']['per_page'],
            currentPage: $orderResponse['meta']['current_page']
        );

        return view('sales-overview.index', [
            'orders' => $paginatedOrders,
        ]);
    }

    public function store(): View
    {
        // $validData = $request->validated();

        return view('');
    }

    public function show(int $id): View
    {
        $order = $this->orderService->findOrder($id);

        return view('sales-overview.show', [
            'order' => $order,
        ]);
    }
}
