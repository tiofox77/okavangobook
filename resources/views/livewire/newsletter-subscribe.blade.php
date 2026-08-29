<div class="{{ $variant === 'hero' ? 'newsletter-form-component' : '' }}">
    @if($showSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
             class="newsletter-success mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="status">
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> {{ __('Obrigado por se inscrever! Você receberá nossas novidades em breve.') }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="{{ $variant === 'hero' ? 'newsletter-signup-form' : 'flex flex-col sm:flex-row gap-2' }}">
        <label for="newsletter-email-{{ $this->getId() }}" class="sr-only">{{ __('Seu email') }}</label>
        <input 
            id="newsletter-email-{{ $this->getId() }}"
            type="email" 
            wire:model.defer="email" 
            placeholder="{{ $variant === 'hero' ? __('Digite seu melhor e-mail') : __('Seu email') }}"
            autocomplete="email"
            required
            class="{{ $variant === 'hero' ? 'newsletter-email-input' : 'w-full min-w-0 flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent' }}"
        >
        <button 
            type="submit" 
            wire:loading.attr="disabled"
            class="{{ $variant === 'hero' ? 'newsletter-submit' : 'shrink-0 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50' }}"
        >
            <span wire:loading.remove wire:target="subscribe">
                {{ $variant === 'hero' ? __('Quero receber novidades') : __('Inscrever') }}
                @if($variant === 'hero')<i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>@endif
            </span>
            <span wire:loading wire:target="subscribe"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> <span class="sr-only">{{ __('A processar') }}</span></span>
        </button>
    </form>
    
    @error('email')
        <p class="{{ $variant === 'hero' ? 'newsletter-error' : 'text-red-500 text-sm mt-2' }}" role="alert"><i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i> {{ $message }}</p>
    @enderror
</div>
