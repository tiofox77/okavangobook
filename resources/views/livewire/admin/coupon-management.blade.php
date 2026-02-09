<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestão de Cupons</h1>
        <div class="flex gap-2">
            <button wire:click="export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i> Exportar
            </button>
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i> Novo Cupom
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <input wire:model.live="search" type="text" placeholder="Buscar cupom..." class="w-full px-4 py-2 border rounded-lg">
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Código</th>
                    <th class="px-4 py-3 text-left">Descrição</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-left">Valor</th>
                    <th class="px-4 py-3 text-left">Usos</th>
                    <th class="px-4 py-3 text-left">Expira em</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-3 font-mono font-bold">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">{{ Str::limit($coupon->description, 30) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $coupon->type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $coupon->type == 'percentage' ? 'Percentual' : 'Fixo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $coupon->type == 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 2) . ' Kz' }}</td>
                        <td class="px-4 py-3">{{ $coupon->uses_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                        <td class="px-4 py-3">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Sem limite' }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $coupon->id }})" class="px-2 py-1 rounded text-xs {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $coupon->is_active ? 'Ativo' : 'Inativo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="edit({{ $coupon->id }})" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $coupon->id }})" class="text-red-600 hover:text-red-800" onclick="return confirm('Remover este cupom?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">Nenhum cupom encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
                <h2 class="text-xl font-bold mb-4">{{ $isEditing ? 'Editar Cupom' : 'Novo Cupom' }}</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Código *</label>
                        <input wire:model="code" type="text" class="w-full px-4 py-2 border rounded-lg">
                        @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Tipo *</label>
                        <select wire:model="type" class="w-full px-4 py-2 border rounded-lg">
                            <option value="percentage">Percentual</option>
                            <option value="fixed">Valor Fixo</option>
                        </select>
                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Valor *</label>
                        <input wire:model="value" type="number" step="0.01" class="w-full px-4 py-2 border rounded-lg">
                        @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Valor Mínimo</label>
                        <input wire:model="min_amount" type="number" step="0.01" class="w-full px-4 py-2 border rounded-lg">
                        @error('min_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Máximo de Usos</label>
                        <input wire:model="max_uses" type="number" class="w-full px-4 py-2 border rounded-lg">
                        @error('max_uses') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="flex items-center mt-8">
                            <input wire:model="is_active" type="checkbox" class="mr-2">
                            <span>Ativo</span>
                        </label>
                    </div>

                    <div>
                        <label class="block mb-2">Data Início</label>
                        <input wire:model="starts_at" type="date" class="w-full px-4 py-2 border rounded-lg">
                        @error('starts_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Data Expiração</label>
                        <input wire:model="expires_at" type="date" class="w-full px-4 py-2 border rounded-lg">
                        @error('expires_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Descrição</label>
                        <textarea wire:model="description" class="w-full px-4 py-2 border rounded-lg" rows="3"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Salvar</button>
                </div>
            </div>
        </div>
    @endif
</div>
