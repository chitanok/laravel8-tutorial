<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $name;
    public function render()
    {
        $this->name = 'Chitano';
        return view('livewire.counter');
    }
}
