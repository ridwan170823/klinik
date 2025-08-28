<?php

namespace App\Notifications\Channels;

use App\Services\WhatsAppService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    protected WhatsAppService $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    public function send($notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationForWhatsApp($notification);
        $message = $notification->toWhatsApp($notifiable);

        $this->whatsApp->sendMessage($to, $message);
    }
}