<?php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use App\Http\Middleware\AuthSeller;
class SellerDashboardComponent extends Component
{
    public function render()
    {
        $orders = Order::orderBy('created_at','DESC')->get()->take(10);
        $totalSales = Order::where('status','delivered')->count();
        $totalRevenue =Order::where('status','delivered')->sum('total');
        $todaySales = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->count();
        $todayRevenue = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->sum('total');
        return view('livewire.seller.seller-dashboard-component',['orders'=>$orders,
            'todaySales'=>$totalSales,
            'totalRevenue'=>$totalRevenue,
            'todaySales'=>$todaySales,
            'totalSales'=>$totalSales,'todayRevenue'=>$todayRevenue
        ])->layout('layouts.base');
    }
}
