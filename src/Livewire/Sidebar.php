<?php

namespace Saivra\MeUi\Livewire;

use Illuminate\Contracts\View\View;

class Sidebar extends \Filament\Livewire\Sidebar
{
    public function render(): View
    {
        return view('meui::livewire.sidebar');
    }
}
