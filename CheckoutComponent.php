<?php

namespace App\Http\Livewire;

use App\Mail\orderMail;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Cart;



class CheckoutComponent extends Component
{
    public $deliver_to_different;

    public $firstname;
    public $lastname;
    public $email;
    public $mobile;
    public $line1;
    public $line2;
    public $res_address;
    public $region;
    public $town_city;

    public $d_firstname;
    public $d_lastname;
    public $s_email;
    public $d_mobile;
    public $d_res_address;
    public $d_region;
    public $d_town_city;

    public $paymentMode;

    public $thankyou;

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'line1'=>'required',
            'line2'=>'required',
            'res_address'=>'required',
            'region'=>'required',
            'town_city'=>'required',
            'paymentMode'=>'required'
        ]);
        if($this->deliver_to_different)
        {
            $this->validateOnly($fields,[
                'd_firstname'=>'required',
                'd_lastname'=>'required',
                's_email'=>'required|email',
                'd_mobile'=>'required|numeric',
                'd_res_address'=>'required',
                'd_region'=>'required',
                'd_town_city'=>'required'
            ]);
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'line1'=>'required',
            'line2'=>'required',
            'res_address'=>'required',
            'region'=>'required',
            'town_city'=>'required',
            'paymentMode'=>'required'
        ]);
        $order = new Order();
        $order->user_id =Auth::user()->id;
        $order->subtotal = Session()->get('checkout')['subtotal'];
        $order->discount = Session()->get('checkout')['discount'];
        $order->tax = Session()->get('checkout')['tax'];
        $order->total=Session()->get('checkout')['total'];
        $order->firstname=$this->firstname;
        $order->lastname=$this->lastname;
        $order->email=$this->email;
        $order->mobile=$this->mobile;
        $order->line1=$this->line1;
        $order->line2=$this->line2;
        $order->res_address=$this->res_address;
        $order->region=$this->region;
        $order->town_city=$this->town_city;
        $order->status='ordered';
        $order->is_delivery_different=$this->deliver_to_different ? 1:0;
        $order->save();


        foreach(Cart::instance('cart')->content() as $item)
        {
            $orderItem = new OrderItem();
            $orderItem->product_id =$item->id;
            $orderItem->order_id=$order->id;
            dd($orderItem->order_id);
            $orderItem->price =$item->price;
            $orderItem->quantity=$item->qty;
            $orderItem->save();
        }

        if($this->deliver_to_different)
        {
            $this->validate([
                'd_firstname'=>'required',
                'd_lastname'=>'required',
                's_email'=>'required|email',
                'd_mobile'=>'required|numeric',
                'd_res_address'=>'required',
                'd_region'=>'required',
                'd_town_city'=>'required'
            ]);
            $delivery =new Delivery();
            $delivery->order_id =$order->id;
            $delivery->firstname=$this->d_firstname;
            $delivery->lastname=$this->d_lastname;
            $delivery->email=$this->s_email;
            $delivery->mobile=$this->d_mobile;
            $delivery->res_address=$this->d_res_address;
            $delivery->region=$this->d_region;
            $delivery->town_city=$this->d_town_city;
            $delivery->save();
        }

        if($this->paymentMode =='cod')
        {
            $transaction= new Transaction();
            $transaction->user_id=Auth::user()->id;
            $transaction->order_id=$order->id;
            $transaction->mode='cod';
            $transaction->status='pending';
            $transaction->save();
        }
        $this->thankyou=1;
        Cart::instance('cart')->destroy();
        Session()->forget('checkout');

        $this->sendOrderConfirmationMail($order);
    }

    public function sendOrderConfirmationMail($order)
    {
        Mail::to($order->email)->send(new orderMail($order));
    }
    public function verifyForCheckout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }
        else if($this->thankyou)
        {
            return redirect()->route('thankyou');
        }
        else if(!session()->get('checkout'))
        {
            return redirect()->route('product.cart');
        }
    }
    public function render()
    {
        $this->verifyForCheckout();
        return view('livewire.checkout-component')->layout('layouts.base');
    }
}
