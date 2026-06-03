<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class AttendancePdfController extends Controller
{
    private function teacher()
    {
        return auth('api')->user()->teacher;
    }

    /**
     * GET /api/guru/reports/attendance/pdf
     *
     * Query params:
     *   classroom_id  (required) — kelas yang diwali oleh guru ini
     *   date_from     (optional) — default: awal bulan ini
     *   date_to       (optional) — default: hari ini
     */
    public function export(Request $request): Response
    {
        $request->validate([
            'classroom_id' => ['required', 'ulid', 'exists:classrooms,id'],
            'date_from'    => ['nullable', 'date'],
            'date_to'      => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        // ── Pastikan kelas ini milik guru yang login ───────────────────
        $teacher      = $this->teacher();
        $classroomIds = $teacher->classrooms()->pluck('id');

        if (! $classroomIds->contains($request->classroom_id)) {
            abort(403, 'Anda bukan wali kelas ini.');
        }

        $classroom = Classroom::with(['major', 'waliKelas.user'])->findOrFail($request->classroom_id);

        $dateFrom = $request->date_from ?? today()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? today()->toDateString();

        // ── Daftar hari kerja dalam rentang ───────────────────────────
        $dates = collect();
        $cur   = Carbon::parse($dateFrom);
        $end   = Carbon::parse($dateTo);
        while ($cur->lte($end)) {
            if ($cur->isWeekday()) {
                $dates->push($cur->copy());
            }
            $cur->addDay();
        }

        // ── Siswa + absensi ───────────────────────────────────────────
        $students = $classroom->students()
            ->with([
                'user',
                'attendances' => fn ($q) => $q
                    ->whereBetween('attendance_date', [$dateFrom, $dateTo])
                    ->orderBy('attendance_date'),
            ])
            ->orderBy('id')
            ->get();

        // ── Bangun baris tabel ─────────────────────────────────────────
        $rows = $students->map(function ($student, $idx) use ($dates) {
            $map     = $student->attendances->keyBy(fn ($a) => Carbon::parse($a->attendance_date)->toDateString());
            $summary = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
            $daily   = [];

            foreach ($dates as $date) {
                $key    = $date->toDateString();
                $att    = $map->get($key);
                $status = $att ? $att->status : 'alpha';
                $daily[$key] = [
                    'status'  => $status,
                    'scan_in' => $att ? substr($att->scan_in ?? '', 0, 5) : null,
                    'late'    => $att ? (bool) ($att->notes && str_contains($att->notes, 'Terlambat')) : false,
                ];
                $summary[$status]++;
            }

            return [
                'no'      => $idx + 1,
                'nis'     => $student->nis,
                'name'    => $student->user->name,
                'daily'   => $daily,
                'summary' => $summary,
            ];
        });

        $classTotal = [
            'hadir' => $rows->sum(fn ($r) => $r['summary']['hadir']),
            'izin'  => $rows->sum(fn ($r) => $r['summary']['izin']),
            'sakit' => $rows->sum(fn ($r) => $r['summary']['sakit']),
            'alpha' => $rows->sum(fn ($r) => $r['summary']['alpha']),
        ];

        // ── Render PDF ────────────────────────────────────────────────
        $orientation = $dates->count() > 14 ? 'landscape' : 'portrait';

        $pdf = Pdf::loadView('pdf.attendance-report', compact(
            'classroom', 'teacher', 'rows', 'dates',
            'dateFrom', 'dateTo', 'classTotal'
        ) + ['generatedAt' => now()]);

        $pdf->setPaper('a4', $orientation);
        $pdf->setOption(['dpi' => 110, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => false]);

        $filename = sprintf(
            'Absensi_%s_%s_sd_%s.pdf',
            str_replace([' ', '/'], '_', $classroom->label),
            Carbon::parse($dateFrom)->format('d-m-Y'),
            Carbon::parse($dateTo)->format('d-m-Y')
        );

        return $pdf->download($filename);
    }
}
