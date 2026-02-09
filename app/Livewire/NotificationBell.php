<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $showDropdown = false;
    public $unreadCount = 0;

    public function mount()
    {
        if (auth()->check()) {
            $this->loadUnreadCount();
        }
    }

    public function loadUnreadCount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->loadUnreadCount();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        $this->loadUnreadCount();
    }

    public function render()
    {
        $notifications = auth()->check() 
            ? auth()->user()->notifications()->latest()->take(5)->get()
            : collect();

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}
