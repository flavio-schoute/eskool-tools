<x-app-layout>

    <x-slot name="header">
        <x-header>
            {{ __('Verkoop overzicht') }}
        </x-header>
    </x-slot>

    <div class="py-12">

        <table class="table-auto">
            <th>
                <tr class="bg-white">
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Factuur nummer</th>
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Factuur datum</th>
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Naam</th>
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Product</th>
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Bedrag</th>
                    <th scope="col" class="py-3 px-4 uppercase text-sm tracking-wide">Actie's</th>
                </tr>
            </th>
            <tbody class="divide-y">
                @foreach ($orders as $order)
                    <tr class="even:bg-white odd:bg-gray-50 hover:bg-gray-100">
                        <td class="py-3 px-4 text-sm whitespace-nowrap">{{ $order['invoice_number'] }}</td>
                        <td class="py-3 px-4 text-sm whitespace-nowrap">{{ $order['invoice_date'] }}</td>
                        <td class="py-3 px-4 text-sm whitespace-nowrap">{{ $order['customer_full_name'] }}</td>
                        <td class="py-3 px-4 text-sm whitespace-nowrap">{{ $order['product'] }}</td>

                        <td class="py-3 px-4 text-sm whitespace-nowrap">
                            {{ Number::currency($order['amount'], locale: config('app.locale')) }}
                        </td>
                        
                        <td>
                            <x-dropdown-action-table align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium transition ease-in-out duration-150">
                                        <span>{{ __('Actions') }}</span>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <form 
                                        method="POST"
                                        action="{{ route('debtor-management.transfer-debtor-to-collection-agency', ['id' => $order['id']]) }}"
                                        class="inline">

                                        @csrf

                                        <button type="submit">
                                            Doorzetten naar Debtt
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div>
            {{ $orders->links() }}
        </div>
            
        {{-- <div class="max-w-full overflow-x-auto">
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
                                @foreach ($orders as $order)
                                    <tr class="{{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order['invoice_number'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order['invoice_date']->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order['customer_full_name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace text-sm text-gray-500">
                                            {{ $order['product'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            €{{ $order['amount'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                            <!-- Actions -->
                                            <div class="sm:flex sm:items-center sm:ms-6 inline">
                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                            <span>{{ __('Actions') }}</span>

                                                            <div class="ms-1">
                                                                <svg class="fill-current h-4 w-4"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        <form method="POST"
                                                            action="{{ route('debtor-management.store', ['id' => $order['id']]) }}"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-900">Doorzetten
                                                                naar Debtt</button>
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div> --}}


        <!-- You can open the modal using ID.showModal() method -->
        {{-- <button class="btn" onclick="my_modal_3.showModal()">open modal</button>
        <dialog id="my_modal_3" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box bg-white">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold">Hello!</h3>
                <p class="py-4">Press ESC key or click on ✕ button to close</p>
            </div>
        </dialog> --}}
    </div>
</x-app-layout>
