<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProductComponent extends Component
{
    use WithPagination;

    public function deleteProduct($id)
    {
        $product=Product::find($id);
        if($product->image)
        {
            if (file_exists(public_path($product->image))){

                unlink('assets/images/products'.'/' .$product->image);
            }
        }
        if($product->images)
        {
            $images = explode(",",$product->images);
            foreach($images as $image)
            {
                if (file_exists(public_path($product->image))){

                    // unlink('assets/images/products'.'/' .$product->image);
                    unlink('assets/images/products'.'/' .$image);
                }
            }
        }
        $product->delete();
        Session()->flash('message','product has been deleted successfullly');

    }
    public function render()
    {
       $products = Product::paginate(10);

        return view('livewire.admin.admin-product-component',['products'=>$products])->layout('layouts.base');
    }
}
