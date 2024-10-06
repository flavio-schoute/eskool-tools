<div class="p-6">
    <div class="flex justify-between font-bold items-center">
        <a class="text-lg md:text-2xl hover:underline hover:text-blue flex group"
            href='https://admin.plugandpay.com/orders/{{ $order->id() }}' target="_blank" rel="noopener noreferrer">
            <span class="mr-1">{{ $order->invoiceNumber() }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-4 hidden group-hover:block">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </a>

        <button wire:click="closeModal" class="py-1 px-2 rounded bg-slate-100 hover:bg-slate-200">&#10005;</button>
    </div>

    <hr class="my-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-bold mb-2 uppercase">Order informatie</h3>
            <div class="text-md">
                <p><span class="font-semibold">Order id:</span> {{ $order->id() }}</p>
                <p><span class="font-semibold">Factuurnummer:</span> {{ $order->invoiceNumber() }}</p>
                <p><span class="font-semibold">Factuur datum:</span> {{ $order->createdAt()->format('d/m/Y - H:i') }}
                </p>
                <p><span class="font-semibold">Betaald (Excl. BTW):</span>
                    &euro;{{ number_format($order->amount(), 2) }}</p>
                <p><span class="font-semibold">BTW bedrag:</span>
                    &euro;{{ number_format($order->taxes()[0]->amount(), 2) }}</p>
                <p><span class="font-semibold">Totaal:</span> &euro;{{ number_format($order->amountWithTax(), 2) }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-2 uppercase">Klant gegevens</h3>
            <div class="md-5">
                <p><span class="font-semibold">Naam:</span> {{ $order->billing()->contact()->firstName() }}
                    {{ $order->billing()->contact()->lastName() }}</p>
                <p><span class="font-semibold">Email:</span> {{ $order->billing()->contact()->email() }}</p>
                <p><span class="font-semibold">Telefoonnummer:</span> {{ $order->billing()->contact()->telephone() }}
                </p>
                <p><span class="font-semibold">Adres:</span> {{ $order->billing()->address()->street() }}
                    {{ $order->billing()->address()->houseNumber() }}</p>
                <p><span class="font-semibold">Plaats:</span> {{ $order->billing()->address()->city() }}</p>
                <p><span class="font-semibold">Postcode:</span> {{ $order->billing()->address()->zipcode() }}</p>
                <p><span class="font-semibold">Land:</span> {{ $order->billing()->address()->country()->name }}</p>
            </div>
        </div>
    </div>
</div>
