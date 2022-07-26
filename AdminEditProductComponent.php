<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image as Image;

class AdminEditProductComponent extends Component
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
    public $catrgory_id;
    public $newImage;
    public $product_id;


    public function mount($product_slug)
    {
        // $product = Product::where('slug',$this->product_slug)->first();
        $product = new Product();
        $this->name=$product->name;
        $this->slug=$product->slug;
        $this->short_description=$product->short_description;
        $this->description=$product->description;
        $this->regular_price=$product->regular_price;
        $this->sale_price=$product->sale_price;
        $this->stock_status=$product->stock_status;
        $this->farmer_name=$product->farmer_name;
        $this->featured=$product->featured;
        $this->quantity=$product->quantity;
        $this->farmer_location=$product->farmer_location;
        $this->farmer_phone=$product->farmer_phone;
        $this->image=$product->image;
        $this->catrgory_id=$product->category_id;
        $this->product_id=$product->id;
        $product->save();
    }
    public function generateSlug()
    {
        $this->slug = Str::slug($this->name,'-');
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
            'newimage'=>'required|mimes:jpeg,png',
            'catrgory_id'=>'required'
        ]);
    }
    public function updateProduct()
    {
        $this->validate([
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
            'newimage'=>'required|mimes:jpeg,png',
            'catrgory_id'=>'required'
        ]);
        $product=Product::find($this->product_id);
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
        // if($this->newimage)
        // {
        //     $imageName =Carbon::now()->timestamp. '.' . $this->newImage->extension();
        //     $this->newimage->storeAs('products',$imageName);
        //     $product->image=$imageName;
        // }
        // $product->category_id=$this->category_id;
        // $product->save();
        // Session()->flash('message','Product has been updated successfully');
    }
    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-edit-product-component',['categories'=>$categories])->layout('layouts.base');
    }
}
