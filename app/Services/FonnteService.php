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
            logger()->warning('[Fonnte] Token not configured');
            return ['status' => false, 'reason' => 'Fonnte token not configured'];
        }

        logger()->info('[Fonnte] Sending', ['target' => $target, 'message_length' => strlen($message)]);

        try {
            $response = Http::timeout(15)->withHeaders([
                'Authorization' => $this->token,
            ])->post("{$this->baseUrl}/send", [
                'target'  => $target,
                'message' => $message,
            ]);

            $body   = $response->json();
            $status = $response->successful();

            if (! $status) {
                logger()->warning('[Fonnte] API error', [
                    'http_status' => $response->status(),
                    'body'        => $body,
                ]);
            }

            return [
                'status' => $status,
                'data'   => $body,
            ];
        } catch (\Throwable $e) {
            logger()->error('[Fonnte] Exception: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
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

        $message = "┌─ *ABSENSI SISWA*\n";
        $message .= "│ SMK Cipta Skill\n";
        $message .= "└──────────────────\n\n";
        $message .= "{$emoji} *Scan {$label}*\n";
        $message .= "Siswa  : {$studentName}\n";
        $message .= "Waktu  : {$time}\n";
        $message .= "Tanggal: " . now()->isoFormat('D MMMM Y') . "\n";

        if ($scanType === 'in') {
            if ($status === 'telat') {
                $message .= "Status : *Terlambat* ⏰\n";
                $message .= "——————————————\n";
                $message .= "Harap lebih pagi lain kali.\n";
            } else {
                $message .= "Status : *Tepat Waktu* ✅\n";
            }
        } else {
            $statusText = match ($status) {
                'hadir'  => 'Hadir ✅',
                'telat'  => 'Terlambat ⏰',
                'sakit'  => 'Sakit 🤒',
                'izin'   => 'Izin 📝',
                default => $status,
            };
            $message .= "Status : {$statusText}\n";
        }

        $message .= "\n──────────────────\n";
        $message .= "_Terima kasih._";

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
