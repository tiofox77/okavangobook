<div>
    <!-- Layout principal do componente de gestão de utilizadores -->
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('livewire.admin.partials.sidebar')

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestão de Utilizadores</h1>
            
            <!-- Mensagens de feedback -->
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <!-- Barra de ações e pesquisa -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                <div>
                    <button wire:click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar Utilizador
                        </span>
                    </button>
                </div>
                
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <!-- Campo de pesquisa -->
                    <div class="relative">
                        <input type="text" wire:model.debounce.300ms="search" 
                            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 rounded-md"
                            placeholder="Pesquisar utilizadores...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Filtro por role -->
                    <select wire:model="roleFilter" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md">
                        <option value="">Todos os Papéis</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Tabela de utilizadores -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Papéis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Registo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 {{ auth()->id() === $user->id ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if(auth()->id() === $user->id)
                                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Você</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 py-0.5 text-xs {{ $role->name === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="openModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900 mr-2">
                                        Editar
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                        <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Tem certeza que deseja eliminar este utilizador?')">
                                            Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum utilizador encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
            
            <!-- Modal de criação/edição -->
            @if ($showModal)
                <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Overlay de fundo -->
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <!-- Centralizador de conteúdo -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <!-- Modal -->
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form wire:submit.prevent="save">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div>
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                            {{ $userId ? 'Editar Utilizador' : 'Novo Utilizador' }}
                                        </h3>
                                        
                                        <!-- Nome -->
                                        <div class="mb-4">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                            <input type="text" wire:model="name" id="name" 
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="mb-4">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" wire:model="email" id="email" 
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <!-- Password -->
                                        <div class="mb-4">
                                            <label for="password" class="block text-sm font-medium text-gray-700">
                                                Password {{ $userId ? '(deixe em branco para manter a atual)' : '' }}
                                            </label>
                                            <input type="password" wire:model="password" id="password" 
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <!-- Confirmação da Password -->
                                        <div class="mb-4">
                                            <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirmar Password</label>
                                            <input type="password" wire:model="passwordConfirmation" id="passwordConfirmation" 
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('passwordConfirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <!-- Roles (checkboxes) -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Papéis</label>
                                            <div class="space-y-2">
                                                @foreach($roles as $role)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" 
                                                            id="role-{{ $role->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                        <label for="role-{{ $role->id }}" class="ml-2 text-sm text-gray-700">{{ $role->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('selectedRoles') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        {{ $userId ? 'Guardar Alterações' : 'Adicionar Utilizador' }}
                                    </button>
                                    <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
