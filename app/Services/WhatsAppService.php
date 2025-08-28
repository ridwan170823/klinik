<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string|null $sid;
    protected string|null $token;
    protected string|null $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.whatsapp_from');
    }

    public function sendMessage(string $to, string $message): void
    {
        if (! $to || ! $this->sid || ! $this->token || ! $this->from) {
            return;
        }

        try {
            Http::withBasicAuth($this->sid, $this->token)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                    'From' => "whatsapp:{$this->from}",
                    'To' => "whatsapp:{$to}",
                    'Body' => $message,
                ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp send failed: '.$e->getMessage());
        }
    }
}