<?php

namespace App\Http\Livewire\Admin;

use App\Models\contactus;
use Livewire\Component;

class AdmincontactusComponent extends Component
{
    public function render()
    {
        $contactuses = contactus::paginate(12);
        //dd($contactuses);
        return view('livewire.admin.admincontactus-component',['contactuses'=>$contactuses])->layout('layouts.base');
    }
}
