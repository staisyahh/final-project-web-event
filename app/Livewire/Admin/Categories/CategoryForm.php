<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Form;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class CategoryForm extends Form
{
    public ?Category $category = null;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $slug = '';

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->category?->id),
            ],
        ];
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
    }

    public function resetForm()
    {
        $this->reset();
    }
}
