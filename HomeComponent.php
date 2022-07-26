<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\HomeSlider;
use App\Models\HomeCategory;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;

class HomeComponent extends Component
{
   
    public function render()
    {
        $sliders =HomeSlider::where('status',1)->get();
        $lproducts = Product::orderBy('created_at','DESC')->get()->take(8);
        $category=Category::find(1);
        $cates=explode(',',$category->sel_categories);
        $categories=Category::whereIn('id',$cates)->get();
        $number_of_products=$category->number_of_products;
        $sproducts = Product::where('sale_price','>',0)->inRandomOrder()->get()->take(8);
        $sale=Sale::find(1);
        if(Auth::check())
        {
            Cart::instance('cart')->restore(Auth::user()->email);
        }
        return view('livewire.home-component',['sliders'=>$sliders,'lproducts'=>$lproducts,'categories'=>$categories,'number_of_products'=>$number_of_products,'sproducts'=>$sproducts,'sale'=>$sale])->layout('layouts.base');
    }
}
