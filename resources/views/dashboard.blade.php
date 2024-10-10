<x-app-layout>
    <x-slot name="header">
        <x-header />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (auth()->user()->login_count == 1)
                        <span>Welkom {{ auth()->user()->name }}, je logt voor de eerste keer in!</span>
                    @else
                        <span>Welkom terug {{ auth()->user()->name }} </span>
                    @endif
                </div>
                <div class="p-6">
                    <span id="current-time">{{ \Carbon\Carbon::now()->format('d/m/Y - H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    setInterval(() => {
        document.getElementById('current-time').innerText = new Date().toLocaleString();
    }, 1000);
</script>
