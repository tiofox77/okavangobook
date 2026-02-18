<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        Notification::notifyAdmins(
            Notification::TYPE_USER_NEW,
            'Novo utilizador registado',
            "{$user->name} ({$user->email}) registou-se na plataforma",
            null,
            route('admin.users')
        );
    }
}
