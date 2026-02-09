<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\RoomType;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function mount()
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['Admin', 'Propriedade'])) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth();
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');
        $myHotelIds = $isAdmin ? [] : $user->managedHotels()->pluck('id')->toArray();

        // Scoped query helpers for Propriedade filtering
        $hotelScope = function ($query) use ($isAdmin, $user) {
            if (!$isAdmin) $query->where('user_id', $user->id);
        };
        $reservationScope = function ($query) use ($isAdmin, $myHotelIds) {
            if (!$isAdmin) $query->whereIn('hotel_id', $myHotelIds);
        };
        $reviewScope = function ($query) use ($isAdmin, $myHotelIds) {
            if (!$isAdmin) $query->whereIn('hotel_id', $myHotelIds);
        };

        // Core statistics
        $totalHotels = Hotel::when(!$isAdmin, $hotelScope)->count();
        $activeHotels = Hotel::where('is_active', true)->when(!$isAdmin, $hotelScope)->count();
        $totalUsers = $isAdmin ? User::count() : 0;
        $newUsersThisMonth = $isAdmin ? User::where('created_at', '>=', $startOfMonth)->count() : 0;
        $totalLocations = $isAdmin ? Location::count() : 0;
        $totalRoomTypes = RoomType::when(!$isAdmin, function ($q) use ($myHotelIds) {
            $q->whereIn('hotel_id', $myHotelIds);
        })->count();

        // Reservation statistics
        $totalReservations = Reservation::when(!$isAdmin, $reservationScope)->count();
        $reservationsThisMonth = Reservation::where('created_at', '>=', $startOfMonth)->when(!$isAdmin, $reservationScope)->count();
        $reservationsLastMonth = Reservation::whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])->when(!$isAdmin, $reservationScope)->count();
        $pendingReservations = Reservation::where('status', 'pending')->when(!$isAdmin, $reservationScope)->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->when(!$isAdmin, $reservationScope)->count();
        $cancelledReservations = Reservation::where('status', 'cancelled')->when(!$isAdmin, $reservationScope)->count();

        // Revenue
        $revenueThisMonth = Reservation::where('created_at', '>=', $startOfMonth)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->when(!$isAdmin, $reservationScope)
            ->sum('total_price') ?? 0;
        $revenueLastMonth = Reservation::whereBetween('created_at', [$lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth()])
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->when(!$isAdmin, $reservationScope)
            ->sum('total_price') ?? 0;
        $totalRevenue = Reservation::whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->when(!$isAdmin, $reservationScope)
            ->sum('total_price') ?? 0;

        // Reviews
        $totalReviews = Review::when(!$isAdmin, $reviewScope)->count();
        $avgRating = (float) (Review::when(!$isAdmin, $reviewScope)->avg('rating') ?? 0);

        // Newsletter
        $totalSubscribers = $isAdmin ? NewsletterSubscriber::count() : 0;

        // Growth percentages
        $reservationGrowth = $reservationsLastMonth > 0
            ? round((($reservationsThisMonth - $reservationsLastMonth) / $reservationsLastMonth) * 100, 1)
            : ($reservationsThisMonth > 0 ? 100 : 0);
        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // Chart data: Reservations per month (last 6 months)
        $reservationsByMonth = [];
        $revenueByMonth = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabels[] = $month->translatedFormat('M Y');
            $reservationsByMonth[] = Reservation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->when(!$isAdmin, $reservationScope)->count();
            $revenueByMonth[] = (float) Reservation::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->when(!$isAdmin, $reservationScope)
                ->sum('total_price');
        }

        // Reservation status distribution
        $statusDistribution = [
            'pending' => $pendingReservations,
            'confirmed' => $confirmedReservations,
            'checked_in' => Reservation::where('status', 'checked_in')->when(!$isAdmin, $reservationScope)->count(),
            'checked_out' => Reservation::where('status', 'checked_out')->when(!$isAdmin, $reservationScope)->count(),
            'cancelled' => $cancelledReservations,
        ];

        // Top hotels by reservations
        $topHotels = Hotel::withCount('reservations')
            ->when(!$isAdmin, $hotelScope)
            ->orderByDesc('reservations_count')
            ->limit(5)
            ->get();

        // Top locations by hotel count
        $topLocations = $isAdmin
            ? Location::withCount('hotels')->orderByDesc('hotels_count')->limit(5)->get()
            : collect();

        // Recent reservations
        $recentReservations = Reservation::with(['user', 'hotel'])
            ->when(!$isAdmin, $reservationScope)
            ->latest()
            ->limit(8)
            ->get();

        // Recent users
        $recentUsers = $isAdmin ? User::latest()->limit(6)->get() : collect();

        // Recent reviews
        $recentReviews = Review::with(['user', 'hotel'])
            ->when(!$isAdmin, $reviewScope)
            ->latest()->limit(5)->get();

        // Hotels by property type
        $hotelsByType = Hotel::select('property_type', DB::raw('count(*) as total'))
            ->when(!$isAdmin, $hotelScope)
            ->groupBy('property_type')
            ->pluck('total', 'property_type')
            ->toArray();

        $statistics = [
            'hoteis' => $totalHotels,
            'hoteis_ativos' => $activeHotels,
            'utilizadores' => $totalUsers,
            'novos_utilizadores' => $newUsersThisMonth,
            'localizacoes' => $totalLocations,
            'quartos' => $totalRoomTypes,
            'reservas_total' => $totalReservations,
            'reservas_mes' => $reservationsThisMonth,
            'reservas_pendentes' => $pendingReservations,
            'reservas_confirmadas' => $confirmedReservations,
            'reservas_canceladas' => $cancelledReservations,
            'receita_total' => $totalRevenue,
            'receita_mes' => $revenueThisMonth,
            'avaliacoes' => $totalReviews,
            'media_avaliacao' => round($avgRating, 1),
            'newsletter' => $totalSubscribers,
            'crescimento_reservas' => $reservationGrowth,
            'crescimento_receita' => $revenueGrowth,
        ];

        return view('livewire.admin.dashboard', [
            'statistics' => $statistics,
            'monthLabels' => $monthLabels,
            'reservationsByMonth' => $reservationsByMonth,
            'revenueByMonth' => $revenueByMonth,
            'statusDistribution' => $statusDistribution,
            'topHotels' => $topHotels,
            'topLocations' => $topLocations,
            'recentReservations' => $recentReservations,
            'recentUsers' => $recentUsers,
            'recentReviews' => $recentReviews,
            'hotelsByType' => $hotelsByType,
        ])->layout('layouts.admin');
    }
}
