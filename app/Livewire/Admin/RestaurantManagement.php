<?php

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\HotelRestaurantItem;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class RestaurantManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $itemId = null;
    public $hotel_id = null;
    public $name = '';
    public $description = '';
    public $category = '';
    public $price = '';
    public $image = null;
    public $imageUpload = null;
    public $is_available = true;
    public $is_vegetarian = false;
    public $is_vegan = false;
    public $is_gluten_free = false;
    public $allergens = [];
    public $allergenInput = '';
    public $preparation_time = null;
    public $is_spicy = false;
    public $display_order = 0;

    public $search = '';
    public $filterHotel = null;
    public $filterCategory = null;
    public $filterAvailability = null;

    public $showModal = false;
    public $showDeleteModal = false;
    public $itemToDelete = null;

    protected $categories = [
        'Entrada' => 'Entrada',
        'Prato Principal' => 'Prato Principal',
        'Sobremesa' => 'Sobremesa',
        'Bebida' => 'Bebida',
        'Snack' => 'Snack',
        'Café da Manhã' => 'Café da Manhã',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            abort(401, 'Não autenticado.');
        }

        $user = auth()->user();
        if (!$user->hasRole('Admin') && !$user->managedHotels()->exists()) {
            abort(403, 'Não tem permissão para aceder a esta página.');
        }
    }

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'imageUpload' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_vegetarian' => 'boolean',
            'is_vegan' => 'boolean',
            'is_gluten_free' => 'boolean',
            'preparation_time' => 'nullable|integer|min:0',
            'is_spicy' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterHotel()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $item = HotelRestaurantItem::findOrFail($id);

        $user = auth()->user();
        if (!$user->hasRole('Admin') && $item->hotel->user_id !== $user->id) {
            session()->flash('error', 'Não tem permissão para editar este item.');
            return;
        }

        $this->itemId = $item->id;
        $this->hotel_id = $item->hotel_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->category = $item->category;
        $this->price = $item->price;
        $this->image = $item->image;
        $this->is_available = $item->is_available;
        $this->is_vegetarian = $item->is_vegetarian;
        $this->is_vegan = $item->is_vegan;
        $this->is_gluten_free = $item->is_gluten_free;
        $this->allergens = $item->allergens ?? [];
        $this->preparation_time = $item->preparation_time;
        $this->is_spicy = $item->is_spicy;
        $this->display_order = $item->display_order;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->imageUpload) {
            $imagePath = $this->imageUpload->store('restaurant-items', 'public');
            
            if ($this->itemId && $this->image) {
                Storage::disk('public')->delete($this->image);
            }
            
            $this->image = $imagePath;
        }

        $data = [
            'hotel_id' => $this->hotel_id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'image' => $this->image,
            'is_available' => $this->is_available,
            'is_vegetarian' => $this->is_vegetarian,
            'is_vegan' => $this->is_vegan,
            'is_gluten_free' => $this->is_gluten_free,
            'allergens' => $this->allergens,
            'preparation_time' => $this->preparation_time,
            'is_spicy' => $this->is_spicy,
            'display_order' => $this->display_order,
        ];

        if ($this->itemId) {
            HotelRestaurantItem::find($this->itemId)->update($data);
            session()->flash('message', 'Item atualizado com sucesso!');
        } else {
            HotelRestaurantItem::create($data);
            session()->flash('message', 'Item criado com sucesso!');
        }

        $this->closeModal();
    }

    public function addAllergen()
    {
        if (!empty($this->allergenInput)) {
            $this->allergens[] = $this->allergenInput;
            $this->allergenInput = '';
        }
    }

    public function removeAllergen($index)
    {
        unset($this->allergens[$index]);
        $this->allergens = array_values($this->allergens);
    }

    public function confirmDelete($id)
    {
        $this->itemToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->itemToDelete) {
            $item = HotelRestaurantItem::findOrFail($this->itemToDelete);
            
            $user = auth()->user();
            if (!$user->hasRole('Admin') && $item->hotel->user_id !== $user->id) {
                session()->flash('error', 'Não tem permissão para eliminar este item.');
                $this->showDeleteModal = false;
                return;
            }

            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();
            session()->flash('message', 'Item eliminado com sucesso!');
        }

        $this->showDeleteModal = false;
        $this->itemToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->itemId = null;
        $this->hotel_id = null;
        $this->name = '';
        $this->description = '';
        $this->category = '';
        $this->price = '';
        $this->image = null;
        $this->imageUpload = null;
        $this->is_available = true;
        $this->is_vegetarian = false;
        $this->is_vegan = false;
        $this->is_gluten_free = false;
        $this->allergens = [];
        $this->allergenInput = '';
        $this->preparation_time = null;
        $this->is_spicy = false;
        $this->display_order = 0;
        $this->resetErrorBag();
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');

        $query = HotelRestaurantItem::with('hotel')
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('hotel', function ($hq) use ($user) {
                    $hq->where('user_id', $user->id);
                });
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('description', 'like', '%' . $this->search . '%')
                       ->orWhereHas('hotel', function ($hq) {
                           $hq->where('name', 'like', '%' . $this->search . '%');
                       });
                });
            })
            ->when($this->filterHotel, function ($q) {
                $q->where('hotel_id', $this->filterHotel);
            })
            ->when($this->filterCategory, function ($q) {
                $q->where('category', $this->filterCategory);
            })
            ->when($this->filterAvailability !== null, function ($q) {
                $q->where('is_available', $this->filterAvailability);
            })
            ->orderBy('hotel_id')
            ->orderBy('category')
            ->orderBy('display_order');

        $hotelsQuery = Hotel::orderBy('name');
        if (!$isAdmin) {
            $hotelsQuery->where('user_id', $user->id);
        }

        return view('livewire.admin.restaurant-management', [
            'items' => $query->paginate(15),
            'hotels' => $hotelsQuery->get(),
            'categories' => $this->categories,
        ])->layout('layouts.admin');
    }
}
