<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoCheckout extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Auto checkout attendance records that have no scan_out by midnight';

    public function handle(): int
    {
        $today = Carbon::now()->toDateString();

        $updated = Attendance::whereNull('scan_out')
            ->whereDate('attendance_date', $today)
            ->update([
                'scan_out' => '23:59:00',
                'notes'    => \DB::raw("COALESCE(notes, '') || ' Auto checkout by system (23:59).'"),
            ]);

        $this->info("Auto-checkout completed for {$updated} student(s).");

        return Command::SUCCESS;
    }
}
