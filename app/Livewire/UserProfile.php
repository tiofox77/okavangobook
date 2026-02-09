<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfile extends Component
{
    use WithPagination;

    public $activeTab = 'favorites';

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function removeFavorite($hotelId)
    {
        Auth::user()->favoriteHotels()->detach($hotelId);
        session()->flash('message', 'Hotel removido dos favoritos!');
    }

    public function render()
    {
        $user = Auth::user();

        $favorites = $user->favoriteHotels()
            ->with('location')
            ->paginate(9, ['*'], 'favoritesPage');

        $reservations = $user->reservations()
            ->with(['hotel', 'roomType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'reservationsPage');

        $reviews = $user->reviews()
            ->with('hotel')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'reviewsPage');

        return view('livewire.user-profile', [
            'user' => $user,
            'favorites' => $favorites,
            'reservations' => $reservations,
            'reviews' => $reviews,
        ])->layout('layouts.app');
    }
}
