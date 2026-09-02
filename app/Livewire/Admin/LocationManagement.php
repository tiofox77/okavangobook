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
    public ?string $provinceFilter = null;
    
    // Modo de visualização (list ou grid)
    public string $viewMode = 'list';
    
    // Estado do modal
    public bool $showModal = false;
    public bool $showViewModal = false;
    public ?Location $viewingLocation = null;

    // ----- Galeria multimédia do destino (fotos + vídeos) -----
    public bool $showGalleryModal = false;
    public ?int $galleryLocationId = null;
    public string $galleryLocationName = '';
    public string $newMediaType = 'image';
    public string $newMediaUrl = '';
    public string $newMediaTitle = '';
    public $newMediaFile;
    
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
            })
            ->when($this->provinceFilter, function ($query) {
                return $query->where('province', $this->provinceFilter);
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
    
    /**
     * Visualiza uma localização (somente leitura)
     */
    public function view(int $locationId): void
    {
        $this->viewingLocation = Location::withCount('hotels')->findOrFail($locationId);
        $this->showViewModal = true;
    }
    
    /**
     * Fecha o modal de visualização
     */
    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->viewingLocation = null;
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
    
    /* ================= Galeria multimédia do destino ================= */

    public function openGallery(int $locationId): void
    {
        $location = Location::findOrFail($locationId);
        $this->galleryLocationId = $location->id;
        $this->galleryLocationName = $location->name;
        $this->reset('newMediaUrl', 'newMediaTitle', 'newMediaFile');
        $this->newMediaType = 'image';
        $this->resetValidation();
        $this->showGalleryModal = true;
    }

    public function closeGallery(): void
    {
        $this->showGalleryModal = false;
        $this->galleryLocationId = null;
        $this->reset('newMediaUrl', 'newMediaTitle', 'newMediaFile');
    }

    /** Itens da galeria do destino aberto (usado pela vista). */
    public function getGalleryItemsProperty()
    {
        if (! $this->galleryLocationId || ! \Illuminate\Support\Facades\Schema::hasTable('location_media')) {
            return collect();
        }

        return \App\Models\LocationMedia::where('location_id', $this->galleryLocationId)
            ->orderBy('position')->orderBy('id')->get();
    }

    public function addMedia(): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        if (! \Illuminate\Support\Facades\Schema::hasTable('location_media')) {
            session()->flash('error', 'A tabela da galeria ainda não existe. Corra as migrações em Actualizações → Base de Dados.');
            return;
        }

        $isUpload = $this->newMediaType === 'image' && $this->newMediaFile;

        $this->validate(
            $isUpload
                ? ['newMediaFile' => 'required|image|max:4096', 'newMediaTitle' => 'nullable|string|max:255']
                : ['newMediaUrl' => 'required|string|max:1000', 'newMediaTitle' => 'nullable|string|max:255'],
            [
                'newMediaFile.image' => 'O ficheiro tem de ser uma imagem.',
                'newMediaFile.max' => 'A imagem não pode exceder 4 MB.',
                'newMediaUrl.required' => 'Indique o endereço da imagem ou do vídeo.',
            ]
        );

        $url = $isUpload
            ? $this->newMediaFile->store('locations/gallery', 'public')
            : trim($this->newMediaUrl);

        // Validação por tipo (mesma regra da Agent API)
        if (! $isUpload) {
            $isHttp = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
            if ($this->newMediaType === 'video' && (! $isHttp || ! filter_var($url, FILTER_VALIDATE_URL))) {
                $this->addError('newMediaUrl', 'Vídeo: use um link http(s) — YouTube, Vimeo ou ficheiro MP4/WebM.');
                return;
            }
            if ($this->newMediaType === 'image' && ! $isHttp
                && preg_match('#^[\w\-./]+\.(jpg|jpeg|png|webp|gif|avif)$#i', $url) !== 1) {
                $this->addError('newMediaUrl', 'Imagem: use um link http(s) ou envie um ficheiro.');
                return;
            }
        }

        \App\Models\LocationMedia::create([
            'location_id' => $this->galleryLocationId,
            'type' => $this->newMediaType,
            'url' => $url,
            'title' => $this->newMediaTitle ?: null,
            'position' => (int) (\App\Models\LocationMedia::where('location_id', $this->galleryLocationId)->max('position') ?? -1) + 1,
        ]);

        $this->reset('newMediaUrl', 'newMediaTitle', 'newMediaFile');
        session()->flash('message', $this->newMediaType === 'video' ? 'Vídeo adicionado à galeria!' : 'Imagem adicionada à galeria!');
    }

    public function removeMedia(int $mediaId): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        $media = \App\Models\LocationMedia::where('location_id', $this->galleryLocationId)->find($mediaId);
        if ($media) {
            $media->delete();
            session()->flash('message', 'Item removido da galeria.');
        }
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
