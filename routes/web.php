<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota principal - Página inicial
Route::get('/', App\Livewire\HomePage::class)->name('home');

// Rotas principais
Route::get('/search', App\Livewire\SearchResults::class)->name('search.results');
Route::get('/hotel/{id}', App\Livewire\HotelDetails::class)->name('hotel.details');
Route::get('/destinations', App\Livewire\Destinations::class)->name('destinations');
Route::get('/destination/{province}', App\Livewire\LocationDetails::class)->name('location.details');
Route::get('/about-angola', App\Livewire\AboutAngola::class)->name('about.angola');
Route::get('/contact', App\Livewire\Contact::class)->name('contact');

// Sistema de Reservas - Público (não requer login)
Route::get('/booking/create', App\Livewire\BookingCreate::class)->name('booking.create');
Route::get('/booking/confirm/{booking}', App\Livewire\BookingConfirm::class)->name('booking.confirm');
Route::get('/booking/success/{booking}', App\Livewire\BookingSuccess::class)->name('booking.success');

// Sistema de Reservas - Utilizador Logado
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', App\Livewire\MyBookings::class)->name('my.bookings');
    Route::get('/booking/{booking}', App\Livewire\BookingDetails::class)->name('booking.details');
});

// Rotas de autenticação
Auth::routes();

// Dashboard do utilizador normal
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
});

// Rotas do painel de administração - protegidas pelo middleware 'role'
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/hotels', App\Livewire\Admin\HotelManagement::class)->name('admin.hotels');
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('admin.users');
    Route::get('/locations', App\Livewire\Admin\LocationManagement::class)->name('admin.locations');
    Route::get('/rooms', App\Livewire\Admin\RoomManagement::class)->name('admin.rooms');
    Route::get('/individual-rooms', App\Livewire\Admin\IndividualRoomManagement::class)->name('admin.individual-rooms');
    Route::get('/amenities', App\Livewire\Admin\AmenityManagement::class)->name('admin.amenities');
    Route::get('/reservations', App\Livewire\Admin\ReservationManagement::class)->name('admin.reservations');
    Route::get('/reservations/create', App\Livewire\Admin\ReservationCreation::class)->name('admin.reservations.create');
    // Configurações do Sistema
    Route::get('/settings', App\Livewire\Admin\SettingsManagement::class)->name('admin.settings');
    Route::get('/updates', App\Livewire\Admin\SystemUpdates::class)->name('admin.updates');
});
