<?php

namespace App\Observers;

use App\Models\NewsletterSubscriber;
use App\Models\Notification;

class NewsletterSubscriberObserver
{
    public function created(NewsletterSubscriber $subscriber)
    {
        Notification::notifyAdmins(
            Notification::TYPE_NEWSLETTER_NEW,
            'Novo assinante da newsletter',
            "{$subscriber->email} subscreveu a newsletter",
            null,
            route('admin.newsletter')
        );
    }
}
