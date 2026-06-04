<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomShiftSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomShiftScheduleController extends Controller
{
    /**
     * GET /api/admin/classrooms/{classroom}/shift-schedule
     * Tampilkan jadwal shift lengkap satu kelas (7 hari).
     */
    public function index(string $classroom): JsonResponse
    {
        $classroom = Classroom::findOrFail($classroom);

        $schedules = ClassroomShiftSchedule::with('shift')
            ->where('classroom_id', $classroom->id)
            ->orderBy('day_of_week')
            ->get()
            ->map(fn ($s) => [
                'id'          => $s->id,
                'day_of_week' => $s->day_of_week,
                'day_name'    => $s->day_name,
                'shift'       => $s->shift,
            ]);

        return response()->json([
            'classroom' => $classroom->load('major'),
            'schedules' => $schedules,
        ]);
    }

    /**
     * POST /api/admin/classrooms/{classroom}/shift-schedule
     * Set/replace jadwal shift untuk kelas ini.
     *
     * Body contoh:
     * {
     *   "schedules": [
     *     { "day_of_week": 1, "shift_id": "01J..." },  // Senin -> Shift A
     *     { "day_of_week": 2, "shift_id": "01J..." },  // Selasa -> Shift A
     *     { "day_of_week": 3, "shift_id": "01J..." },  // Rabu -> Shift B
     *     { "day_of_week": 4, "shift_id": "01J..." },  // Kamis -> Shift B
     *     { "day_of_week": 5, "shift_id": "01J..." },  // Jumat -> Shift B
     *   ]
     * }
     */
    public function store(Request $request, string $classroom): JsonResponse
    {
        $classroom = Classroom::findOrFail($classroom);

        $data = $request->validate([
            'schedules'               => ['required', 'array', 'min:1'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'schedules.*.shift_id'    => ['required', 'ulid', 'exists:shifts,id'],
        ]);

        // Cek duplikasi hari dalam request yang sama
        $days = collect($data['schedules'])->pluck('day_of_week');
        if ($days->unique()->count() !== $days->count()) {
            return response()->json(['message' => 'Terdapat hari duplikat dalam request.'], 422);
        }

        // Hapus jadwal lama, ganti dengan yang baru (upsert bersih)
        ClassroomShiftSchedule::where('classroom_id', $classroom->id)->delete();

        $created = collect($data['schedules'])->map(fn ($row) =>
            ClassroomShiftSchedule::create([
                'classroom_id' => $classroom->id,
                'shift_id'     => $row['shift_id'],
                'day_of_week'  => $row['day_of_week'],
            ])
        );

        return response()->json([
            'message'   => 'Jadwal shift kelas berhasil disimpan.',
            'schedules' => $created->load('shift'),
        ], 201);
    }

    /**
     * DELETE /api/admin/classrooms/{classroom}/shift-schedule/{schedule}
     * Hapus satu entri jadwal (satu hari).
     */
    public function destroy(string $classroom, string $schedule): JsonResponse
    {
        $schedule = ClassroomShiftSchedule::where('classroom_id', $classroom)
            ->findOrFail($schedule);

        $schedule->delete();

        return response()->json(['message' => 'Jadwal hari berhasil dihapus.']);
    }
}
