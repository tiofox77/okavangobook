<div>
    {{-- The whole world belongs to you. --}}
    
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 p-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-check text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gestão de Reservas</h1>
                    <p class="text-gray-600 dark:text-gray-300">Confirme reservas, atribua quartos e gerencie check-ins/outs</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reservations.create') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Reserva
                </a>
                <button type="button" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Exportar Relatório
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border-l-4 border-indigo-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total de Reservas</p>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</h2>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                    <i class="fas fa-clipboard-list text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Reservas Confirmadas</p>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['confirmed'] }}</h2>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Check-ins Ativos</p>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['checked_in'] }}</h2>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-door-open text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border-l-4 border-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Check-ins Hoje</p>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['today'] }}</h2>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-amber-600 dark:text-amber-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 p-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2 md:mb-0">
                <i class="fas fa-filter mr-2 text-indigo-600 dark:text-indigo-400"></i>Filtros e Pesquisa
            </h2>
            <div class="flex space-x-2">
                <select wire:model="perPage" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input wire:model.debounce.300ms="search" type="text" id="search" class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Código, cliente ou hotel...">
                </div>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model="status" id="status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="dateFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtro de Data</label>
                <select wire:model="dateFilter" id="dateFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todas as Datas</option>
                    @foreach($dateFilterOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="hotelFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel</label>
                <select wire:model="hotelFilter" id="hotelFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('status', '')" class="@if($status === '') bg-indigo-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-indigo-500 hover:text-white transition duration-150">
                <i class="fas fa-list-ul mr-1"></i> Todas
            </button>
            <button wire:click="$set('status', 'pending')" class="@if($status === 'pending') bg-amber-500 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-amber-400 hover:text-white transition duration-150">
                <i class="fas fa-clock mr-1"></i> Pendentes
            </button>
            <button wire:click="$set('status', 'confirmed')" class="@if($status === 'confirmed') bg-green-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-green-500 hover:text-white transition duration-150">
                <i class="fas fa-check mr-1"></i> Confirmadas
            </button>
            <button wire:click="$set('status', 'checked_in')" class="@if($status === 'checked_in') bg-blue-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-blue-500 hover:text-white transition duration-150">
                <i class="fas fa-door-open mr-1"></i> Check-in
            </button>
            <button wire:click="$set('dateFilter', 'today')" class="@if($dateFilter === 'today') bg-purple-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-purple-500 hover:text-white transition duration-150">
                <i class="fas fa-calendar-day mr-1"></i> Hoje
            </button>
        </div>
        
        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Filtro de Pagamento:</p>
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('paymentStatus', '')" class="@if($paymentStatus === '') bg-gray-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-gray-500 hover:text-white transition duration-150">
                    <i class="fas fa-wallet mr-1"></i> Todos
                </button>
                <button wire:click="$set('paymentStatus', 'pending')" class="@if($paymentStatus === 'pending') bg-amber-500 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-amber-400 hover:text-white transition duration-150">
                    <i class="fas fa-hourglass-half mr-1"></i> Pendente
                </button>
                <button wire:click="$set('paymentStatus', 'paid')" class="@if($paymentStatus === 'paid') bg-green-600 text-white @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif px-3 py-1 rounded-full text-sm hover:bg-green-500 hover:text-white transition duration-150">
                    <i class="fas fa-check-circle mr-1"></i> Pago
                </button>
            </div>
        </div>
    </div>
    
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm dark:bg-green-900 dark:text-green-200 flex justify-between items-center" x-data="{ show: true }" x-show="show">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500 text-lg"></i>
                <p>{{ session('message') }}</p>
            </div>
            <button type="button" @click="show = false" class="text-green-700 hover:text-green-900 dark:text-green-200 dark:hover:text-green-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm dark:bg-red-900 dark:text-red-200 flex justify-between items-center" x-data="{ show: true }" x-show="show">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500 text-lg"></i>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" @click="show = false" class="text-red-700 hover:text-red-900 dark:text-red-200 dark:hover:text-red-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Reservations Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" wire:loading.class="opacity-50">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-4 py-3 cursor-pointer" wire:click="sortBy('id')">
                            <div class="flex items-center">
                                ID
                                @include('components.admin.sort-icon', ['field' => 'id', 'sortField' => $sortField, 'sortDirection' => $sortDirection])
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 cursor-pointer" wire:click="sortBy('confirmation_code')">
                            <div class="flex items-center">
                                Código
                                @include('components.admin.sort-icon', ['field' => 'confirmation_code', 'sortField' => $sortField, 'sortDirection' => $sortDirection])
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 cursor-pointer" wire:click="sortBy('hotel_id')">
                            <div class="flex items-center">
                                Hotel
                                @include('components.admin.sort-icon', ['field' => 'hotel_id', 'sortField' => $sortField, 'sortDirection' => $sortDirection])
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <div class="flex items-center">
                                Cliente
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 cursor-pointer" wire:click="sortBy('check_in')">
                            <div class="flex items-center">
                                Check-in
                                @include('components.admin.sort-icon', ['field' => 'check_in', 'sortField' => $sortField, 'sortDirection' => $sortDirection])
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <div class="flex items-center">
                                Quarto
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 cursor-pointer" wire:click="sortBy('total_price')">
                            <div class="flex items-center">
                                Total
                                @include('components.admin.sort-icon', ['field' => 'total_price', 'sortField' => $sortField, 'sortDirection' => $sortDirection])
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <div class="flex items-center">
                                Estado
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <div class="flex items-center">
                                Pagamento
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="sr-only">Ações</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        @php
                            $isToday = $reservation->check_in->isToday();
                            $isTomorrow = $reservation->check_in->isTomorrow();
                            $rowClasses = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700';
                            if ($isToday && in_array($reservation->status, ['pending', 'confirmed'])) {
                                $rowClasses = 'bg-amber-50 border-b border-l-4 border-l-amber-500 dark:bg-amber-900/20 dark:border-gray-700 hover:bg-amber-100 dark:hover:bg-amber-900/30';
                            } elseif ($isTomorrow && $reservation->status === 'pending') {
                                $rowClasses = 'bg-blue-50 border-b border-l-4 border-l-blue-500 dark:bg-blue-900/20 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/30';
                            }
                        @endphp
                        <tr class="{{ $rowClasses }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <div class="flex items-center">
                                    {{ $reservation->id }}
                                    @if($isToday && in_array($reservation->status, ['pending', 'confirmed']))
                                        <span class="ml-2 flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-amber-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                        {{ $reservation->confirmation_code ?: 'Pendente' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                {{ $reservation->hotel->name }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-100 mr-3">
                                        <img src="{{ $reservation->user->profile_photo_url }}" alt="{{ $reservation->user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($reservation->user->name) }}&color=7F9CF5&background=EBF4FF'">
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $reservation->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reservation->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="@if($isToday) text-amber-600 dark:text-amber-400 font-semibold @endif">
                                        <i class="fas fa-calendar-alt @if($isToday) text-amber-500 @else text-indigo-500 @endif mr-1"></i> 
                                        {{ $reservation->check_in->format('d/m/Y') }}
                                        @if($isToday)
                                            <span class="ml-1 text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full dark:bg-amber-900 dark:text-amber-300">HOJE</span>
                                        @elseif($isTomorrow)
                                            <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">Amanhã</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><i class="fas fa-arrow-right text-gray-400 mr-1"></i> {{ $reservation->check_out->format('d/m/Y') }}</p>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400"><i class="fas fa-moon mr-1"></i>{{ $reservation->nights }} {{ $reservation->nights == 1 ? 'noite' : 'noites' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($reservation->room)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                        Nº {{ $reservation->room->room_number }}
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $reservation->roomType->name }}</p>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300">
                                        Não atribuído
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $reservation->roomType->name }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <div>
                                    <p>{{ number_format($reservation->total_price, 2, ',', '.') }} Kz</p>
                                    @if($reservation->payment_method)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-credit-card mr-1"></i> {{ __('reservations.payment_methods.' . $reservation->payment_method) }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @switch($reservation->status)
                                    @case('pending')
                                        <span class="inline-flex items-center bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-amber-900 dark:text-amber-300">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Pendente
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Confirmada
                                        </span>
                                        @break
                                    @case('checked_in')
                                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            <i class="fas fa-door-open mr-1.5"></i>
                                            Check-in
                                        </span>
                                        @break
                                    @case('checked_out')
                                    @case('completed')
                                        <span class="inline-flex items-center bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                                            <i class="fas fa-check-double mr-1.5"></i>
                                            Concluída
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-red-900 dark:text-red-300">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Cancelada
                                        </span>
                                        @break
                                    @case('no_show')
                                        <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fas fa-user-slash mr-1.5"></i>
                                            No-show
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                @switch($reservation->payment_status)
                                    @case('pending')
                                        <span class="inline-flex items-center bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-amber-900 dark:text-amber-300">
                                            <i class="fas fa-hourglass-half mr-1.5"></i>
                                            Pendente
                                        </span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Pago
                                        </span>
                                        @break
                                    @case('partial')
                                    @case('partially_paid')
                                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            <i class="fas fa-coins mr-1.5"></i>
                                            Parcial
                                        </span>
                                        @break
                                    @case('refunded')
                                        <span class="inline-flex items-center bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-purple-900 dark:text-purple-300">
                                            <i class="fas fa-undo mr-1.5"></i>
                                            Reembolsado
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-red-900 dark:text-red-300">
                                            <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                            Falhou
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <!-- Botão de Visualização Proeminente -->
                                    <button wire:click="openViewModal({{ $reservation->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg dark:text-indigo-400 dark:hover:bg-indigo-900/30 transition-colors" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($reservation->status === 'pending')
                                        <button wire:click="openConfirmModal({{ $reservation->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg dark:text-green-400 dark:hover:bg-green-900/30 transition-colors" title="Confirmar Reserva">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif

                                    @if($reservation->status === 'confirmed')
                                        <button wire:click="openCheckInModal({{ $reservation->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg dark:text-blue-400 dark:hover:bg-blue-900/30 transition-colors" title="Check-in">
                                            <i class="fas fa-door-open"></i>
                                        </button>
                                    @endif

                                    @if($reservation->status === 'checked_in')
                                        <button wire:click="openCheckOutModal({{ $reservation->id }})" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg dark:text-purple-400 dark:hover:bg-purple-900/30 transition-colors" title="Check-out">
                                            <i class="fas fa-door-closed"></i>
                                        </button>
                                    @endif

                                    <!-- Menu de Ações -->
                                    <div x-data="{ isOpen: false }" class="relative">
                                        <button @click="isOpen = !isOpen" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="isOpen" @click.away="isOpen = false" class="absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl z-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600" style="display: none">
                                            <a href="#" wire:click.prevent="openEditModal({{ $reservation->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                                <i class="fas fa-edit mr-2"></i> Editar
                                            </a>
                                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                                <a href="#" wire:click.prevent="openCancelModal({{ $reservation->id }})" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-600">
                                                    <i class="fas fa-times-circle mr-2"></i> Cancelar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="10" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                    <p class="text-lg font-medium mb-1">Nenhuma reserva encontrada</p>
                                    <p class="text-sm">Ajuste os filtros ou adicione novas reservas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Loading Indicator -->
        <div wire:loading wire:target="search, status, dateFilter, hotelFilter, sortBy" class="absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-50 dark:bg-opacity-50 flex items-center justify-center">
            <div class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-indigo-600 dark:text-indigo-400">A carregar resultados...</span>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
            <div class="hidden sm:block">
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    A mostrar <span class="font-medium">{{ $reservations->firstItem() ?: 0 }}</span> a <span class="font-medium">{{ $reservations->lastItem() ?: 0 }}</span> de <span class="font-medium">{{ $reservations->total() }}</span> reservas
                </p>
            </div>
            <div>
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
    
    <!-- Modal de Confirmação de Reserva -->
    <div x-data="{ show: false }" x-show="show" x-on:open-confirm-modal.window="show = true" x-on:close-confirm-modal.window="show = false" x-on:keydown.escape.window="show = false" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Confirmar Reserva
                            </h3>
                            <div class="mt-4">
                                @if($selectedReservation)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente</p>
                                                <p class="font-medium">{{ $selectedReservation->user->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Hotel</p>
                                                <p class="font-medium">{{ $selectedReservation->hotel->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Check-in</p>
                                                <p class="font-medium">{{ $selectedReservation->check_in->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Check-out</p>
                                                <p class="font-medium">{{ $selectedReservation->check_out->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Tipo de Quarto</p>
                                                <p class="font-medium">{{ $selectedReservation->roomType->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                                <p class="font-medium">{{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quarto</label>
                                        <select wire:model="selectedRoomId" id="room_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Selecionar quarto...</option>
                                            @foreach($availableRooms as $room)
                                                <option value="{{ $room->id }}">Nº {{ $room->room_number }} ({{ $room->floor }}º andar)</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('selectedRoomId'))
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('selectedRoomId') }}</span>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pagamento</label>
                                        <select wire:model="selectedPaymentMethod" id="payment_method" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Selecionar método...</option>
                                            @foreach($paymentMethodOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedPaymentMethod')
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado do Pagamento</label>
                                        <select wire:model="selectedPaymentStatus" id="payment_status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Selecionar estado...</option>
                                            @foreach($paymentStatusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedPaymentStatus')
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Carregando dados da reserva...</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="confirmReservation" wire:loading.attr="disabled" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmReservation">Confirmar</span>
                        <span wire:loading wire:target="confirmReservation">A processar...</span>
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Check-in -->
    <div x-data="{ show: false }" x-show="show" x-on:open-checkin-modal.window="show = true" x-on:close-checkin-modal.window="show = false" x-on:keydown.escape.window="show = false" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-door-open text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Realizar Check-in
                            </h3>
                            <div class="mt-4">
                                @if($selectedReservation)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente</p>
                                                <p class="font-medium">{{ $selectedReservation->user->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Hotel</p>
                                                <p class="font-medium">{{ $selectedReservation->hotel->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Código de Confirmação</p>
                                                <p class="font-medium">{{ $selectedReservation->confirmation_code }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Quarto</p>
                                                <p class="font-medium">Nº {{ $selectedReservation->room ? $selectedReservation->room->room_number : 'Não atribuído' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Check-in</p>
                                                <p class="font-medium">{{ $selectedReservation->check_in->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Check-out</p>
                                                <p class="font-medium">{{ $selectedReservation->check_out->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="guest_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Hóspedes</label>
                                        <input type="number" wire:model="guestCount" id="guest_count" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1">
                                        @error('guestCount')
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="checkin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas de Check-in</label>
                                        <textarea wire:model="checkinNotes" id="checkin_notes" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Informações adicionais, preferências ou solicitações especiais..."></textarea>
                                    </div>

                                    <div class="flex items-center mb-4">
                                        <input wire:model="verifiedDocuments" type="checkbox" id="verified_documents" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="verified_documents" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Documentos verificados
                                        </label>
                                    </div>

                                    <div class="flex items-center mb-4">
                                        <input wire:model="paymentConfirmed" type="checkbox" id="payment_confirmed" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="payment_confirmed" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Pagamento confirmado
                                        </label>
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Carregando dados da reserva...</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="processCheckIn" wire:loading.attr="disabled" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="processCheckIn">Realizar Check-in</span>
                        <span wire:loading wire:target="processCheckIn">A processar...</span>
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Check-out -->
    <div x-data="{ show: false }" x-show="show" x-on:open-checkout-modal.window="show = true" x-on:close-checkout-modal.window="show = false" x-on:keydown.escape.window="show = false" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-door-closed text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Realizar Check-out
                            </h3>
                            <div class="mt-4">
                                @if($selectedReservation)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente</p>
                                                <p class="font-medium">{{ $selectedReservation->user->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Código de Confirmação</p>
                                                <p class="font-medium">{{ $selectedReservation->confirmation_code }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Quarto</p>
                                                <p class="font-medium">Nº {{ $selectedReservation->room->room_number }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Data Check-in</p>
                                                <p class="font-medium">{{ $selectedReservation->actual_check_in ? $selectedReservation->actual_check_in->format('d/m/Y H:i') : 'N/D' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Data Check-out Prevista</p>
                                                <p class="font-medium">{{ $selectedReservation->check_out->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Pago</p>
                                                <p class="font-medium">{{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Consumos extras -->
                                    <div class="mb-4">
                                        <label for="additional_charges" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Consumos Extras</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" wire:model="additionalCharges" id="additional_charges" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0.00" step="0.01">
                                            <span class="text-gray-700 dark:text-gray-300">Kz</span>
                                        </div>
                                        @error('additionalCharges')
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="charges_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição dos Consumos</label>
                                        <textarea wire:model="chargesDescription" id="charges_description" rows="2" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Minibar, restaurante, spa, etc."></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="checkout_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas de Check-out</label>
                                        <textarea wire:model="checkoutNotes" id="checkout_notes" rows="2" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Observações ou comentários adicionais..."></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-2">Resumo do Pagamento</h4>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <div class="flex justify-between mb-2">
                                                <span class="text-gray-600 dark:text-gray-300">Valor da Reserva:</span>
                                                <span class="font-medium">{{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</span>
                                            </div>
                                            <div class="flex justify-between mb-2">
                                                <span class="text-gray-600 dark:text-gray-300">Consumos Extras:</span>
                                                <span class="font-medium">{{ number_format($additionalCharges ?? 0, 2, ',', '.') }} Kz</span>
                                            </div>
                                            <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                                            <div class="flex justify-between font-bold">
                                                <span class="text-gray-800 dark:text-gray-200">Total:</span>
                                                <span class="text-indigo-600 dark:text-indigo-400">{{ number_format(($selectedReservation->total_price + ($additionalCharges ?? 0)), 2, ',', '.') }} Kz</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center mb-4">
                                        <input wire:model="roomChecked" type="checkbox" id="room_checked" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="room_checked" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Quarto verificado
                                        </label>
                                    </div>

                                    <div class="flex items-center mb-4">
                                        <input wire:model="allPaymentSettled" type="checkbox" id="all_payment_settled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="all_payment_settled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Todos os pagamentos liquidados
                                        </label>
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Carregando dados da reserva...</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="processCheckOut" wire:loading.attr="disabled" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="processCheckOut">Completar Check-out</span>
                        <span wire:loading wire:target="processCheckOut">A processar...</span>
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Cancelamento de Reserva -->
    <div x-data="{ show: false }" x-show="show" x-on:open-cancel-modal.window="show = true" x-on:close-cancel-modal.window="show = false" x-on:keydown.escape.window="show = false" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Cancelar Reserva
                            </h3>
                            <div class="mt-4">
                                @if($selectedReservation)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente</p>
                                                <p class="font-medium">{{ $selectedReservation->user->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Código</p>
                                                <p class="font-medium">{{ $selectedReservation->confirmation_code ?: 'Pendente' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Hotel</p>
                                                <p class="font-medium">{{ $selectedReservation->hotel->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Tipo de Quarto</p>
                                                <p class="font-medium">{{ $selectedReservation->roomType->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Check-in</p>
                                                <p class="font-medium">{{ $selectedReservation->check_in->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                                <p class="font-medium">{{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Atenção</h3>
                                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                    <p>Esta ação irá cancelar a reserva e não pode ser desfeita. Confirme se esta é a ação correta.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo do Cancelamento</label>
                                        <select wire:model="cancellationReason" id="cancellation_reason" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Selecionar motivo...</option>
                                            @foreach($cancellationReasons as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('cancellationReason'))
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('cancellationReason') }}</span>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label for="cancellation_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas Adicionais</label>
                                        <textarea wire:model="cancellationNotes" id="cancellation_notes" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Detalhes sobre o motivo do cancelamento..."></textarea>
                                    </div>

                                    <div class="flex items-center mb-4">
                                        <input wire:model="issueRefund" type="checkbox" id="issue_refund" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="issue_refund" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Emitir reembolso
                                        </label>
                                    </div>

                                    @if($issueRefund)
                                        <div class="mb-4">
                                            <label for="refund_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor do Reembolso</label>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" wire:model="refundAmount" id="refund_amount" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" step="0.01" max="{{ $selectedReservation->total_price }}">
                                                <span class="text-gray-700 dark:text-gray-300">Kz</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Máximo: {{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</p>
                                            @error('refundAmount')
                                                <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Carregando dados da reserva...</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="cancelReservation" wire:loading.attr="disabled" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="cancelReservation">Confirmar Cancelamento</span>
                        <span wire:loading wire:target="cancelReservation">A processar...</span>
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Voltar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Visualização Detalhada da Reserva -->
    <div x-data="{ show: false }" x-show="show" x-on:open-view-modal.window="show = true" x-on:close-view-modal.window="show = false" x-on:keydown.escape.window="show = false" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full max-h-screen overflow-y-auto">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Detalhes da Reserva
                            </h3>
                            
                            @if($selectedReservation)
                                <div class="mt-4">
                                    <!-- Status e código da reserva -->
                                    <div class="flex justify-between items-center mb-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'amber',
                                                'confirmed' => 'green',
                                                'checked_in' => 'blue', 
                                                'completed' => 'indigo',
                                                'cancelled' => 'red',
                                                'no_show' => 'gray'
                                            ];
                                            $color = $statusColors[$selectedReservation->status] ?? 'gray';
                                        @endphp
                                        <span class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium px-3 py-1 rounded-full dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300">
                                            {{ __('reservations.status.' . $selectedReservation->status) }}
                                        </span>
                                        
                                        @if($selectedReservation->confirmation_code)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                Código: {{ $selectedReservation->confirmation_code }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 mt-4">Carregando dados da reserva...</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($selectedReservation)
                    <div class="bg-white dark:bg-gray-800 p-6 overflow-y-auto">
                        <!-- Informações principais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Detalhes Cliente -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-user-circle text-indigo-500 mr-2"></i> Informações do Cliente
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 mr-3">
                                            <img src="{{ $selectedReservation->user->profile_photo_url }}" alt="{{ $selectedReservation->user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($selectedReservation->user->name) }}&color=7F9CF5&background=EBF4FF'">
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedReservation->user->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedReservation->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Telefone</p>
                                            <p>{{ $selectedReservation->user->phone ?? 'Não informado' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Data de Registo</p>
                                            <p>{{ $selectedReservation->user->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Notas do Cliente</p>
                                        <p class="text-sm">{{ $selectedReservation->special_requests ?: 'Nenhuma nota adicionada' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detalhes da Propriedade e Quarto -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-hotel text-indigo-500 mr-2"></i> Detalhes da Propriedade
                                </h4>
                                <div class="space-y-3">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $selectedReservation->hotel->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedReservation->hotel->address }}</p>
                                    
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tipo de Quarto</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedReservation->roomType->name }}</p>
                                        <div class="text-sm mt-1">{{ $selectedReservation->roomType->description }}</div>
                                    </div>
                                    
                                    @if($selectedReservation->room)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Quarto Atribuído</p>
                                        <p class="text-indigo-600 dark:text-indigo-400 font-medium">Nº {{ $selectedReservation->room->room_number }} ({{ $selectedReservation->room->floor }}º andar)</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Datas e Pagamento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Datas e Horários -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i> Datas da Estadia
                                </h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-in Previsto</p>
                                        <p class="font-medium">{{ $selectedReservation->check_in->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">A partir das {{ $selectedReservation->hotel->check_in_time ?? '15:00' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-out Previsto</p>
                                        <p class="font-medium">{{ $selectedReservation->check_out->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Até às {{ $selectedReservation->hotel->check_out_time ?? '12:00' }}</p>
                                    </div>
                                    
                                    @if($selectedReservation->actual_check_in)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-in Realizado</p>
                                        <p class="font-medium text-green-600 dark:text-green-400">{{ $selectedReservation->actual_check_in->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($selectedReservation->actual_check_out)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-out Realizado</p>
                                        <p class="font-medium text-green-600 dark:text-green-400">{{ $selectedReservation->actual_check_out->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Duração</p>
                                    <p class="font-medium">{{ $selectedReservation->nights }} {{ $selectedReservation->nights == 1 ? 'noite' : 'noites' }}</p>
                                </div>
                            </div>
                            
                            <!-- Detalhes Pagamento -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-credit-card text-indigo-500 mr-2"></i> Detalhes do Pagamento
                                </h4>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Preço Base</p>
                                            <p class="font-medium">{{ number_format($selectedReservation->base_price, 2, ',', '.') }} Kz</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                            <p class="font-medium text-lg">{{ number_format($selectedReservation->total_price, 2, ',', '.') }} Kz</p>
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                            <p class="font-medium {{ $selectedReservation->payment_status === 'paid' ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                                                {{ __('reservations.payment_status.' . $selectedReservation->payment_status) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Método</p>
                                            <p class="font-medium">{{ __('reservations.payment_method.' . $selectedReservation->payment_method) }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($selectedReservation->payment_date)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Data do Pagamento</p>
                                        <p class="font-medium">{{ $selectedReservation->payment_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($selectedReservation->cancellation_date && $selectedReservation->refund_amount > 0)
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3 mt-3">
                                        <p class="text-xs text-red-500 dark:text-red-400">Reembolso Emitido</p>
                                        <p class="font-medium text-red-600 dark:text-red-400">{{ number_format($selectedReservation->refund_amount, 2, ',', '.') }} Kz</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Em {{ $selectedReservation->cancellation_date->format('d/m/Y') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Histórico e Consumos -->
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Consumos Extras -->
                            @if($selectedReservation->extra_charges > 0 || $selectedReservation->extra_charges_description)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-receipt text-indigo-500 mr-2"></i> Consumos Extras
                                </h4>
                                <div class="space-y-3">
                                    @if($selectedReservation->extra_charges > 0)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Valor Total</p>
                                        <p class="font-medium">{{ number_format($selectedReservation->extra_charges, 2, ',', '.') }} Kz</p>
                                    </div>
                                    @endif
                                    
                                    @if($selectedReservation->extra_charges_description)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Descrição</p>
                                        <p class="text-sm">{{ $selectedReservation->extra_charges_description }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <!-- Histórico de Alterações -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-history text-indigo-500 mr-2"></i> Histórico da Reserva
                                </h4>
                                
                                <div class="relative">
                                    <div class="absolute h-full w-0.5 bg-gray-200 dark:bg-gray-600 left-2"></div>
                                    <div class="space-y-4 ml-6">
                                        <div class="relative">
                                            <div class="absolute -left-5 mt-1.5">
                                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                            </div>
                                            <p class="text-sm font-medium">Criação da Reserva</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedReservation->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="text-sm">Reserva feita por {{ $selectedReservation->user->name }}</p>
                                        </div>
                                        
                                        @if($selectedReservation->confirmation_date)
                                        <div class="relative">
                                            <div class="absolute -left-5 mt-1.5">
                                                <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                            </div>
                                            <p class="text-sm font-medium">Confirmação da Reserva</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedReservation->confirmation_date->format('d/m/Y H:i') }}</p>
                                            <p class="text-sm">Quarto atribuído: {{ $selectedReservation->room ? 'N° ' . $selectedReservation->room->room_number : 'Não atribuído' }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($selectedReservation->actual_check_in)
                                        <div class="relative">
                                            <div class="absolute -left-5 mt-1.5">
                                                <div class="h-3 w-3 rounded-full bg-indigo-500"></div>
                                            </div>
                                            <p class="text-sm font-medium">Check-in Realizado</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedReservation->actual_check_in->format('d/m/Y H:i') }}</p>
                                            @if($selectedReservation->check_in_notes)
                                                <p class="text-sm">{{ $selectedReservation->check_in_notes }}</p>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        @if($selectedReservation->actual_check_out)
                                        <div class="relative">
                                            <div class="absolute -left-5 mt-1.5">
                                                <div class="h-3 w-3 rounded-full bg-purple-500"></div>
                                            </div>
                                            <p class="text-sm font-medium">Check-out Realizado</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedReservation->actual_check_out->format('d/m/Y H:i') }}</p>
                                            @if($selectedReservation->check_out_notes)
                                                <p class="text-sm">{{ $selectedReservation->check_out_notes }}</p>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        @if($selectedReservation->cancellation_date)
                                        <div class="relative">
                                            <div class="absolute -left-5 mt-1.5">
                                                <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                            </div>
                                            <p class="text-sm font-medium">Reserva Cancelada</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedReservation->cancellation_date->format('d/m/Y H:i') }}</p>
                                            <p class="text-sm">Motivo: {{ $selectedReservation->cancellation_reason }}</p>
                                            @if($selectedReservation->cancellation_notes)
                                                <p class="text-sm">{{ $selectedReservation->cancellation_notes }}</p>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
