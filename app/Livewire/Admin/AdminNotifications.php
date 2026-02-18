<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminNotifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showAll = false;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if (!$user) return;

        $query = Notification::where('user_id', $user->id)
            ->recent(30)
            ->orderBy('created_at', 'desc');

        $this->notifications = $query->take(10)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'badge_color' => $n->badge_color,
                'icon_svg' => $n->icon_svg,
                'time_ago' => $n->time_ago,
                'created_at' => $n->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $this->unreadCount = Notification::where('user_id', $user->id)
            ->unread()
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->delete();

        $this->loadNotifications();
    }

    public function clearAll()
    {
        Notification::where('user_id', Auth::id())
            ->recent(30)
            ->delete();

        $this->loadNotifications();
    }

    // Polling - atualiza a cada 30 segundos
    public function poll()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.admin.admin-notifications');
    }
}
