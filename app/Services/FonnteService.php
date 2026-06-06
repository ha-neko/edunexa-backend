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

        $message = "*Absensi Siswa - SMK Computer Science*\n\n";
        $message .= "{$emoji} *Scan {$label}*\n";
        $message .= "Nama: {$studentName}\n";
        $message .= "Waktu: {$time}\n";

        if ($scanType === 'in') {
            if ($status === 'telat') {
                $message .= "Status: *Terlambat* ⏰\n";
                $message .= "Harap lebih pagi lain kali.\n";
            } else {
                $message .= "Status: *Tepat Waktu* ✅\n";
            }
        } else {
            $statusText = match ($status) {
                'hadir'  => 'Hadir',
                'telat'  => 'Terlambat',
                'sakit'  => 'Sakit',
                'izin'   => 'Izin',
                default => $status,
            };
            $message .= "Status: {$statusText}\n";
        }

        $message .= "\n_terima kasih._";

        $result = $this->send($phoneNumber, $message);

        if (! $result['status']) {
            logger()->warning('[Fonnte] Gagal kirim notifikasi', [
                'phone' => $phoneNumber,
                'reason' => $result['reason'] ?? 'unknown',
                'data' => $result['data'] ?? [],
            ]);
        }

        return $result;
    }
}
