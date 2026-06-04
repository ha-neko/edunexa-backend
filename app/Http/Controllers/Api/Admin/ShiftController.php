<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassroomShiftSchedule;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    // ── GET /api/admin/shifts ─────────────────────────────────────────────

    public function index(): JsonResponse
    {
        $shifts = Shift::withCount('schedules')
            ->orderBy('start_time')
            ->get();

        return response()->json(['data' => $shifts]);
    }

    // ── POST /api/admin/shifts ────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:50'],
            'start_time'     => ['required', 'date_format:H:i'],
            'late_tolerance' => ['required', 'date_format:H:i', 'after:start_time'],
            'end_time'       => ['required', 'date_format:H:i', 'after:late_tolerance'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        // Simpan sebagai H:i:s
        $data['start_time']     = $data['start_time'] . ':00';
        $data['late_tolerance'] = $data['late_tolerance'] . ':00';
        $data['end_time']       = $data['end_time'] . ':00';

        $shift = Shift::create($data);

        return response()->json(['message' => 'Shift berhasil dibuat.', 'data' => $shift], 201);
    }

    // ── GET /api/admin/shifts/{shift} ─────────────────────────────────────

    public function show(string $shift): JsonResponse
    {
        $shift = Shift::with(['schedules.classroom.major'])->findOrFail($shift);
        return response()->json(['data' => $shift]);
    }

    // ── PUT /api/admin/shifts/{shift} ─────────────────────────────────────

    public function update(Request $request, string $shift): JsonResponse
    {
        $shift = Shift::findOrFail($shift);

        $data = $request->validate([
            'name'           => ['sometimes', 'string', 'max:50'],
            'start_time'     => ['sometimes', 'date_format:H:i'],
            'late_tolerance' => ['sometimes', 'date_format:H:i'],
            'end_time'       => ['sometimes', 'date_format:H:i'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        foreach (['start_time', 'late_tolerance', 'end_time'] as $field) {
            if (isset($data[$field]) && strlen($data[$field]) === 5) {
                $data[$field] .= ':00';
            }
        }

        $shift->update($data);

        return response()->json(['message' => 'Shift berhasil diperbarui.', 'data' => $shift->fresh()]);
    }

    // ── DELETE /api/admin/shifts/{shift} ──────────────────────────────────

    public function destroy(string $shift): JsonResponse
    {
        $shift = Shift::findOrFail($shift);

        if ($shift->schedules()->exists()) {
            return response()->json([
                'message' => 'Shift masih digunakan oleh jadwal kelas. Hapus jadwal terlebih dahulu.',
            ], 422);
        }

        $shift->delete();

        return response()->json(['message' => 'Shift berhasil dihapus.']);
    }
}
