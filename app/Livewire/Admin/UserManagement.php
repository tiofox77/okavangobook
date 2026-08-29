<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;
    
    // Propriedades para formulário
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';
    public array $selectedRoles = [];
    
    // Filtros e pesquisa
    public string $search = '';
    public ?string $roleFilter = null;
    
    // Estado do modal
    public bool $showModal = false;
    public bool $showViewModal = false;
    public array $selectedUserDetails = [];
    
    // Regras de validação
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'selectedRoles' => 'nullable|array'
        ];
        
        // Adicionar regras para a password apenas na criação ou se estiver a ser atualizada
        if (!$this->userId || $this->password) {
            $rules['password'] = 'required|min:8|confirmed';
            $rules['passwordConfirmation'] = 'required';
        }
        
        return $rules;
    }
    
    public function mount()
    {
        // Verificar se o utilizador tem permissão para aceder à gestão de utilizadores
        if (!auth()->check() || !auth()->user()->hasRole('Admin')) {
            return redirect()->route('login');
        }
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function render()
    {
        // Obter todos os utilizadores com filtragem e pesquisa
        $usersQuery = User::query()
            ->when($this->search, function ($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                return $query->role($this->roleFilter);
            });
            
        $users = $usersQuery->paginate(10);
        $roles = Role::all(); // Para dropdown de roles
        
        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
    
    public function openModal(?int $userId = null)
    {
        $this->resetValidation();
        $this->reset('name', 'email', 'password', 'passwordConfirmation', 'selectedRoles');
        
        // Se for edição, carregar os dados do utilizador
        if ($userId) {
            $this->userId = $userId;
            $user = User::findOrFail($userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->selectedRoles = $user->roles->pluck('id')->toArray();
        }
        
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function viewUser(int $userId): void
    {
        $user = User::query()
            ->with([
                'roles:id,name',
                'managedHotels' => fn ($query) => $query
                    ->with('location:id,name')
                    ->withCount(['roomTypes', 'reservations'])
                    ->orderBy('name'),
                'activeSubscription.plan',
            ])
            ->withCount(['managedHotels', 'reservations', 'reviews', 'favorites'])
            ->findOrFail($userId);

        $subscription = $user->activeSubscription;

        $this->selectedUserDetails = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
            'roles' => $user->roles->pluck('name')->values()->all(),
            'email_verified' => $user->email_verified_at !== null,
            'email_verified_at' => $user->email_verified_at?->format('d/m/Y H:i'),
            'created_at' => $user->created_at?->format('d/m/Y H:i'),
            'updated_at' => $user->updated_at?->format('d/m/Y H:i'),
            'counts' => [
                'hotels' => $user->managed_hotels_count,
                'reservations' => $user->reservations_count,
                'reviews' => $user->reviews_count,
                'favorites' => $user->favorites_count,
            ],
            'subscription' => $subscription ? [
                'plan' => $subscription->plan?->name ?? 'Plano removido',
                'status' => $subscription->status,
                'cycle' => $subscription->billing_cycle,
                'ends_at' => $subscription->ends_at?->format('d/m/Y'),
            ] : null,
            'hotels' => $user->managedHotels->map(fn ($hotel) => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'type' => ucfirst((string) ($hotel->property_type ?: 'hotel')),
                'location' => $hotel->location?->name ?? 'Localização não definida',
                'active' => (bool) $hotel->is_active,
                'rooms' => $hotel->room_types_count,
                'reservations' => $hotel->reservations_count,
            ])->values()->all(),
        ];

        $this->showViewModal = true;
    }

    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->selectedUserDetails = [];
    }
    
    public function save()
    {
        $validatedData = $this->validate();
        
        if ($this->userId) {
            // Atualizar utilizador existente
            $user = User::findOrFail($this->userId);
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
            ];
            
            // Atualizar password apenas se foi fornecida
            if ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }
            
            $user->update($userData);
            
            // Sincronizar roles
            $user->syncRoles($this->selectedRoles);
            
            session()->flash('message', 'Utilizador atualizado com sucesso!');
        } else {
            // Criar novo utilizador
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            
            // Atribuir roles
            $user->syncRoles($this->selectedRoles);
            
            session()->flash('message', 'Utilizador criado com sucesso!');
        }
        
        $this->closeModal();
    }
    
    public function delete(int $userId)
    {
        // Não permitir que o utilizador elimine a si mesmo
        if ($userId === auth()->id()) {
            session()->flash('error', 'Não é possível eliminar o seu próprio utilizador.');
            return;
        }
        
        $user = User::findOrFail($userId);
        $user->delete();
        
        session()->flash('message', 'Utilizador eliminado com sucesso!');
    }
}
