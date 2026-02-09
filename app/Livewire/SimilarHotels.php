<?php

namespace App\Livewire;

use App\Models\Hotel;
use Livewire\Component;

class SimilarHotels extends Component
{
    public $hotelId;
    public $limit = 4;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function render()
    {
        $similarHotels = $this->findSimilarHotels();

        return view('livewire.similar-hotels', [
            'similarHotels' => $similarHotels,
        ]);
    }

    private function findSimilarHotels()
    {
        $hotel = Hotel::with(['location', 'amenities', 'roomTypes'])->find($this->hotelId);
        
        if (!$hotel) {
            return collect();
        }

        $query = Hotel::where('id', '!=', $this->hotelId);

        // Mesma localização ou cidade próxima
        if ($hotel->location_id) {
            $query->where('location_id', $hotel->location_id);
        }

        // Mesma categoria de estrelas (±1 estrela)
        if ($hotel->stars) {
            $query->whereBetween('stars', [$hotel->stars - 1, $hotel->stars + 1]);
        }

        $candidates = $query->with(['amenities', 'roomTypes'])->get();

        // Calcular score de similaridade
        $scored = $candidates->map(function($candidate) use ($hotel) {
            $score = 0;

            // Mesmo número de estrelas = +10 pontos
            if ($candidate->stars == $hotel->stars) {
                $score += 10;
            }

            // Comodidades em comum
            $hotelAmenities = $hotel->amenities->pluck('id')->toArray();
            $candidateAmenities = $candidate->amenities->pluck('id')->toArray();
            $commonAmenities = count(array_intersect($hotelAmenities, $candidateAmenities));
            $score += $commonAmenities * 3;

            // Faixa de preço similar (±20%)
            $hotelAvgPrice = $hotel->roomTypes->avg('base_price');
            $candidateAvgPrice = $candidate->roomTypes->avg('base_price');
            if ($hotelAvgPrice && $candidateAvgPrice) {
                $priceDiff = abs($hotelAvgPrice - $candidateAvgPrice) / $hotelAvgPrice;
                if ($priceDiff <= 0.2) {
                    $score += 15;
                } elseif ($priceDiff <= 0.4) {
                    $score += 8;
                }
            }

            $candidate->similarity_score = $score;
            return $candidate;
        });

        return $scored->sortByDesc('similarity_score')->take($this->limit);
    }
}
