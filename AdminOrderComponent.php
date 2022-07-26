<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminOrderComponent extends Component
{
    public function updateOrderStatus($order_id,$status)
    {
        $order =Order::find($order_id);
        $order->status=$status;
        if($order == "delivered")
        {
            $order->delivered_date =DB::raw('CURRENT_DATE');
        }
        else if ($order == "canceled")
        {
            $order->canceled_date =DB::raw('CURRENT_DATE');
        }
        $order->save();
        Session()->flash('order_message','order status has been updated successfully!');
    }
    public function render()
    {
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view('livewire.admin.admin-order-component',['orders'=>$orders])->layout('layouts.base');
    }
}
