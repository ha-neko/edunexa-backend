<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class AttendancePdfController extends Controller
{
    public function daily(Request $request): Response
    {
        $request->validate([
            'classroom_id' => ['required', 'ulid', 'exists:classrooms,id'],
            'date'         => ['nullable', 'date'],
        ]);

        $date      = $request->date ? Carbon::parse($request->date) : today();
        $classroom = Classroom::with(['major', 'waliKelas.user'])->findOrFail($request->classroom_id);

        $attendances = Attendance::with(['student.user'])
            ->whereDate('attendance_date', $date)
            ->whereHas('student', fn ($q) => $q->where('classroom_id', $classroom->id))
            ->orderBy('scan_in')
            ->get();

        $students = $classroom->students()->with('user')->orderBy('id')->get();

        $rows = $students->map(function ($student, $idx) use ($attendances) {
            $att = $attendances->firstWhere('student_id', $student->id);
            return [
                'no'       => $idx + 1,
                'nis'      => $student->nis,
                'name'     => $student->user->name,
                'scan_in'  => $att ? substr($att->scan_in ?? '', 0, 5) : '-',
                'scan_out' => $att ? substr($att->scan_out ?? '', 0, 5) : '-',
                'status'   => $att ? $att->status : 'alpha',
                'late'     => $att ? (bool) ($att->notes && str_contains($att->notes, 'Terlambat')) : false,
            ];
        });

        $summary = [
            'hadir' => $rows->where('status', 'hadir')->count(),
            'telat' => $rows->where('status', 'telat')->count(),
            'izin'  => $rows->where('status', 'izin')->count(),
            'sakit' => $rows->where('status', 'sakit')->count(),
            'alpha' => $rows->where('status', 'alpha')->count(),
        ];

        $pdf = Pdf::loadView('pdf.attendance-daily', compact(
            'classroom', 'rows', 'date', 'summary'
        ) + ['generatedAt' => now()]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 110, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => false]);

        $filename = sprintf(
            'Absensi_Harian_%s_%s.pdf',
            str_replace([' ', '/'], '_', $classroom->label),
            $date->format('d-m-Y')
        );

        return $pdf->download($filename);
    }
}
