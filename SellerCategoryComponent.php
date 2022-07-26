<?php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class SellerCategoryComponent extends Component
{
    use WithPagination;

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();
        Session()->flash('message', 'Category has been deleted successfully');
    }
    public function render()
    {
        $categories =Category::paginate(5);
        return view('livewire.seller.seller-category-component',['categories'=>$categories])->layout('layouts.base');
    }
}
