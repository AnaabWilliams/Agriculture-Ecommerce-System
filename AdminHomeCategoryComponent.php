<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\HomeCategory;
use Livewire\Component;

class AdminHomeCategoryComponent extends Component
{
    public $selected_categories=[];
    public $numberOfProducts;

    public function mount()
    {
        $category=HomeCategory::find(1);
        $this->selected_categories = explode(',',$category->sel_categories);
        $this->numberOfProducts = $category->number_of_products;
    }
    public function updateHomeCategory()
    {
        $category=Category::find(1);
        $category->sel_categories= implode(',',$this->selected_categories);
        $category->number_of_products=$this->numberOfProducts;
        $category->save();
        Session()->flash('message','Home Category has been updated successfully');
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-home-category-component',['categories'=>$categories])->layout('layouts.base');
    }
}
