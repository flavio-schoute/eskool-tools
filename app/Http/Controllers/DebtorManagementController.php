<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;

class DebtorManagementController extends Controller
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

        return view('debtor-management.index', [
            'orders' => $paginatedOrders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug(' Fin');

        dd('In method');

        dd($request);
    }

    // public function handleIncollectibeInvoices() {
    //     $invoice = $this->event->data->object;
    //     $stripe = Cashier::stripe();

    //     if (isset($invoice->charge)) {

    //     $user = User::where('stripe_id', $invoice->customer)->first();

    //     if (sizeof($invoice->customer_tax_ids) > 0 || isset($user->coc_number)) {
    //         $type = 'zakelijk';
    //         if ($invoice->lines->data[0]->plan->interval == 'year') {
    //             $auth = config('credentials.DEBTT_YEARLY');
    //         } else {
    //             $auth = config('credentials.DEBTT_MONTHLY');
    //         }

    //     } else {
    //         $type = 'particulier';
    //         if ($invoice->lines->data[0]->plan->interval == 'year') {
    //             $auth = config('credentials.DEBTT_YEARLY_PART');
    //         } else {
    //             $auth = config('credentials.DEBTT_MONTHLY_PART');
    //         }
    //     }

    //     $request = Http::post('https://dossier.debtt.com/api/v2/', [
    //         'function' => 'createcase',
    //         'auth' => $auth,
    //         'debiteur' => [
    //             [
    //                 'vorm' => $type,
    //                 'naam' => $invoice->customer_name,
    //                 'adres' => $invoice->customer_address->line1,
    //                 'postcode' => $invoice->customer_address->postal_code,
    //                 'plaats' => $invoice->customer_address->city,
    //                 'email' => $invoice->customer_email,
    //                 'mobiel' => $invoice->customer_phone,
    //                 'debiteurnr' => $invoice->number.'-uncollectable-api',
    //                 'factuur' => [
    //                     [
    //                         'nummer' => $invoice->number,
    //                         'datum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
    //                         'vervaldatum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
    //                         'bedrag' => $invoice->amount_paid / 100,
    //                     ],
    //                 ],
    //             ],
    //         ],
    //     ]);
    //     }
    // }
}
