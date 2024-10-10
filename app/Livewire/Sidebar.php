<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Sidebar extends Component
{
    public $showSidebar = false;

    public function toggleSidebar(): void
    {
        $this->showSidebar = !$this->showSidebar;
    }

    public function render(): View
    {
        return view('livewire.sidebar');
    }
}
