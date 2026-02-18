<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsPage extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterRead = '';
    public $search = '';

    protected $queryString = ['filterType', 'filterRead'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterRead()
    {
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
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
    }

    public function deleteNotification($notificationId)
    {
        Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function deleteAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();
    }

    public function getNotificationTypes()
    {
        return [
            '' => 'Todos os tipos',
            Notification::TYPE_RESERVATION_NEW => 'Novas reservas',
            Notification::TYPE_RESERVATION_CONFIRMED => 'Reservas confirmadas',
            Notification::TYPE_RESERVATION_CANCELLED => 'Reservas canceladas',
            Notification::TYPE_RESERVATION_CHECKIN => 'Check-ins',
            Notification::TYPE_RESERVATION_CHECKOUT => 'Check-outs',
            Notification::TYPE_PAYMENT_RECEIVED => 'Pagamentos',
            Notification::TYPE_REVIEW_NEW => 'Avaliações',
            Notification::TYPE_USER_NEW => 'Novos utilizadores',
            Notification::TYPE_HOTEL_NEW => 'Novas propriedades',
            Notification::TYPE_NEWSLETTER_NEW => 'Newsletter',
            Notification::TYPE_SYSTEM => 'Sistema',
        ];
    }

    public function render()
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterRead === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filterRead === 'read') {
            $query->where('is_read', true);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%");
            });
        }

        $notifications = $query->paginate(20);

        $unreadCount = Notification::where('user_id', Auth::id())->unread()->count();
        $totalCount = Notification::where('user_id', Auth::id())->count();

        return view('livewire.admin.notifications-page', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
            'notificationTypes' => $this->getNotificationTypes(),
        ])->layout('layouts.admin');
    }
}
