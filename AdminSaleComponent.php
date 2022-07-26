<?php

namespace App\Http\Livewire\Admin;

use App\Models\Sale;
use Livewire\Component;

class AdminSaleComponent extends Component
{
    public $sale_date;
    public $status;
    public function mount()
    {
        $sale = Sale::find(1);
        $this->sale_date=$sale->sale_date;
        $this->status=$sale->status;
    }
    public function updateSale()
    {
        $sale=Sale::find(1);
        $sale->sale_date=$sale->sale_date;
        $sale->status=$sale->status;
        $sale->save();
        Session()->flash('message','Record has been updated successfully!');
    }
    public function render()
    {
        return view('livewire.admin.admin-sale-component')->layout('layouts.base');
    }
}
