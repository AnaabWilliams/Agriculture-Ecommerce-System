<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image as Image;


class AdminAddProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $sale_price;
    public $stock_status;
    public $farmer_name;
    public $featured;
    public $quantity;
    public $farmer_location;
    public $farmer_phone;
    public $image;
    public $category_id;
    public $images;

    public function mount()
    {
        $this->stock_status ='instock';
        $this->featured =0;
    }
    public function generateSlug()
    {
        $this->slug=Str::slug($this->name, '-');
    }
    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name'=>'required',
            'slug'=>'required|unique:products',
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required|numeric',
            'sale_price'=>'numeric',
            'stock_status'=>'required',
            'farmer_name'=>'required|string',
            'quantity'=>'required|numeric',
            'farmer_location'=>'required',
            'farmer_phone'=>'required|numeric',
            'image'=>'required|mimes:jpeg,png',
            'category_id'=>'required'
        ]);
    }
    public function addProduct()
    {
        $this->validate([
            'name'=>'required',
            'slug'=>'required|unique:products',
             //'short_description'=>'required',
            //'description'=>'required',
            'regular_price'=>'required|numeric',
            'sale_price'=>'numeric',
            'stock_status'=>'required',
            'farmer_name'=>'required|string',
            'quantity'=>'required|numeric',
            'farmer_location'=>'required',
            'farmer_phone'=>'required|numeric',
            'image'=>'required|mimes:jpeg,png',
            'category_id'=>'required'
        ]);

        $product= new Product();
        $product->name=$this->name;
        $product->slug=$this->slug;
        $product->farmer_name=$this->farmer_name;
        $product->farmer_location=$this->farmer_location;
        $product->farmer_phone=$this->farmer_phone;
        $product->short_description=$this->short_description;
        $product->description=$this->description;
        $product->regular_price=$this->regular_price;
        $product->sale_price=$this->sale_price;
        $product->stock_status=$this->stock_status;
        $product->featured=$this->featured;
        $product->quantity=$this->quantity;
        $imgName =Carbon::now()->timestamp.'.' . $this->image->getClientOriginalExtension();
        $location = public_path('assets/images/products/'. $imgName);
        Image::make($this->image)->resize(1479,969)->save($location);
        $product->image=$imgName;
        if($this->images)
        {
            $imageName =" ";
            foreach($this->images as $key=>$image)
            {
                $imgName =Carbon::now()->timestamp. $key. '.' . $image->getClientOriginalExtension();
                $location = public_path('assets/images/products/'. $imgName);
                 Image::make($image)->resize(1479,969)->save($location);
               // $image->storeAs('products',$imgName);
                $imageName = $imageName . '.' .$imgName.",";
            }
            $product->images=$imageName;
        }
        $product->category_id=$this->category_id;
        $product->save();
        Session()->flash('message','Product has been created successfully!');
    }
    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-add-product-component',['categories'=>$categories])->layout('layouts.base');
    }
}
