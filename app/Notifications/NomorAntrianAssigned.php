<?php

namespace App\Notifications;

use App\Models\Antrian;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Channels\WhatsAppChannel;

class NomorAntrianAssigned extends Notification
{
    use Queueable;

    protected $antrian;

    public function __construct(Antrian $antrian)
    {
        $this->antrian = $antrian;
    }

    public function via($notifiable)
    {
       return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Nomor Antrian Disetujui')
            ->line('Nomor antrian Anda telah disetujui.')
            ->line('Nomor antrian: ' . $this->antrian->nomor_antrian)
            ->line('Terima kasih telah menggunakan layanan kami.');
    }
    public function toWhatsApp($notifiable)
    {
        return 'Nomor antrian Anda telah disetujui. Nomor antrian: '
            . $this->antrian->nomor_antrian;
    }

    public function toArray($notifiable)
    {
        return [
            'antrian_id' => $this->antrian->id,
            'nomor_antrian' => $this->antrian->nomor_antrian,
        ];
    }
}