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

// SEO - Sitemap dinâmico
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Troca de idioma (PT/EN)
Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, \App\Http\Middleware\SetLocale::SUPPORTED, true)) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

// Rotas principais (URLs em português; nomes mantidos para compatibilidade)
Route::get('/pesquisa', App\Livewire\SearchResults::class)->name('search.results');
Route::get('/hotel/{slug}', App\Livewire\HotelDetails::class)->name('hotel.details');
Route::get('/comparar', App\Livewire\HotelComparison::class)->name('hotels.compare');
Route::get('/destinos', App\Livewire\Destinations::class)->name('destinations');
Route::get('/destino/{province}', App\Livewire\LocationDetails::class)->name('location.details');
Route::get('/sobre-angola', App\Livewire\AboutAngola::class)->name('about.angola');
Route::get('/contacto', App\Livewire\Contact::class)->name('contact');
Route::get('/planos', App\Livewire\PricingPage::class)->name('pricing');

// Artigos / Blog (público)
Route::get('/blog', App\Livewire\ArticlesList::class)->name('articles');
Route::get('/blog/{slug}', App\Livewire\ArticleDetails::class)->name('article.details');

// Páginas institucionais (estáticas)
Route::view('/privacidade', 'pages.privacy')->name('privacy');
Route::view('/termos', 'pages.terms')->name('terms');
Route::view('/faq', 'pages.faq')->name('faq');

// Sistema de Reservas - Público (não requer login)
Route::get('/reserva/criar', App\Livewire\BookingCreate::class)->name('booking.create');
Route::get('/reserva/confirmar/{booking}', App\Livewire\BookingConfirm::class)->name('booking.confirm');
Route::get('/reserva/sucesso/{booking}', App\Livewire\BookingSuccess::class)->name('booking.success');

// Sistema de Reservas - Utilizador Logado
Route::middleware(['auth'])->group(function () {
    Route::get('/minhas-reservas', App\Livewire\MyBookings::class)->name('my.bookings');
    Route::get('/reserva/{booking}', App\Livewire\BookingDetails::class)->name('booking.details');
    Route::get('/perfil', App\Livewire\UserProfile::class)->name('profile');
});

// Rotas de autenticação
Auth::routes();

// Dashboard do utilizador normal
Route::middleware(['auth'])->group(function () {
    Route::get('/conta', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/alertas-preco', App\Livewire\PriceAlerts::class)->name('price-alerts');
});

// Redirects 301 das URLs antigas (EN) para as novas (PT) — preserva SEO/links
Route::permanentRedirect('/search', '/pesquisa');
Route::permanentRedirect('/compare', '/comparar');
Route::permanentRedirect('/destinations', '/destinos');
Route::permanentRedirect('/destination/{province}', '/destino/{province}');
Route::permanentRedirect('/about-angola', '/sobre-angola');
Route::permanentRedirect('/contact', '/contacto');
Route::permanentRedirect('/pricing', '/planos');
Route::permanentRedirect('/articles', '/blog');
Route::permanentRedirect('/articles/{slug}', '/blog/{slug}');
Route::permanentRedirect('/booking/create', '/reserva/criar');
Route::permanentRedirect('/booking/confirm/{booking}', '/reserva/confirmar/{booking}');
Route::permanentRedirect('/booking/success/{booking}', '/reserva/sucesso/{booking}');
Route::permanentRedirect('/booking/{booking}', '/reserva/{booking}');
Route::permanentRedirect('/my-bookings', '/minhas-reservas');
Route::permanentRedirect('/profile', '/perfil');
Route::permanentRedirect('/dashboard', '/conta');
Route::permanentRedirect('/price-alerts', '/alertas-preco');

// Rotas do painel de administração - Admin e Propriedade
Route::middleware(['auth', 'role:Admin|Propriedade'])->prefix('admin')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/hotels', App\Livewire\Admin\HotelManagement::class)->name('admin.hotels');
    Route::get('/rooms', App\Livewire\Admin\RoomManagement::class)->name('admin.rooms');
    Route::get('/individual-rooms', App\Livewire\Admin\IndividualRoomManagement::class)->name('admin.individual-rooms');
    Route::get('/amenities', App\Livewire\Admin\AmenityManagement::class)->name('admin.amenities');
    Route::get('/restaurant', App\Livewire\Admin\RestaurantManagement::class)->name('admin.restaurant');
    Route::get('/leisure-facilities', App\Livewire\Admin\LeisureFacilitiesManagement::class)->name('admin.leisure');
    Route::get('/reservations', App\Livewire\Admin\ReservationManagement::class)->name('admin.reservations');
    Route::get('/reservations/create', App\Livewire\Admin\ReservationCreation::class)->name('admin.reservations.create');
    Route::get('/notifications', App\Livewire\Admin\NotificationsPage::class)->name('admin.notifications');
    Route::get('/profile', App\Livewire\Admin\AdminProfile::class)->name('admin.profile');
    Route::get('/my-subscription', App\Livewire\Admin\MySubscription::class)->name('admin.my-subscription');
});

// Rotas exclusivas do Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('admin.users');
    Route::get('/locations', App\Livewire\Admin\LocationManagement::class)->name('admin.locations');
    Route::get('/coupons', App\Livewire\Admin\CouponManagement::class)->name('admin.coupons');
    Route::get('/newsletter', App\Livewire\Admin\NewsletterManagement::class)->name('admin.newsletter');
    Route::get('/newsletter/send', App\Livewire\Admin\NewsletterSend::class)->name('admin.newsletter.send');
    Route::get('/analytics', App\Livewire\Admin\Analytics::class)->name('admin.analytics');
    Route::get('/articles', App\Livewire\Admin\ArticleManagement::class)->name('admin.articles');
    Route::get('/reports/reservations', App\Livewire\Admin\ReservationReports::class)->name('admin.reports.reservations');
    Route::get('/settings', App\Livewire\Admin\SettingsManagement::class)->name('admin.settings');
    Route::get('/updates', App\Livewire\Admin\SystemUpdates::class)->name('admin.updates');
    Route::get('/plans', App\Livewire\Admin\PlanManagement::class)->name('admin.plans');
    Route::get('/payments', App\Livewire\Admin\PaymentManagement::class)->name('admin.payments');
});
