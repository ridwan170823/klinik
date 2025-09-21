<?php

namespace App\Notifications;

use App\Models\Antrian;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AntrianScheduled extends Notification
{
    use Queueable;

    protected Antrian $antrian;
    protected bool $forAdmin;

    public function __construct(Antrian $antrian, bool $forAdmin = false)
    {
        $this->antrian = $antrian;
        $this->forAdmin = $forAdmin;
    }

    public function via($notifiable): array
    {
         $channels = ['database'];

        if (! $this->forAdmin && ! empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $tanggal = $this->antrian->tanggal
            ? Carbon::parse($this->antrian->tanggal)
                ->locale(app()->getLocale())
                ->translatedFormat('l, d F Y')
            : null;
        $waktuMulai = $this->antrian->jadwal->waktu_mulai ?? null;
        $waktuSelesai = $this->antrian->jadwal->waktu_selesai ?? null;
        $namaDokter = $this->antrian->dokter->nama ?? '-';
        $namaLayanan = $this->antrian->layanan->nama ?? '-';

        $waktuText = trim(implode(' - ', array_filter([$waktuMulai, $waktuSelesai])));
        $jadwalText = $tanggal
            ? trim($tanggal.' '.($waktuText ?: ''))
            : 'tanggal belum ditentukan';

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Jadwal Antrian Dikonfirmasi')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pasien') . '!')
            ->line('Permintaan antrian kamu sudah disetujui.')
            ->line('Layanan: ' . $namaLayanan)
            ->line('Dokter: ' . $namaDokter)
            ->line('Jadwal: ' . $jadwalText)
            ->line('Nomor antrian: ' . ($this->antrian->nomor_antrian ?? '-'))
            ->action('Lihat Detail Antrian', route('antrian.index'))
            ->line('Terima kasih telah mempercayakan layanan kami.');
    }

    public function toArray($notifiable): array
    {
        $tanggal = $this->antrian->tanggal
            ? Carbon::parse($this->antrian->tanggal)
                ->locale(app()->getLocale())
                ->translatedFormat('l, d F Y')
            : null;
        $waktuMulai = $this->antrian->jadwal->waktu_mulai ?? null;
        $waktuSelesai = $this->antrian->jadwal->waktu_selesai ?? null;
        $namaDokter = $this->antrian->dokter->nama ?? '-';
        $namaPasien = $this->antrian->user->name ?? '-';
        $namaLayanan = $this->antrian->layanan->nama ?? '-';

        $title = $this->forAdmin
            ? 'Antrian baru dijadwalkan'
            : 'Jadwal antrian dikonfirmasi';

        $waktuText = trim(implode(' - ', array_filter([$waktuMulai, $waktuSelesai])));
        $jadwalText = $tanggal
            ? trim($tanggal.' '.($waktuText ?: ''))
            : 'tanggal belum ditentukan';

        $message = $this->forAdmin
            ? sprintf('%s terjadwal untuk layanan %s bersama %s pada %s.',
                $namaPasien,
                $namaLayanan,
                $namaDokter,
                $jadwalText
            )
            : sprintf('Anda memiliki jadwal %s dengan %s pada %s.',
                $namaLayanan,
                $namaDokter,
                $jadwalText
            );

        return [
            'title' => $title,
            'message' => trim($message),
            'icon' => $this->forAdmin ? 'fa-clipboard-list' : 'fa-calendar-check',
            'antrian_id' => $this->antrian->id,
            'nomor_antrian' => $this->antrian->nomor_antrian,
            'status' => $this->antrian->status,
            'action_url' => route('antrian.index'),
        ];
    }
}