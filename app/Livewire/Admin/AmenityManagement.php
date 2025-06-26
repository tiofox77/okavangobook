<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Amenity;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class AmenityManagement extends Component
{
    use WithPagination;
    
    // Propriedades públicas para formulário de criação/edição
    #[Validate('required|min:2|max:255')]
    public string $name = '';
    
    #[Validate('nullable|max:255')]
    public ?string $icon = null;
    
    #[Validate('required|in:hotel,room')]
    public string $type = 'hotel';
    
    #[Validate('nullable|max:1000')]
    public ?string $description = null;
    
    #[Validate('boolean')]
    public bool $is_active = true;
    
    #[Validate('integer|min:0')]
    public int $display_order = 0;
    
    // Propriedades para controle do estado do formulário
    public ?int $editing_id = null;
    public string $filter_type = 'all';
    public string $search = '';
    public string $sortField = 'display_order';
    public string $sortDirection = 'asc';
    public bool $showModal = false;
    
    /**
     * Método de montagem do componente, executado ao inicializar
     */
    public function mount(): void
    {
        // Inicialização padrão
    }
    
    /**
     * Método para limpar os campos do formulário
     */
    private function resetForm(): void
    {
        $this->reset([
            'name', 'icon', 'type', 'description', 'is_active', 'display_order',
            'editing_id', 'showModal'
        ]);
        $this->resetValidation();
    }
    
    /**
     * Abre o modal para criar nova comodidade
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    /**
     * Abre o modal para editar uma comodidade existente
     */
    public function edit(int $id): void
    {
        $this->resetForm();
        $this->editing_id = $id;
        
        $amenity = Amenity::findOrFail($id);
        $this->name = $amenity->name;
        $this->icon = $amenity->icon;
        $this->type = $amenity->type;
        $this->description = $amenity->description;
        $this->is_active = $amenity->is_active;
        $this->display_order = $amenity->display_order;
        
        $this->showModal = true;
    }
    
    /**
     * Salva a comodidade (criação ou edição)
     */
    public function save(): void
    {
        $validatedData = $this->validate();
        
        try {
            DB::beginTransaction();
            
            if ($this->editing_id) {
                // Atualização de comodidade existente
                $amenity = Amenity::findOrFail($this->editing_id);
                $amenity->update($validatedData);
                $message = 'Comodidade atualizada com sucesso';
            } else {
                // Criação de nova comodidade
                Amenity::create($validatedData);
                $message = 'Comodidade criada com sucesso';
            }
            
            DB::commit();
            $this->resetForm();
            $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error', 
                'message' => 'Erro ao salvar comodidade: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Exclui uma comodidade
     */
    public function delete(int $id): void
    {
        try {
            $amenity = Amenity::findOrFail($id);
            $amenity->delete();
            
            $this->dispatch('notify', [
                'type' => 'success', 
                'message' => 'Comodidade excluída com sucesso'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error', 
                'message' => 'Erro ao excluir comodidade: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Altera o tipo de filtro
     */
    public function filterByType(string $type): void
    {
        $this->filter_type = $type;
        $this->resetPage();
    }
    
    /**
     * Alterna o estado ativo/inativo da comodidade no formulário
     */
    public function toggleActive(): void
    {
        $this->is_active = !$this->is_active;
    }

    /**
     * Fecha o modal e reseta o formulário
     */
    public function closeModal(): void
    {
        $this->resetForm();
        $this->showModal = false;
    }
    
    /**
     * Alterna o estado ativo/inativo de uma comodidade específica no banco de dados
     */
    public function toggleAmenityActive(int $id): void
    {
        try {
            $amenity = Amenity::findOrFail($id);
            $amenity->is_active = !$amenity->is_active;
            $amenity->save();
            
            $this->dispatch('notify', [
                'type' => 'success', 
                'message' => 'Estado da comodidade atualizado com sucesso'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error', 
                'message' => 'Erro ao alterar estado da comodidade: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Altera o campo e direção de ordenação
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    /**
     * Renderiza o componente
     */
    public function render()
    {
        $query = Amenity::query();
        
        // Aplica filtro de tipo
        if ($this->filter_type !== 'all') {
            $query->where('type', $this->filter_type);
        }
        
        // Aplica pesquisa
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        // Aplica ordenação
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $amenities = $query->paginate(10);
        
        // Contadores para dashboard
        $counts = [
            'total' => Amenity::count(),
            'hotel' => Amenity::hotel()->count(),
            'room' => Amenity::room()->count(),
            'active' => Amenity::where('is_active', true)->count(),
        ];
        
        return view('livewire.admin.amenity-management', [
            'amenities' => $amenities,
            'counts' => $counts
        ])->layout('layouts.admin');
    }
}
