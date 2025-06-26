<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Location;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class LocationManagement extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    // Propriedades para formulário
    public ?int $locationId = null;
    public string $name = '';
    public string $province = '';
    public ?string $description = '';
    public ?string $image = '';
    public ?string $capital = '';
    public ?int $population = null;
    public ?bool $isFeatured = false;
    
    // Para upload de nova imagem
    public $newImage;
    
    // Filtros e pesquisa
    public string $search = '';
    public ?string $featuredFilter = null;
    
    // Modo de visualização (list ou grid)
    public string $viewMode = 'list';
    
    // Estado do modal
    public bool $showModal = false;
    
    // Regras de validação
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capital' => 'nullable|string|max:255',
            'population' => 'nullable|integer|min:0',
            'isFeatured' => 'boolean',
            'newImage' => 'nullable|image|max:1024', // max 1MB
        ];
    }
    
    public function mount(): void
    {
        // Verificar se o utilizador tem permissão para aceder à gestão de localizações
        if (!auth()->check() || !auth()->user()->hasRole('Admin')) {
            redirect()->route('login');
        }
        
        // Inicializar o modo de visualização a partir da sessão, se disponível
        $this->viewMode = session('location_view_mode', 'list');
    }
    
    // Alternar entre os modos de visualização
    public function toggleViewMode(string $mode): void
    {
        $this->viewMode = $mode;
        session(['location_view_mode' => $mode]);
    }
    
    public function render()
    {
        // Obter todas as localizações com filtragem e pesquisa
        $locationsQuery = Location::query()
            ->when($this->search, function ($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('province', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->featuredFilter !== null, function ($query) {
                return $query->where('is_featured', $this->featuredFilter === '1');
            });
            
        $locations = $locationsQuery->paginate(10);
        
        return view('livewire.admin.location-management', [
            'locations' => $locations,
        ])->layout('layouts.admin');
    }
    
    public function openModal(?int $locationId = null): void
    {
        $this->resetValidation();
        $this->reset('name', 'province', 'description', 'image', 'capital', 'population', 'isFeatured', 'newImage');
        
        // Se for edição, carregar os dados da localização
        if ($locationId) {
            $this->locationId = $locationId;
            $location = Location::findOrFail($locationId);
            $this->name = $location->name;
            $this->province = $location->province;
            $this->description = $location->description ?? '';
            $this->image = $location->image ?? '';
            $this->capital = $location->capital ?? '';
            $this->population = $location->population;
            $this->isFeatured = (bool) $location->is_featured;
        }
        
        $this->showModal = true;
    }
    
    public function closeModal(): void
    {
        $this->showModal = false;
    }
    
    public function save(): void
    {
        $validatedData = $this->validate();
        
        // Processar o upload de imagem se houver
        $imagePath = $this->image;
        if ($this->newImage) {
            $imagePath = $this->newImage->store('locations', 'public');
        }
        
        if ($this->locationId) {
            // Atualizar localização existente
            $location = Location::findOrFail($this->locationId);
            $location->update([
                'name' => $this->name,
                'province' => $this->province,
                'description' => $this->description,
                'image' => $imagePath,
                'capital' => $this->capital,
                'population' => $this->population,
                'is_featured' => $this->isFeatured,
            ]);
            
            session()->flash('message', 'Localização atualizada com sucesso!');
        } else {
            // Criar nova localização
            Location::create([
                'name' => $this->name,
                'province' => $this->province,
                'description' => $this->description,
                'image' => $imagePath,
                'capital' => $this->capital,
                'population' => $this->population,
                'is_featured' => $this->isFeatured,
            ]);
            
            session()->flash('message', 'Localização criada com sucesso!');
        }
        
        $this->closeModal();
    }
    
    public function delete(int $locationId): void
    {
        // Verificar se existem hotéis associados a esta localização
        $location = Location::findOrFail($locationId);
        $hotelCount = $location->hotels()->count();
        
        if ($hotelCount > 0) {
            session()->flash('error', "Não é possível eliminar esta localização. Existem {$hotelCount} hotéis associados.");
            return;
        }
        
        $location->delete();
        session()->flash('message', 'Localização eliminada com sucesso!');
    }
    
    /**
     * Alterna o status de destaque de uma localização
     *
     * @param int $locationId ID da localização
     * @return void
     */
    public function toggleFeatured(int $locationId): void
    {
        $location = Location::findOrFail($locationId);
        $location->update([
            'is_featured' => !$location->is_featured
        ]);
        
        $status = $location->is_featured ? 'destacada' : 'removida dos destaques';
        session()->flash('message', "Localização {$location->name} foi {$status} com sucesso!");
    }
}
