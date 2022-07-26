<?php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Category;

class SellerAddCategoryComponent extends Component
{
    public $name;
    public $slug;

    public function generateslug()
    {
        $this->slug=Str::slug($this->name);
    }
    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name'=>'required',
            'slug'=>'required|unique:categories'
        ]);
    }

    public function storeCategory()
    {
        $this->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories'
        ]);
        $category = new Category();
        $category->name=$this->name;
        $category->slug=$this->slug;
        $category->save();
        session()->flush('message','Category has been created successfully');
    }
    public function render()
    {
        return view('livewire.seller.seller-add-category-component')->layout('layouts.base');;
    }
}
