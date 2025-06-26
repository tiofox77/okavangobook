<!-- Modal de confirmação de eliminação (Design 2025) -->
<div x-show="confirmingDeletion" x-cloak class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com blur de fundo (glassmorphism) -->
        <div x-show="confirmingDeletion" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 transition-opacity" 
             aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-red-900 backdrop-blur-sm opacity-80"></div>
        </div>

        <!-- Centralizador de conteúdo -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal com efeito de entrada -->
        <div x-show="confirmingDeletion" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl border border-white/50 dark:border-gray-700/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <!-- Cabeçalho do modal com gradiente -->
            <div class="bg-gradient-to-r from-red-500/10 to-orange-500/10 dark:from-red-800/30 dark:to-orange-900/30 px-6 py-4 flex items-center">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5C2.962 18.333 3.924 20 5.464 20z" />
                    </svg>
                </div>
                <h3 class="ml-4 text-xl font-semibold text-gray-900 dark:text-white tracking-tight" id="modal-title">
                    <span class="inline-block">Confirmar Eliminação</span>
                    <div class="h-1 w-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-full mt-1"></div>
                </h3>
            </div>
            
            <!-- Conteúdo do modal -->
            <div class="bg-white dark:bg-gray-800 p-6 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white via-white to-gray-100 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900">
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    Tem certeza que deseja eliminar esta localização? Esta ação <span class="font-bold text-red-600 dark:text-red-400">não pode ser desfeita</span> e todos os dados associados serão removidos permanentemente.
                </p>
                
                <!-- Informações da localização a ser eliminada -->
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700 flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Esta ação irá eliminar permanentemente:
                        </p>
                        <ul class="mt-1 text-xs text-gray-600 dark:text-gray-300 list-disc list-inside space-y-1">
                            <li>A localização e todos os seus dados</li>
                            <li>As relações com hotéis e outros registos</li>
                            <li>Todas as imagens associadas a esta localização</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Botões de ação (design 2025) -->
            <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 px-6 py-5 flex justify-end space-x-4 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl">
                <button 
                    x-on:click="confirmingDeletion = false; locationIdToDelete = null" 
                    type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button 
                    x-on:click="$wire.delete(locationIdToDelete); confirmingDeletion = false" 
                    type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
