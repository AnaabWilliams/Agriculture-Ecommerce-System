<?php

namespace App\Http\Livewire;

use App\Models\contactus;
use App\Models\Setting;
use Livewire\Component;

class ContactusComponent extends Component
{
    public $name;
    public $email;
    public $phone;
    public $comment;

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required|numeric',
            'comment'=>'required'
        ]);
    }
    public function sendMessage()
    {
        $this->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required|numeric',
            'comment'=>'required'
        ]);
        $contactus =new contactus();
        $contactus->name=$this->name;
        $contactus->email=$this->email;
        $contactus->phone=$this->phone;
        $contactus->comment=$this->comment;
        $contactus->save();
        Session()->flash('message','Thank you, your message has been sent successfully!');
    }
    public function render()
    {
        $setting= Setting::find(1);
        return view('livewire.contactus-component',['setting'=>$setting])->layout('layouts.base');
    }
}
