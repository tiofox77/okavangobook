<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class HotelReviews extends Component
{
    use WithFileUploads, WithPagination;

    public $hotelId;
    public $hotel;
    public $showModal = false;
    public $rating = 5;
    public $title = '';
    public $comment = '';
    public $photos = [];
    public $existingReview = null;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'nullable|string|max:255',
        'comment' => 'required|string|min:10|max:1000',
        'photos.*' => 'nullable|image|max:2048',
    ];

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->hotel = Hotel::findOrFail($hotelId);
        
        if (Auth::check()) {
            $this->existingReview = Review::where('hotel_id', $this->hotelId)
                ->where('user_id', Auth::id())
                ->first();
        }
    }

    public function openModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->existingReview) {
            $this->rating = $this->existingReview->rating;
            $this->title = $this->existingReview->title;
            $this->comment = $this->existingReview->comment;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $photoPaths = [];
        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $photoPaths[] = $photo->store('reviews', 'public');
            }
        }

        $reviewData = [
            'hotel_id' => $this->hotelId,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'title' => $this->title,
            'comment' => $this->comment,
            'photos' => !empty($photoPaths) ? $photoPaths : null,
            'is_approved' => true,
        ];

        if ($this->existingReview) {
            $this->existingReview->update($reviewData);
            session()->flash('message', 'Avaliação atualizada com sucesso!');
        } else {
            Review::create($reviewData);
            session()->flash('message', 'Avaliação criada com sucesso!');
        }

        $this->closeModal();
        $this->mount($this->hotelId);
    }

    public function deleteReview()
    {
        if ($this->existingReview) {
            $this->existingReview->delete();
            session()->flash('message', 'Avaliação removida com sucesso!');
            $this->existingReview = null;
        }
    }

    private function resetForm()
    {
        $this->rating = 5;
        $this->title = '';
        $this->comment = '';
        $this->photos = [];
        $this->resetValidation();
    }

    public function render()
    {
        $reviews = Review::where('hotel_id', $this->hotelId)
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = Review::where('hotel_id', $this->hotelId)
            ->where('is_approved', true)
            ->avg('rating');

        $totalReviews = Review::where('hotel_id', $this->hotelId)
            ->where('is_approved', true)
            ->count();

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = Review::where('hotel_id', $this->hotelId)
                ->where('is_approved', true)
                ->where('rating', $i)
                ->count();
        }

        return view('livewire.hotel-reviews', [
            'reviews' => $reviews,
            'averageRating' => round($averageRating, 1),
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
