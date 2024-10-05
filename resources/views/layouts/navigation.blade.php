<nav class="w-full max-w-64 flex min-h-screen border-r-2 border-r-[#e6e7e8] p-4 bg-white ">
    <div class="w-full">
        <div>
            <x-application-logo class="block w-auto fill-current h-20" />
        </div>

        <div class="p-4">
            <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
        </div>

        <div>
            <ul class="flex flex-col gap-3">
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
</nav>