<?php

namespace App\Livewire;

use App\Services\PlugAndPayOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use LivewireUI\Modal\ModalComponent;

class ViewInvoice extends ModalComponent
{
    public int $orderId;

    private readonly PlugAndPayOrderService $orderService;

    public function mount(int $orderId): void
    {
        $this->orderId = $orderId;
        $this->orderService = App::make(PlugAndPayOrderService::class);
    }

    public function render(): View
    {
        $order = $this->orderService->findOrder($this->orderId);

        return view('livewire.view-invoice', [
            'order' => $order
        ]);
    }

    public static function modalMaxWidth(): string
    {
        return '5xl';
    }


    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
