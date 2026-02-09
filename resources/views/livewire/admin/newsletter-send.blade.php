<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Enviar Newsletter</h1>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <p class="text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>{{ $subscribersCount }}</strong> assinantes ativos receberão este email.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Assunto *</label>
            <input wire:model="subject" type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Ofertas exclusivas de hotéis!">
            @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem *</label>
            <textarea wire:model="message" rows="10" class="w-full px-4 py-2 border rounded-lg" placeholder="Escreva sua mensagem aqui..."></textarea>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-3">
            <button wire:click="togglePreview" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="fas fa-eye mr-2"></i> {{ $preview ? 'Esconder' : 'Visualizar' }} Prévia
            </button>
            <button wire:click="send" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg" onclick="return confirm('Enviar email para {{ $subscribersCount }} assinantes?')">
                <i class="fas fa-paper-plane mr-2"></i> Enviar Newsletter
            </button>
        </div>

        @if($preview)
            <div class="mt-6 border-t pt-6">
                <h3 class="font-bold mb-2">Prévia do Email:</h3>
                <div class="bg-gray-50 p-4 rounded border">
                    <p class="font-bold mb-2">Assunto: {{ $subject ?: '(Sem assunto)' }}</p>
                    <div class="whitespace-pre-wrap">{{ $message ?: '(Sem mensagem)' }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
