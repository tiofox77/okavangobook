<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Amenity;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class RoomManagement extends Component
{
    use WithPagination;
    use \Livewire\WithFileUploads;
    
    /**
     * Tema de paginação a utilizar
     */
    protected $paginationTheme = 'tailwind';
    
    /**
     * ID do hotel selecionado para filtrar quartos
     */
    public ?int $hotel_id = null;
    
    /**
     * Termo de pesquisa para filtrar quartos
     */
    public string $search = '';
    
    /**
     * Tipo de quarto selecionado para filtro
     */
    public ?string $roomType = null;
    
    /**
     * Filtro de disponibilidade
     */
    public ?bool $availableOnly = false;
    
    /**
     * ID do tipo de quarto (quando estiver editando)
     */
    public ?int $room_id = null;
    
    /**
     * Propriedades do formulário para criação/edição de tipos de quartos
     */
    public ?int $form_hotel_id = null;
    public string $name = '';
    public ?string $description = '';
    public int $capacity = 2;
    public int $beds = 1;
    public ?string $bed_type = '';
    public ?int $size = null;
    public float $base_price = 0.0;
    public int $rooms_count = 0;
    public bool $is_available = true;
    public bool $is_featured = false;
    public ?array $amenities = [];
    
    /**
     * Propriedades para gerenciamento de imagens
     */
    public $thumbnail = null;
    public $galleryImages = [];
    public ?string $existingThumbnail = null;
    public ?array $existingGalleryImages = [];
    
    /**
     * Controle do modal
     */
    public bool $showModal = false;
    public bool $showViewModal = false;
    public ?RoomType $viewingRoom = null;
    
    /**
     * Propriedades protegidas para armazenar dados
     */
    protected Collection $hotels;
    protected array $roomTypes = [
        'standard' => 'Standard',
        'deluxe' => 'Deluxe',
        'suite' => 'Suite',
        'family' => 'Família',
        'presidential' => 'Presidencial',
    ];
    
    /**
     * Reset de paginação quando algum filtro é alterado
     */
    protected $queryString = [
        'search' => ['except' => ''],
        'hotel_id' => ['except' => null],
        'roomType' => ['except' => null],
        'availableOnly' => ['except' => false],
    ];
    
    /**
     * Métodos observadores para atualização de propriedades e salvamento na sessão
     */
    public function updatedHotelId($value): void
    {
        $this->resetPage();
        session(['room_filter_hotel_id' => $value]);
    }
    
    public function updatedRoomType($value): void
    {
        $this->resetPage();
        session(['room_filter_type' => $value]);
    }
    
    public function updatedAvailableOnly($value): void
    {
        $this->resetPage();
        session(['room_filter_available' => $value]);
    }
    
    public function updatedSearch($value): void
    {
        $this->resetPage();
        session(['room_filter_search' => $value]);
    }
    
    /**
     * Regras de validação para o formulário
     */
    protected function rules(): array
    {
        return [
            'form_hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'capacity' => 'required|integer|min:1|max:20',
            'beds' => 'required|integer|min:1|max:10',
            'bed_type' => 'nullable|string|max:50',
            'size' => 'nullable|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_count' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'amenities' => 'nullable|array',
            'thumbnail' => 'nullable|image|max:2048', // Máximo 2MB
            'galleryImages.*' => 'nullable|image|max:2048',
        ];
    }
    
    /**
     * Mensagens personalizadas para erros de validação
     */
    protected function messages(): array
    {
        return [
            'form_hotel_id.required' => 'O hotel é obrigatório.',
            'form_hotel_id.exists' => 'O hotel selecionado não existe.',
            'name.required' => 'O nome do tipo de quarto é obrigatório.',
            'name.max' => 'O nome do tipo de quarto não pode ter mais de 100 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'capacity.required' => 'A capacidade do quarto é obrigatória.',
            'capacity.min' => 'A capacidade mínima é de 1 pessoa.',
            'capacity.max' => 'A capacidade máxima é de 20 pessoas.',
            'beds.required' => 'O número de camas é obrigatório.',
            'beds.min' => 'O mínimo de camas é 1.',
            'beds.max' => 'O máximo de camas é 10.',
            'bed_type.max' => 'O tipo de cama não pode ter mais de 50 caracteres.',
            'size.min' => 'O tamanho do quarto deve ser maior que zero.',
            'base_price.required' => 'O preço base é obrigatório.',
            'base_price.numeric' => 'O preço deve ser um valor numérico.',
            'thumbnail.image' => 'O arquivo deve ser uma imagem válida.',
            'thumbnail.max' => 'A imagem não deve exceder 2MB.',
            'galleryImages.*.image' => 'Todas as imagens devem estar em formato válido.',
            'galleryImages.*.max' => 'Cada imagem não deve exceder 2MB.',
            'base_price.min' => 'O preço base deve ser maior ou igual a zero.',
            'rooms_count.required' => 'A quantidade de quartos é obrigatória.',
            'rooms_count.min' => 'A quantidade de quartos não pode ser negativa.',
        ];
    }
    
    /**
     * Inicializa o componente
     */
    public function mount(): void
    {
        // Verificar se o utilizador tem permissão
        if (!auth()->check()) {
            abort(401, 'Não autenticado.');
        }
        
        $user = auth()->user();
        if (!$user->hasRole('Admin') && !$user->managedHotels()->exists()) {
            abort(403, 'Não tem permissão para aceder a esta página.');
        }
        
        // Carregar hotéis para o select
        $this->loadHotels();
        
        // Restaurar filtros da sessão
        $this->hotel_id = session('room_filter_hotel_id', $this->hotel_id);
        $this->roomType = session('room_filter_type', $this->roomType);
        $this->availableOnly = session('room_filter_available', $this->availableOnly);
        $this->search = session('room_filter_search', $this->search);
    }
    
    /**
     * Carrega a lista de hotéis para o select
     */
    private function loadHotels(): void
    {
        $user = auth()->user();
        $query = Hotel::select('id', 'name');
        
        // Se não for Admin, mostrar apenas hotéis do usuário
        if (!$user->hasRole('Admin')) {
            $query->where('user_id', $user->id);
        }
        
        $this->hotels = $query->orderBy('name')->get();
    }
    
    /**
     * Carrega as amenidades do tipo quarto para o formulário
     */
    protected function loadRoomAmenities(): array
    {
        return Amenity::where('type', Amenity::TYPE_ROOM)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->keyBy('id')
            ->map(function ($amenity) {
                return [
                    'id' => $amenity->id,
                    'name' => $amenity->name,
                    'icon' => $amenity->icon,
                    'description' => $amenity->description
                ];
            })
            ->toArray();
    }
    
    /**
     * Abre o modal para criar um novo tipo de quarto
     */
    public function openModal(?int $hotelId = null): void
    {
        $this->reset(['room_id', 'name', 'description', 'capacity', 'beds', 'bed_type',
                      'size', 'base_price', 'rooms_count', 'is_available', 'is_featured', 'amenities']);
        
        if ($hotelId) {
            $this->form_hotel_id = $hotelId;
        } elseif ($this->hotel_id) {
            $this->form_hotel_id = $this->hotel_id;
        }
        
        // Valores padrão
        $this->capacity = 2;
        $this->beds = 1;
        $this->is_available = true;
        $this->rooms_count = 0;
        
        $this->showModal = true;
    }
    
    /**
     * Fecha o modal
     */
    public function closeModal(): void
    {
        $this->reset([
            'room_id', 'name', 'description', 'capacity', 'beds', 'bed_type',
            'size', 'base_price', 'rooms_count', 'is_available', 'is_featured', 'amenities',
            'thumbnail', 'galleryImages', 'existingThumbnail', 'existingGalleryImages'
        ]);
        $this->showModal = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    /**
     * Visualiza um tipo de quarto (somente leitura)
     */
    public function view(int $roomId): void
    {
        $this->viewingRoom = RoomType::with('hotel')->findOrFail($roomId);
        $this->showViewModal = true;
    }
    
    /**
     * Fecha o modal de visualização
     */
    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->viewingRoom = null;
    }
    
    /**
     * Edita um tipo de quarto existente
     */
    public function edit(int $roomId): void
    {
        $this->reset(['name', 'description', 'capacity', 'beds', 'bed_type',
                     'size', 'base_price', 'rooms_count', 'is_available', 'is_featured', 'amenities']);
        $this->resetErrorBag();
        
        $room = RoomType::findOrFail($roomId);
        $this->room_id = $room->id;
        $this->form_hotel_id = $room->hotel_id;
        $this->name = $room->name;
        $this->description = $room->description ?? '';
        $this->capacity = $room->capacity;
        $this->beds = $room->beds ?? 1;
        $this->bed_type = $room->bed_type ?? '';
        $this->size = $room->size;
        $this->base_price = (float) $room->base_price;
        $this->rooms_count = $room->rooms_count;
        $this->is_available = $room->is_available;
        $this->is_featured = $room->is_featured;
        
        // Tratamento especial para amenities que pode vir como string ou array
        if (is_string($room->amenities) && !empty($room->amenities)) {
            $this->amenities = json_decode($room->amenities, true) ?? [];
        } else {
            $this->amenities = $room->amenities ?? [];
        }
        
        // Carregar imagens existentes
        $this->thumbnail = null; // Resetar quaisquer uploads em andamento
        $this->galleryImages = [];
        
        // Obter imagens existentes
        if (!empty($room->images) && is_array($room->images)) {
            // Se tiver uma imagem principal específica, usar ela
            if (isset($room->images['thumbnail'])) {
                $this->existingThumbnail = $room->images['thumbnail'];
            }
            
            // Obter imagens da galeria
            if (isset($room->images['gallery']) && is_array($room->images['gallery'])) {
                $this->existingGalleryImages = $room->images['gallery'];
            }
        } elseif (is_string($room->images) && !empty($room->images)) {
            // Se for string, tentar decodificar como JSON
            $images = json_decode($room->images, true);
            if (is_array($images)) {
                if (isset($images['thumbnail'])) {
                    $this->existingThumbnail = $images['thumbnail'];
                }
                if (isset($images['gallery']) && is_array($images['gallery'])) {
                    $this->existingGalleryImages = $images['gallery'];
                }
            }
        }
        
        $this->showModal = true;
    }
    
    /**
     * Salva um tipo de quarto (novo ou existente)
     */
    /**
     * Remove a imagem de miniatura que está sendo carregada
     */
    public function removeThumbnail(): void
    {
        $this->thumbnail = null;
    }
    
    /**
     * Remove a imagem de miniatura existente de um tipo de quarto
     */
    public function removeExistingThumbnail(): void
    {
        $this->existingThumbnail = null;
    }
    
    /**
     * Remove uma imagem da galeria temporária
     */
    public function removeGalleryImage(int $index): void
    {
        // Cria um novo array sem a imagem removida
        $newGalleryImages = [];
        foreach ($this->galleryImages as $i => $image) {
            if ($i !== $index) {
                $newGalleryImages[] = $image;
            }
        }
        $this->galleryImages = $newGalleryImages;
    }
    
    /**
     * Remove uma imagem existente da galeria
     */
    public function removeExistingGalleryImage(int $index): void
    {
        // Cria um novo array sem a imagem removida
        if (isset($this->existingGalleryImages[$index])) {
            unset($this->existingGalleryImages[$index]);
            // Reindexar o array
            $this->existingGalleryImages = array_values($this->existingGalleryImages);
        }
    }
    
    public function save(): void
    {
        $this->validate();
        
        // Tratamento especial para amenities que pode vir como string ou array
        $processedAmenities = null;
        if (!empty($this->amenities)) {
            if (is_string($this->amenities)) {
                $processedAmenities = json_decode($this->amenities, true) ?: [];
            } else {
                $processedAmenities = $this->amenities;
            }
            // Remove valores vazios ou nulos
            $processedAmenities = array_filter($processedAmenities);
        }
        
        // Preparar o array de imagens
        $images = [];
        
        // Processar a imagem de miniatura (thumbnail)
        if ($this->thumbnail) {
            // Upload da nova imagem
            $thumbnailPath = $this->thumbnail->store('room-types', 'public');
            $images['thumbnail'] = '/storage/' . $thumbnailPath;
        } elseif ($this->existingThumbnail) {
            // Manter a imagem existente
            $images['thumbnail'] = $this->existingThumbnail;
        }
        
        // Processar imagens da galeria
        $galleryPaths = [];
        
        // Manter imagens existentes que não foram removidas
        if (!empty($this->existingGalleryImages)) {
            $galleryPaths = array_merge($galleryPaths, $this->existingGalleryImages);
        }
        
        // Processar novas imagens carregadas
        if (!empty($this->galleryImages)) {
            foreach ($this->galleryImages as $image) {
                $path = $image->store('room-types/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
        }
        
        if (!empty($galleryPaths)) {
            $images['gallery'] = $galleryPaths;
        }
        
        $roomData = [
            'hotel_id' => $this->form_hotel_id,
            'name' => $this->name,
            'description' => $this->description ?: null,
            'capacity' => $this->capacity,
            'beds' => $this->beds,
            'bed_type' => $this->bed_type ?: null,
            'size' => $this->size,
            'base_price' => $this->base_price,
            'rooms_count' => $this->rooms_count,
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
            'amenities' => !empty($processedAmenities) ? $processedAmenities : null,
            'images' => !empty($images) ? $images : null,
        ];
        
        if ($this->room_id) {
            $room = RoomType::findOrFail($this->room_id);
            $room->update($roomData);
            session()->flash('success', 'Tipo de quarto atualizado com sucesso!');
        } else {
            RoomType::create($roomData);
            session()->flash('success', 'Tipo de quarto criado com sucesso!');
        }
        
        $this->closeModal();
    }
    
    /**
     * Confirma e exclui um tipo de quarto
     */
    public function delete(int $roomId): void
    {
        $room = RoomType::findOrFail($roomId);
        $room->delete();
        
        session()->flash('success', 'Tipo de quarto excluído com sucesso!');
    }
    
    /**
     * Retorna um ícone FontAwesome adequado para a facilidade
     */
    public function getFeatureIcon(?string $feature): ?string
    {
        return 'fas fa-check-circle';
    }
    
    /**
     * Renderiza o componente
     */
    public function render(): View
    {
        $this->loadHotels();
        
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');
        
        $rooms = RoomType::with(['hotel:id,name'])
            // Se não for Admin, mostrar apenas quartos dos hotéis do usuário
            ->when(!$isAdmin, function (Builder $query) use ($user) {
                $query->whereHas('hotel', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->when($this->hotel_id, function (Builder $query, $hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->when($this->search, function (Builder $query, $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('hotel', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->roomType, function (Builder $query, $type) {
                $query->where('type', $type);
            })
            ->when($this->availableOnly, function (Builder $query) {
                $query->where('is_available', true);
            })
            ->orderBy('hotel_id')
            ->orderBy('name')
            ->paginate(10);
        
        return view('livewire.admin.room-management', [
            'rooms' => $rooms,
            'hotels' => $this->hotels,
            'roomTypes' => $this->roomTypes,
            'amenitiesList' => $this->loadRoomAmenities(),
        ])->layout('layouts.admin');
    }
}
