<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestão de Newsletter</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.newsletter.send') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fas fa-paper-plane mr-2"></i> Enviar Email
            </a>
            <button wire:click="export" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="fas fa-download mr-2"></i> Exportar CSV
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Ativos</p>
                    <p class="text-2xl font-bold">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Inativos</p>
                    <p class="text-2xl font-bold">{{ $stats['inactive'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="flex gap-4">
            <input wire:model.live="search" type="text" placeholder="Buscar por email..." class="flex-1 px-4 py-2 border rounded-lg">
            <select wire:model.live="filterStatus" class="px-4 py-2 border rounded-lg">
                <option value="all">Todos</option>
                <option value="active">Ativos</option>
                <option value="inactive">Inativos</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Data Inscrição</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td class="px-4 py-3">{{ $subscriber->email }}</td>
                        <td class="px-4 py-3">{{ $subscriber->subscribed_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $subscriber->id }})" class="px-2 py-1 rounded text-xs {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subscriber->is_active ? 'Ativo' : 'Inativo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="delete({{ $subscriber->id }})" class="text-red-600 hover:text-red-800" onclick="return confirm('Remover este assinante?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum assinante encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscribers->links() }}
    </div>
</div>
