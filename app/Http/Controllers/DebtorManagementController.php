<?php

namespace App\Http\Controllers;

use App\Actions\Customer\CreateCustomerAction;
use App\Http\Requests\Order\ValidateOrderIdRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\User;
use App\Notifications\Slack\CustomerTransferedToIncassoMessage;
use App\Services\AddressService;
use App\Services\CustomerService;
use App\Services\DebtorManagementService;
use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Enum\PaymentStatus;

class DebtorManagementController extends Controller
{
    public function __construct(
        private readonly PlugAndPayOrderService $orderService,
        private readonly CustomerService $customerService,
        private readonly AddressService $addressService
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

    /** 
     * Step 1 -> WA - SMS - Email - Op basis van gegevens die we uit PP halen
     * Step 2 -> WA - SMS - Email - Op basis van gegevens die we uit PP halen - (Waarschuwing naar incasso)
     * Step 3 -> Incasso
     */

    // Rename to step1 or something else --> Rename this function
    public function TransferDebtorToCollectionAgency(ValidateOrderIdRequest $request)
    {
        /**
         * Validateer data -> Rquest moet veranderd worden - DONE
         * Haal de data bij PP op - Done
         * Zet in onze database - Done
         * Customer - Done
         * Address - Done
         * Koppelen - Done
         * Order koppelen met klant (deze in debiteurbeheer table)
         * Doorzettnen naar Debtt
         * Notificatie
         * Niet meer weergeven in rij dat moet in index gecontroleer worden
         */

        /** @var int $validatedDataId */
        $validatedDataId = $request->validated(['id']);

        // Get the data from the Plug & Pay API
        $order = $this->orderService->findOrder(
            id: $validatedDataId,
            includes: [
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES
            ]
        );

        $customer = $this->customerService->createCustomer([
            'full_name' => $order->billing()->contact()->firstName() . ' ' . $order->billing()->contact()->lastName(),
            'first_name' => $order->billing()->contact()->firstName(),
            'last_name' => $order->billing()->contact()->lastName(),
            'email' => $order->billing()->contact()->email(),
            'phone_number' => $order->billing()->contact()->telephone(),
        ]);

        $address = $this->addressService->createAddress([
            'address_line' => $order->billing()->address()->street() . ' ' . $order->billing()->address()->houseNumber(),
            'street' => $order->billing()->address()->street(),
            'house_number' => $order->billing()->address()->houseNumber(),
            'house_number_addition' => null, // TODO: Checken of er een toevoeging is
            'zipcode' => $order->billing()->address()->zipcode(),
            'city' => $order->billing()->address()->city(),
            'country' => $order->billing()->address()->country()->value,
        ]);

        $customer->addresses()->attach($address->id);

        $user = User::query()->get()->where('id', '=', 1);

        Notification::send($user, new CustomerTransferedToIncassoMessage());

        // Call the service

        return redirect()->route('debtor-management.index');
    }
}
