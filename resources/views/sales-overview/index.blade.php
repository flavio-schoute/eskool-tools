<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Verkoop overzicht') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-full overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-x-auto">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice Number
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Full Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $rowIndex = 0; ?>
                                @foreach ($orders as $order)
                                    <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button
                                                onclick="Livewire.dispatch('openModal', { component: 'view-invoice', arguments: { orderId: {{ $order['id'] }} }})"
                                                class="hover:text-blue-500 hover:underline">{{ $order['invoice_number'] }}</button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $order['invoice_date']->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $order['full_name'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $order['product'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            €{{ $order['amount_excluding_vat'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                            <form method="POST" action="{{ route('sales-overview.store', $order) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-900">Claim</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $rowIndex++; ?>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
