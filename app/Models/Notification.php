<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Tipos de notificação
    const TYPE_RESERVATION_NEW = 'reservation_new';
    const TYPE_RESERVATION_CONFIRMED = 'reservation_confirmed';
    const TYPE_RESERVATION_CANCELLED = 'reservation_cancelled';
    const TYPE_RESERVATION_CHECKIN = 'reservation_checkin';
    const TYPE_RESERVATION_CHECKOUT = 'reservation_checkout';
    const TYPE_PAYMENT_RECEIVED = 'payment_received';
    const TYPE_REVIEW_NEW = 'review_new';
    const TYPE_USER_NEW = 'user_new';
    const TYPE_HOTEL_NEW = 'hotel_new';
    const TYPE_NEWSLETTER_NEW = 'newsletter_subscriber';
    const TYPE_SYSTEM = 'system';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'link',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public static function createForUser($userId, $type, $title, $message, $icon = null, $link = null)
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'link' => $link,
        ]);
    }

    /**
     * Cria notificação para todos os admins
     */
    public static function notifyAdmins($type, $title, $message, $icon = null, $link = null)
    {
        $admins = User::role('Admin')->pluck('id');
        
        foreach ($admins as $adminId) {
            static::create([
                'user_id' => $adminId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'link' => $link,
            ]);
        }
    }

    /**
     * Cria notificação para o proprietário do hotel
     */
    public static function notifyHotelOwner($hotelId, $type, $title, $message, $icon = null, $link = null)
    {
        $hotel = Hotel::find($hotelId);
        if ($hotel && $hotel->user_id) {
            static::create([
                'user_id' => $hotel->user_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'link' => $link,
            ]);
        }
    }

    /**
     * Cria notificação para admins + proprietário do hotel
     */
    public static function notifyAdminsAndOwner($hotelId, $type, $title, $message, $icon = null, $link = null)
    {
        static::notifyAdmins($type, $title, $message, $icon, $link);
        
        $hotel = Hotel::find($hotelId);
        if ($hotel && $hotel->user_id) {
            // Não duplicar se o owner também é admin
            $isAdmin = User::find($hotel->user_id)?->hasRole('Admin');
            if (!$isAdmin) {
                static::create([
                    'user_id' => $hotel->user_id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'icon' => $icon,
                    'link' => $link,
                ]);
            }
        }
    }

    /**
     * Retorna a cor CSS do badge baseado no tipo
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_RESERVATION_NEW => 'bg-blue-500',
            self::TYPE_RESERVATION_CONFIRMED => 'bg-green-500',
            self::TYPE_RESERVATION_CANCELLED => 'bg-red-500',
            self::TYPE_RESERVATION_CHECKIN => 'bg-indigo-500',
            self::TYPE_RESERVATION_CHECKOUT => 'bg-purple-500',
            self::TYPE_PAYMENT_RECEIVED => 'bg-emerald-500',
            self::TYPE_REVIEW_NEW => 'bg-yellow-500',
            self::TYPE_USER_NEW => 'bg-cyan-500',
            self::TYPE_HOTEL_NEW => 'bg-orange-500',
            self::TYPE_NEWSLETTER_NEW => 'bg-pink-500',
            self::TYPE_SYSTEM => 'bg-gray-500',
            default => 'bg-blue-500',
        };
    }

    /**
     * Retorna o ícone SVG baseado no tipo
     */
    public function getIconSvgAttribute(): string
    {
        return match($this->type) {
            self::TYPE_RESERVATION_NEW, self::TYPE_RESERVATION_CONFIRMED => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
            self::TYPE_RESERVATION_CANCELLED => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
            self::TYPE_RESERVATION_CHECKIN => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />',
            self::TYPE_RESERVATION_CHECKOUT => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />',
            self::TYPE_PAYMENT_RECEIVED => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />',
            self::TYPE_REVIEW_NEW => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />',
            self::TYPE_USER_NEW => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />',
            self::TYPE_HOTEL_NEW => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
            self::TYPE_NEWSLETTER_NEW => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
            default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        };
    }

    /**
     * Tempo relativo formatado
     */
    public function getTimeAgoAttribute(): string
    {
        $diff = $this->created_at->diffForHumans();
        return $diff;
    }
}
