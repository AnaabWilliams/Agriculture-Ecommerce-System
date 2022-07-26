<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class AdminCategoryComponent extends Component
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
        $Categories = Category::paginate(5);
        return view('livewire.admin.admin-category-component', ['categories'=>$Categories])->layout('layouts.base');
    }
}
