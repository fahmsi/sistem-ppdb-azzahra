<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppNotificationService
{
    public function send(?string $phone, string $message): void
    {
        $token = env('FONNTE_TOKEN');

        if (! $token || empty($phone)) {
            return;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);
        } catch (\Throwable) {
            // WhatsApp delivery must not block admin verification flow.
        }
    }
}
