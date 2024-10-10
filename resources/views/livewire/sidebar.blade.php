<div class="min-h-screen bg-white border-r border-r-gray-500 max-w-72 w-full">
    <x-application-logo class="p-4 block fill-current h-40" />

    <div class="px-6 py-3">
        <hr>
    </div>

    <div>
        <ul>
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>

            <x-nav-link :href="route('sales-overview.index')" :active="request()->routeIs('sales-overview.index')">
                {{ __('Sales overzicht') }}
            </x-nav-link>

            <x-nav-link :href="route('debtor-management.index')" :active="request()->routeIs('debtor-management.index')">
                {{ __('Debiteuren overzicht') }}
            </x-nav-link>
        </ul>
    </div>
</div>
