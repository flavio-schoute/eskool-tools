<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DebtorManagementService
{
    public function __construct(
        private readonly PlugAndPayOrderService $orderService
    ) {
    }

    // TOOD: Refactor -> Param: Order $order
    public function handleIncollectibeInvoices(int $orderId): void
    {
        /** @var string $url */
        $url = config('services.debtt.api_url');

        /** @var string $authKey */
        $authKey = config('services.debtt.api_key');

        // Get the order
        $order = $this->orderService->findOrder($orderId);

        $debtorType = empty($order->billing()->contact()->company()) ? 'particulier' : 'zakelijk';

        Http::post($url, [
            'function' => 'createcase',
            'auth' => $authKey,
            'debiteur' => [
                [
                    'vorm' => $debtorType,
        //             'naam' => $invoice->customer_name,
        //             'adres' => $invoice->customer_address->line1,
        //             'postcode' => $invoice->customer_address->postal_code,
        //             'plaats' => $invoice->customer_address->city,
        //             'email' => $invoice->customer_email,
        //             'mobiel' => $invoice->customer_phone,
        //             'debiteurnr' => $invoice->number.'-uncollectable-api',
        //             'factuur' => [
        //                 [
        //                     'nummer' => $invoice->number,
        //                     'datum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
        //                     'vervaldatum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
        //                     'bedrag' => $invoice->amount_paid / 100,
        //                 ],
        //             ],
                ],
            ],
        ]);

        // $invoice = $this->event->data->object;
        // $stripe = Cashier::stripe();

        // if (isset($invoice->charge)) {

        // $user = User::where('stripe_id', $invoice->customer)->first();

        // if (sizeof($invoice->customer_tax_ids) > 0 || isset($user->coc_number)) {
        //     $type = 'zakelijk';
        //     if ($invoice->lines->data[0]->plan->interval == 'year') {
        //         $auth = config('credentials.DEBTT_YEARLY');
        //     } else {
        //         $auth = config('credentials.DEBTT_MONTHLY');
        //     }

        // } else {
        //     $type = 'particulier';
        //     if ($invoice->lines->data[0]->plan->interval == 'year') {
        //         $auth = config('credentials.DEBTT_YEARLY_PART');
        //     } else {
        //         $auth = config('credentials.DEBTT_MONTHLY_PART');
        //     }
        // }

        // $request = Http::post($url, [
        //     'function' => 'createcase',
        //     'auth' => $authKey,
        //     'debiteur' => [
        //         [
        //             'vorm' => $type,
        //             'naam' => $invoice->customer_name,
        //             'adres' => $invoice->customer_address->line1,
        //             'postcode' => $invoice->customer_address->postal_code,
        //             'plaats' => $invoice->customer_address->city,
        //             'email' => $invoice->customer_email,
        //             'mobiel' => $invoice->customer_phone,
        //             'debiteurnr' => $invoice->number.'-uncollectable-api',
        //             'factuur' => [
        //                 [
        //                     'nummer' => $invoice->number,
        //                     'datum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
        //                     'vervaldatum' => Carbon::createFromTimestamp($invoice->created)->format('d-m-Y'),
        //                     'bedrag' => $invoice->amount_paid / 100,
        //                 ],
        //             ],
        //         ],
        //     ],
        // ]);

        // return $request;
    }
}
