<?php

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use App\Models\Coupon;
use App\Models\SearchHistory;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Livewire\Component;

class Analytics extends Component
{
    public $period = '30';
    
    public function render()
    {
        $stats = $this->getStatistics();
        $charts = $this->getChartData();
        
        return view('livewire.admin.analytics', [
            'stats' => $stats,
            'charts' => $charts,
        ])->layout('layouts.admin');
    }
    
    private function getStatistics()
    {
        $startDate = Carbon::now()->subDays((int)$this->period);
        
        return [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'total_hotels' => Hotel::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'total_revenue' => Reservation::where('payment_status', 'paid')->sum('total_price'),
            'period_revenue' => Reservation::where('payment_status', 'paid')
                ->where('created_at', '>=', $startDate)
                ->sum('total_price'),
            'newsletter_subscribers' => NewsletterSubscriber::active()->count(),
            'active_coupons' => Coupon::active()->count(),
            'total_searches' => SearchHistory::count(),
            'recent_searches' => SearchHistory::where('created_at', '>=', $startDate)->count(),
            'avg_reservation_value' => Reservation::where('payment_status', 'paid')->avg('total_price'),
        ];
    }
    
    private function getChartData()
    {
        $startDate = Carbon::now()->subDays((int)$this->period);
        
        // Top 5 hotéis mais reservados
        $topHotels = Reservation::select('hotel_id')
            ->selectRaw('COUNT(*) as reservations_count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('hotel_id')
            ->orderByDesc('reservations_count')
            ->with('hotel:id,name')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->hotel->name ?? 'Desconhecido',
                    'count' => $item->reservations_count,
                ];
            });
        
        // Buscas mais populares
        $popularSearches = SearchHistory::where('created_at', '>=', $startDate)
            ->whereNotNull('location')
            ->selectRaw('location, COUNT(*) as count')
            ->groupBy('location')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        
        return [
            'top_hotels' => $topHotels,
            'popular_searches' => $popularSearches,
        ];
    }
}
