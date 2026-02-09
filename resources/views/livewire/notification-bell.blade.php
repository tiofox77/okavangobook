<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-gray-100 rounded-full transition">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
        
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold text-gray-900 dark:text-white">Notificações</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800">
                    Marcar todas como lidas
                </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div wire:click="markAsRead({{ $notification->id }})" 
                     class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($notification->icon)
                                <i class="{{ $notification->icon }} text-blue-600 text-lg"></i>
                            @else
                                <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->is_read)
                            <div class="flex-shrink-0 ml-2">
                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-bell-slash text-4xl mb-2"></i>
                    <p class="text-sm">Nenhuma notificação</p>
                </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
            <div class="p-3 text-center border-t border-gray-200 dark:border-gray-700">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                    Ver todas as notificações
                </a>
            </div>
        @endif
    </div>
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
