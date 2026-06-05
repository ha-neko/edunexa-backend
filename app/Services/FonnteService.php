<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected string $token;
    protected string $baseUrl;

    public function __construct()
    {
        $this->token   = config('app.fonnte_token', '');
        $this->baseUrl = 'https://api.fonnte.com';
    }

    public function isEnabled(): bool
    {
        return $this->token !== '';
    }

    public function send(string $target, string $message): array
    {
        if (! $this->isEnabled()) {
            return ['status' => false, 'reason' => 'Fonnte token not configured'];
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post("{$this->baseUrl}/send", [
            'target'  => $target,
            'message' => $message,
        ]);

        $body = $response->json();

        return [
            'status' => $response->successful(),
            'data'   => $body,
        ];
    }

    public function sendAttendanceNotification(
        string $phoneNumber,
        string $studentName,
        string $scanType,
        string $time,
        string $status = 'hadir'
    ): array {
        $emoji    = $scanType === 'in' ? '🏫' : '🏠';
        $label    = $scanType === 'in' ? 'MASUK' : 'KELUAR';
        $statusText = match ($status) {
            'hadir'  => 'Hadir',
            'sakit'  => 'Sakit',
            'izin'   => 'Izin',
            'alpha'  => 'Alpha',
            default => $status,
        };

        $message = "*Absensi Siswa - SMK Computer Science*\n\n";
        $message .= "{$emoji} *Scan {$label}*\n";
        $message .= "Nama: {$studentName}\n";
        $message .= "Waktu: {$time}\n";
        $message .= "Status: {$statusText}\n\n";
        $message .= "_Terima kasih._";

        return $this->send($phoneNumber, $message);
    }
}
