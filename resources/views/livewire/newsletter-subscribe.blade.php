<div>
    {{-- The best athlete wants his opponent at his best. --}}
    
    @if($showSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <i class="fas fa-check-circle mr-2"></i> Obrigado por se inscrever! Você receberá nossas novidades em breve.
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-2">
        <input 
            type="email" 
            wire:model.defer="email" 
            placeholder="Seu email" 
            class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
        <button 
            type="submit" 
            wire:loading.attr="disabled"
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
        >
            <span wire:loading.remove wire:target="subscribe">Inscrever</span>
            <span wire:loading wire:target="subscribe"><i class="fas fa-spinner fa-spin"></i></span>
        </button>
    </form>
    
    @error('email')
        <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
    @enderror
</div>
