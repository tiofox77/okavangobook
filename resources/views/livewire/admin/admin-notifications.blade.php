<div x-data="{ showNotifications: false }" wire:poll.30s="poll" class="relative">
    <!-- Bell Button -->
    <button @click="showNotifications = !showNotifications" class="relative text-gray-600 dark:text-gray-300 hover:text-admin-primary p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform bg-red-500 rounded-full min-w-[18px] h-[18px] animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="showNotifications" 
         @click.away="showNotifications = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-2xl z-50 border border-gray-200 dark:border-gray-700 overflow-hidden"
         x-cloak>
        
        <!-- Header -->
        <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notificações
                @if($unreadCount > 0)
                    <span class="ml-2 bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">{{ $unreadCount }} nova{{ $unreadCount > 1 ? 's' : '' }}</span>
                @endif
            </h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-white/80 hover:text-white transition-colors duration-150 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Marcar tudo como lido
                </button>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <div class="relative group {{ !$notification['is_read'] ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                    <a href="{{ $notification['link'] ?? '#' }}" 
                       wire:click="markAsRead({{ $notification['id'] }})"
                       class="flex items-start px-4 py-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 {{ $notification['badge_color'] }} rounded-full p-1.5 shadow-sm">
                            <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {!! $notification['icon_svg'] !!}
                            </svg>
                        </div>
                        <!-- Content -->
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm {{ !$notification['is_read'] ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }} truncate">
                                {{ $notification['title'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">
                                {{ $notification['message'] }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $notification['time_ago'] }}
                            </p>
                        </div>
                        <!-- Unread dot -->
                        @if(!$notification['is_read'])
                            <div class="flex-shrink-0 ml-2 mt-1">
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                            </div>
                        @endif
                    </a>
                    <!-- Delete button -->
                    <button wire:click="deleteNotification({{ $notification['id'] }})" 
                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all duration-150 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20"
                            title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Sem notificações</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">As novas notificações aparecerão aqui</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if(count($notifications) > 0)
            <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <a href="{{ route('admin.notifications') }}" class="text-sm text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-150 flex items-center">
                    Ver todas as notificações
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <button wire:click="clearAll" wire:confirm="Tem certeza que deseja limpar todas as notificações?" class="text-xs text-gray-400 hover:text-red-500 transition-colors duration-150">
                    Limpar tudo
                </button>
            </div>
        @endif
    </div>
</div>
