<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Address\CreateAddressAction;
use App\Actions\Customer\CreateCustomerAction;
use App\Actions\Debtor\CreateDebtorAction;
use App\Actions\Order\CreateOrderAction;
use App\Enums\Debtor\DebtorStatus;
use App\Http\Requests\Order\ValidateOrderIdRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\User;
use App\Notifications\Slack\CustomerTransferedToIncassoMessage;
use App\Services\AddressService;
use App\Services\CustomerService;
use App\Services\PlugAndPayOrderService;
use App\Traits\Order\RetrieveUniqueProductLabels;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification;
use PlugAndPay\Sdk\Director\BodyTo\BodyToOrder;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\InvoiceStatus;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\OrderIncludes;
use PlugAndPay\Sdk\Enum\PaymentStatus;

class DebtorManagementController extends Controller
{
    use RetrieveUniqueProductLabels;

    public function __construct(
        private readonly PlugAndPayOrderService $orderService,
        private readonly CreateCustomerAction $createCustomerAction,
        private readonly CreateAddressAction $createAddressAction,
        private readonly CreateOrderAction $createOrderAction,
        private readonly CreateDebtorAction $createDebtorAction,
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
            'statuses' => DebtorStatus::cases(),
        ]);
    }

    // Todo: Add return and move method to an other location maybe
    public function sendFirstReminder(ValidateOrderIdRequest $request): void
    {
        /** @var int $validatedDataId */
        $validatedDataId = $request->validated(['id']);

        $order = $this->findOrderById($validatedDataId);

        $order = $this->createOrderAction->execute(
            attributes: $this->mapOrderData($order)
        );

        $this->createDebtorAction->execute([
            'customer_id' => $order->customer()->get('id')->id,
            'order_id' => $order->id,
            'status' => DebtorStatus::FIRST_REMINDER,
        ]);
    }

    /**
     * Step 1 -> WA - SMS - Email - Op basis van gegevens die we uit PP halen
     * Step 2 -> WA - SMS - Email - Op basis van gegevens die we uit PP halen - (Waarschuwing naar incasso)
     * Step 3 -> Incasso.
     */

    // Rename to step1 or something else --> Rename this function
    public function transferDebtorToCollectionAgency(ValidateOrderIdRequest $request)
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
         * Niet meer weergeven in rij dat moet in index gecontroleer worden.
         */

        /** @var int $validatedDataId */
        $request->validated(['id']);

        // $customer = $this->customerService->createCustomer([
        //     'full_name' => $order->billing()->contact()->firstName() . ' ' . $order->billing()->contact()->lastName(),
        //     'first_name' => $order->billing()->contact()->firstName(),
        //     'last_name' => $order->billing()->contact()->lastName(),
        //     'email' => $order->billing()->contact()->email(),
        //     'phone_number' => $order->billing()->contact()->telephone(),
        // ]);

        // $address = $this->addressService->createAddress([
        //     'address_line' => $order->billing()->address()->street() . ' ' . $order->billing()->address()->houseNumber(),
        //     'street' => $order->billing()->address()->street(),
        //     'house_number' => $order->billing()->address()->houseNumber(),
        //     'house_number_addition' => null, // TODO: Checken of er een toevoeging is
        //     'zipcode' => $order->billing()->address()->zipcode(),
        //     'city' => $order->billing()->address()->city(),
        //     'country' => $order->billing()->address()->country()->value,
        // ]);

        // $customer->addresses()->attach($address->id);

        // $user = User::query()->get()->where('id', '=', 1);

        // Notification::send($user, new CustomerTransferedToIncassoMessage());

        // // Call the service

        return redirect()->route('debtor-management.index');
    }

    // Todo specifx array
    private function mapOrderData(Order $order): array
    {
        $customerFirstName = $order->billing()->contact()->firstName();
        $customerLastName = $order->billing()->contact()->lastName();
        $customerFullName = $customerFirstName . ' ' . $customerLastName;

        $customerStreet = $order->billing()->address()->street();
        $customerHouseNumber = $order->billing()->address()->houseNumber();

        $products = $this->getUniqueProductLabels($order)->implode(', ');

        return [
            'first_name' => $customerFirstName,
            'last_name' => $customerLastName,
            'email' => $order->billing()->contact()->email(),
            'phone_number' => $order->billing()->contact()->telephone(),

            // Address data
            'address_line' => $customerStreet . ' ' . $customerHouseNumber,
            'street' => $customerStreet,
            'house_number' => $customerHouseNumber,
            'postal_code' => $order->billing()->address()->zipcode(),
            'city' => $order->billing()->address()->city(),
            'country' => $order->billing()->address()->country()->value,

            // Order data
            'plug_and_play_order_id' => $order->id(),
            'invoice_number' => $order->invoiceNumber(),
            'invoice_date' => $order->createdAt(),
            'full_name' => $customerFullName,
            'products' => $products,
            'amount' => $order->amount(),
            'amount_with_tax' => $order->amountWithTax(),
            'tax_amount' => $order->taxes()['amount'],
            'contact_person' => null, // Todo: Fix later (currently no option to get contact person from the SDK)
        ];
    }

    // Todo: Maybde move this function to a other location
    private function findOrderById(int $id): Order
    {
        return $this->orderService->findOrder(
            id: $id,
            includes: [
                OrderIncludes::BILLING,
                OrderIncludes::ITEMS,
                OrderIncludes::PAYMENT,
                OrderIncludes::TAXES,
            ]
        );
    }
}
