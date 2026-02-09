<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\SearchHistory;
use App\Models\UserPreference;
use Livewire\Component;

class RecommendedHotels extends Component
{
    public $limit = 6;

    public function render()
    {
        $hotels = $this->getRecommendedHotels();

        return view('livewire.recommended-hotels', [
            'hotels' => $hotels,
        ]);
    }

    private function getRecommendedHotels()
    {
        if (!auth()->check()) {
            return Hotel::inRandomOrder()->take($this->limit)->get();
        }

        $userId = auth()->id();
        $recommendations = collect();

        // Baseado em preferências do usuário
        $preferences = UserPreference::where('user_id', $userId)->first();
        if ($preferences && $preferences->receive_recommendations) {
            $query = Hotel::query();

            if ($preferences->preferred_locations) {
                $query->whereIn('location_id', $preferences->preferred_locations);
            }

            if ($preferences->preferred_stars) {
                $query->where('stars', '>=', $preferences->preferred_stars);
            }

            if ($preferences->budget_min && $preferences->budget_max) {
                $query->whereHas('roomTypes', function($q) use ($preferences) {
                    $q->whereBetween('base_price', [$preferences->budget_min, $preferences->budget_max]);
                });
            }

            $recommendations = $recommendations->merge($query->take(3)->get());
        }

        // Baseado em histórico de buscas
        $recentSearches = SearchHistory::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentSearches as $search) {
            if ($search->location) {
                $similar = Hotel::where('location', 'like', '%' . $search->location . '%')
                    ->whereNotIn('id', $recommendations->pluck('id'))
                    ->take(2)
                    ->get();
                $recommendations = $recommendations->merge($similar);
            }
        }

        // Completar com hotéis populares
        if ($recommendations->count() < $this->limit) {
            $popular = Hotel::withCount('reviews')
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->orderBy('reviews_count', 'desc')
                ->take($this->limit - $recommendations->count())
                ->get();
            $recommendations = $recommendations->merge($popular);
        }

        return $recommendations->unique('id')->take($this->limit);
    }
}
