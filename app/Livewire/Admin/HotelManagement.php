<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use App\Models\Amenity;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class HotelManagement extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    // Propriedades para formulário de criação/edição
    public ?int $hotelId = null;
    public string $name = '';
    public string $property_type = 'hotel';
    public string $description = '';
    public ?int $locationId = null;
    public ?int $userId = null;
    public ?string $address = '';
    public ?float $price = null;
    public ?int $rating = null;
    public ?string $phone = ''; // Telefone de contato
    public ?string $phoneNumber = ''; // Propriedade antiga (manter para compatibilidade)
    public ?string $email = '';
    public ?string $website = '';
    public ?string $mapLink = '';
    public ?string $check_in_time = '14:00'; // Horário padrão de check-in
    public ?string $check_out_time = '12:00'; // Horário padrão de check-out
    public ?float $latitude = null; // Coordenadas geográficas
    public ?float $longitude = null; // Coordenadas geográficas
    public array $amenities = [];
    public ?string $thumbnail = null;
    public $thumbnailUpload = null; // Para upload de novo thumbnail
    public array $images = []; // Para armazenar URLs de imagens existentes
    public array $imagesUpload = []; // Para novos uploads de imagens
    public ?string $slug = '';
    public bool $is_featured = false;
    public bool $is_active = true;
    
    // Propriedades para métodos de pagamento
    public bool $accept_transfer = true;
    public bool $accept_tpa_onsite = true;
    public ?string $transfer_instructions = null;
    public ?string $bank_name = null;
    public ?string $account_number = null;
    public ?string $iban = null;
    
    // Propriedades para filtro e pesquisa
    public string $search = '';
    public ?string $filterLocation = null;
    public ?string $filterRating = null;
    public ?string $filterPropertyType = null; // Filtro por tipo de propriedade
    public ?string $filterHotel = null; // Filtro por hotel
    
    /**
     * Métodos observadores para atualização de propriedades e salvamento na sessão
     */
    public function updatedSearch($value): void
    {
        $this->resetPage();
        session(['hotel_filter_search' => $value]);
    }
    
    public function updatedFilterLocation($value): void
    {
        $this->resetPage();
        session(['hotel_filter_location' => $value]);
    }
    
    public function updatedFilterRating($value): void
    {
        $this->resetPage();
        session(['hotel_filter_rating' => $value]);
    }
    
    public function updatedFilterPropertyType($value): void
    {
        $this->resetPage();
        session(['hotel_filter_property_type' => $value]);
    }
    
    public function updatedFilterHotel($value): void
    {
        $this->resetPage();
        session(['hotel_filter_hotel' => $value]);
    }
    
    // Estado do modal
    public bool $showModal = false;
    public bool $showViewModal = false;
    public ?Hotel $viewingHotel = null;
    
    // Regras de validação
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'property_type' => 'required|in:hotel,resort,hospedaria',
            'description' => 'required|string',
            'locationId' => 'required|exists:locations,id',
            'userId' => 'nullable|exists:users,id',
            'address' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|integer|min:1|max:5',
            'phone' => 'nullable|string|max:20',
            'phoneNumber' => 'nullable|string|max:20', // Para compatibilidade
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mapLink' => 'nullable|url|max:255',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'amenities' => 'nullable|array',
            'thumbnail' => 'nullable|string',
            'thumbnailUpload' => 'nullable|image|max:2048',
            'imagesUpload.*' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'slug' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
    
    private function resetForm()
    {
        $this->reset([
            'hotelId', 'name', 'property_type', 'description', 'locationId', 'userId', 
            'address', 'price', 'rating', 'phone', 'email', 'website', 'mapLink',
            'check_in_time', 'check_out_time', 'latitude', 'longitude', 
            'amenities', 'thumbnail', 'thumbnailUpload', 'images', 'imagesUpload',
            'slug', 'is_featured', 'is_active',
            'accept_transfer', 'accept_tpa_onsite', 'transfer_instructions',
            'bank_name', 'account_number', 'iban'
        ]);
        
        // Reinicia array de comodidades
        $this->amenities = [];
        
        // Valores padrão
        $this->property_type = 'hotel';
        $this->accept_transfer = true;
        $this->accept_tpa_onsite = true;
    }
    
    public function mount()
    {
        // Verificar se o utilizador tem permissão para aceder à gestão de hotéis
        if (!auth()->check()) {
            abort(401, 'Não autenticado.');
        }
        
        // Verificar se é Admin ou gestor de hotel
        $user = auth()->user();
        if (!$user->hasRole('Admin') && !$user->managedHotels()->exists()) {
            abort(403, 'Não tem permissão para aceder a esta página.');
        }
        
        // Restaurar filtros da sessão
        $this->search = session('hotel_filter_search', $this->search);
        $this->filterLocation = session('hotel_filter_location', $this->filterLocation);
        $this->filterRating = session('hotel_filter_rating', $this->filterRating);
        $this->filterPropertyType = session('hotel_filter_property_type', $this->filterPropertyType);
        $this->filterHotel = session('hotel_filter_hotel', $this->filterHotel);
    }
    
    /**
     * Carrega as amenidades do tipo hotel para o formulário
     */
    protected function loadHotelAmenities(): array
    {
        return Amenity::where('type', Amenity::TYPE_HOTEL)
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

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');
        
        // Obter todos os hotéis com filtragem e pesquisa
        $hotelsQuery = Hotel::query()
            // Se não for Admin, mostrar apenas hotéis do usuário
            ->when(!$isAdmin, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterLocation, function ($query) {
                return $query->where('location_id', $this->filterLocation);
            })
            ->when($this->filterRating, function ($query) {
                return $query->where('rating', $this->filterRating);
            })
            ->when($this->filterPropertyType, function ($query) {
                return $query->where('property_type', $this->filterPropertyType);
            })
            ->when($this->filterHotel, function ($query) {
                return $query->where('id', $this->filterHotel);
            });
            
        // Para a tabela principal
        $hotels = $hotelsQuery->paginate(10);
        
        // Para os dropdowns
        $locations = Location::all(); // Para dropdown de localizações
        $managers = User::role('Admin')->get(); // Para dropdown de gestores
        $hotelList = Hotel::select(['id', 'name'])->orderBy('name')->get(); // Para dropdown de filtro por hotel
        
        return view('livewire.admin.hotel-management', [
            'hotels' => $hotels,
            'locations' => $locations,
            'managers' => $managers,
            'hotelAmenities' => $this->loadHotelAmenities(),
            'hotelList' => $hotelList,
        ])->layout('layouts.admin');
    }
    
    public function openModal(?int $hotelId = null)
    {
        $this->resetValidation();
        $this->resetForm();
        
        // Se for edição, carregar os dados do hotel
        if ($hotelId) {
            $this->hotelId = $hotelId;
            $hotel = Hotel::findOrFail($hotelId);
            
            // Verificar se o usuário tem permissão para editar este hotel
            $user = auth()->user();
            if (!$user->hasRole('Admin') && $hotel->user_id !== $user->id) {
                session()->flash('error', 'Você não tem permissão para editar este hotel.');
                return;
            }
            $this->name = $hotel->name;
            $this->property_type = $hotel->property_type ?? 'hotel';
            $this->description = $hotel->description;
            $this->locationId = $hotel->location_id;
            $this->userId = $hotel->user_id;
            $this->address = $hotel->address;
            $this->price = $hotel->price;
            $this->rating = $hotel->rating ? (int) $hotel->rating : null;
            $this->phone = $hotel->phone ?? $hotel->phone_number;
            $this->email = $hotel->email;
            $this->website = $hotel->website;
            $this->mapLink = $hotel->map_link;
            $this->check_in_time = $hotel->check_in_time;
            $this->check_out_time = $hotel->check_out_time;
            $this->latitude = $hotel->latitude ? (float) $hotel->latitude : null;
            $this->longitude = $hotel->longitude ? (float) $hotel->longitude : null;
            $this->amenities = is_array($hotel->amenities) ? $hotel->amenities : [];
            $this->thumbnail = $hotel->thumbnail;
            $this->images = is_array($hotel->images) ? $hotel->images : [];
            $this->slug = $hotel->slug;
            $this->is_featured = (bool) $hotel->is_featured;
            $this->is_active = (bool) $hotel->is_active;
            
            // Carregar métodos de pagamento
            $this->accept_transfer = (bool) $hotel->accept_transfer;
            $this->accept_tpa_onsite = (bool) $hotel->accept_tpa_onsite;
            $this->transfer_instructions = $hotel->transfer_instructions;
            $this->bank_name = $hotel->bank_name;
            $this->account_number = $hotel->account_number;
            $this->iban = $hotel->iban;
        } else {
            // Valores padrão para novo hotel
            $this->property_type = 'hotel';
            $this->is_active = true;
            $this->check_in_time = '14:00';
            $this->check_out_time = '12:00';
            $this->accept_transfer = true;
            $this->accept_tpa_onsite = true;
        }
        
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    /**
     * Visualiza um hotel (somente leitura)
     */
    public function view(int $hotelId): void
    {
        $hotel = Hotel::with(['location', 'user'])->findOrFail($hotelId);
        
        // Verificar se o usuário tem permissão para visualizar este hotel
        $user = auth()->user();
        if (!$user->hasRole('Admin') && $hotel->user_id !== $user->id) {
            session()->flash('error', 'Você não tem permissão para visualizar este hotel.');
            return;
        }
        
        $this->viewingHotel = $hotel;
        $this->showViewModal = true;
    }
    
    /**
     * Fecha o modal de visualização
     */
    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->viewingHotel = null;
    }
    
    public function save()
    {
        $validatedData = $this->validate();
        
        // Criar o slug antes para usar nos caminhos das imagens
        if (empty($this->slug)) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
            
            // Verificar se o slug já existe e adicionar um sufixo numérico se necessário
            $originalSlug = $this->slug;
            $count = 1;
            
            while (Hotel::where('slug', $this->slug)
                     ->when($this->hotelId, function($query) {
                         return $query->where('id', '!=', $this->hotelId);
                     })
                     ->exists()) {
                $this->slug = $originalSlug . '-' . $count++;
            }
        }
        
        // Diretório base para os uploads deste hotel
        $hotelSlug = $this->slug;
        $hotelUploadDir = 'hotels/' . $hotelSlug;
        
        // Processar upload de thumbnail se existir
        if ($this->thumbnailUpload) {
            // Criar pasta para o hotel específico
            $thumbnailPath = $this->thumbnailUpload->store($hotelUploadDir . '/thumbnail', 'public');
            $this->thumbnail = asset('storage/' . $thumbnailPath);
        }
        
        // Processar múltiplos uploads de imagens se existirem
        $newImages = [];
        if (!empty($this->imagesUpload)) {
            foreach ($this->imagesUpload as $image) {
                $imagePath = $image->store($hotelUploadDir . '/gallery', 'public');
                $newImages[] = asset('storage/' . $imagePath);
            }
        }
        
        // Combinar imagens existentes com novas imagens
        $imagesArray = array_merge($this->images, $newImages);
        
        // Preparar dados para salvar
        $hotelData = [
            'name' => $this->name,
            'description' => $this->description,
            'location_id' => $this->locationId,
            'user_id' => $this->userId,
            'address' => $this->address,
            'price' => $this->price,
            'rating' => $this->rating,
            'phone' => $this->phone,
            'phone_number' => $this->phone, // Para compatibilidade
            'email' => $this->email,
            'website' => $this->website,
            'map_link' => $this->mapLink,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'amenities' => array_map('intval', $this->amenities),
            'thumbnail' => $this->thumbnail,
            'images' => $imagesArray,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'accept_transfer' => $this->accept_transfer,
            'accept_tpa_onsite' => $this->accept_tpa_onsite,
            'transfer_instructions' => $this->transfer_instructions,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'iban' => $this->iban,
            'slug' => $this->slug
        ];
        
        // Garantir que as imagens são armazenadas como JSON
        if (isset($hotelData['images']) && is_array($hotelData['images'])) {
            $hotelData['images'] = json_encode($hotelData['images']);
        }
        
        if ($this->hotelId) {
            // Atualizar hotel existente
            $hotel = Hotel::findOrFail($this->hotelId);
            $hotel->update($hotelData);
            
            session()->flash('message', 'Hotel atualizado com sucesso!');
        } else {
            // Criar novo hotel
            Hotel::create($hotelData);
            
            session()->flash('message', 'Hotel criado com sucesso!');
        }
        
        $this->closeModal();
    }
    
    public function delete(int $hotelId)
    {
        // Implementação de exclusão com confirmação no front-end
        $hotel = Hotel::findOrFail($hotelId);
        $hotel->delete();
        
        session()->flash('message', 'Hotel eliminado com sucesso!');
    }
    
    /**
     * Remove uma imagem do array de imagens pelo índice.
     *
     * @param int $index Índice da imagem a ser removida
     * @return void
     */
    public function removeImage(int $index)
    {
        if (isset($this->images[$index])) {
            $imageUrl = $this->images[$index];
            
            // Remove a imagem do array
            unset($this->images[$index]);
            // Reordena os índices do array
            $this->images = array_values($this->images);
            
            // Se o hotel já existir, salva imediatamente a mudança
            if ($this->hotelId) {
                $hotel = Hotel::findOrFail($this->hotelId);
                $hotel->update([
                    'images' => json_encode($this->images)
                ]);
                
                // Tenta remover o arquivo físico se estiver em armazenamento local
                $this->tryRemoveImageFile($imageUrl);
                
                session()->flash('message', 'Imagem removida com sucesso!');
            }
        }
    }
    
    /**
     * Tenta remover o arquivo físico da imagem do armazenamento, se for local.
     *
     * @param string $imageUrl URL da imagem a ser removida
     * @return bool Se a imagem foi removida com sucesso
     */
    private function tryRemoveImageFile(string $imageUrl): bool
    {
        // Verifica se é uma imagem no armazenamento local
        $storageUrl = url('storage/');
        if (str_starts_with($imageUrl, $storageUrl)) {
            $relativePath = str_replace($storageUrl . '/', '', $imageUrl);
            
            // Verifica se o arquivo existe no disco
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                // Remove o arquivo
                $removed = \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                
                // Verifica se a pasta está vazia para limpar
                $this->cleanEmptyDirectories($relativePath);
                
                return $removed;
            }
        }
        
        return false;
    }
    
    /**
     * Limpa diretórios vazios após remoção de imagens
     *
     * @param string $filePath Caminho relativo do arquivo removido
     * @return void
     */
    private function cleanEmptyDirectories(string $filePath): void
    {
        // Obtém o diretório do arquivo
        $directory = dirname($filePath);
        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        
        // Verifica se o diretório existe e está vazio
        if ($storage->exists($directory) && count($storage->files($directory)) === 0 && count($storage->directories($directory)) === 0) {
            // Remove o diretório vazio
            $storage->deleteDirectory($directory);
            
            // Verifica o diretório pai recursivamente
            $parentDir = dirname($directory);
            if ($parentDir !== '.' && $parentDir !== '/') {
                $this->cleanEmptyDirectories($parentDir);
            }
        }
    }
}
