<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str; // Jangan lupa import ini

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public CategoryForm $form;

    public bool $isEditMode = false;
    public ?Category $deletingCategory = null;

    // --- LOGIKA UTAMA SOLUSI 2 ---
    // Hook ini berjalan otomatis saat $this->form->name berubah
    public function updatedFormName($value)
    {
        // Cek apakah ini mode tambah (category null) atau edit mode false
        if (empty($this->form->category)) {
            $this->form->slug = Str::slug($value);
        }
    }
    // -----------------------------

    public function create()
    {
        $this->form->resetForm();
        $this->isEditMode = false;
        $this->dispatch('open-modal', name: 'modal-category');
    }

    public function edit(Category $category)
    {
        $this->form->setCategory($category);
        $this->isEditMode = true;
        $this->dispatch('open-modal', name: 'modal-category');
    }

    public function save()
    {
        $this->form->validate();

        if ($this->isEditMode) {
            $this->form->category->update([
                'name' => $this->form->name,
                'slug' => $this->form->slug,
            ]);
            $this->dispatch('event-saved', message: 'Kategori berhasil diperbarui!');
        } else {
            Category::create([
                'name' => $this->form->name,
                'slug' => $this->form->slug,
            ]);
            $this->dispatch('event-saved', message: 'Kategori berhasil dibuat!');
        }

        $this->dispatch('close-modal', name: 'modal-category');
    }

    public function confirmDelete(Category $category)
    {
        $this->deletingCategory = $category;
        $this->dispatch('open-modal', name: 'modal-delete');
    }

    public function delete()
    {
        if ($this->deletingCategory && $this->deletingCategory->events()->count() > 0) {
            $this->dispatch('error-toast', message: 'Kategori tidak bisa dihapus karena masih memiliki event terkait.');
            $this->deletingCategory = null;
            $this->dispatch('close-modal', name: 'modal-delete');
            return;
        }

        if ($this->deletingCategory) {
            $this->deletingCategory->delete();
            $this->dispatch('event-saved', message: 'Kategori berhasil dihapus.');
        }

        $this->deletingCategory = null;
        $this->dispatch('close-modal', name: 'modal-delete');
    }

    public function render()
    {
        $categories = Category::withCount('events')->latest()->paginate(10);

        return view('livewire.admin.categories.index', [
            'categories' => $categories,
        ])->title('Manajemen Kategori');
    }
}
