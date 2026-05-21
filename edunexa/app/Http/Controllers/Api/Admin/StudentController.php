<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    // ── GET /api/admin/students ───────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $students = Student::withTrashed()
            ->with(['user', 'classroom.major', 'guardian.user'])
            ->when($request->search, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                )->orWhere('nis', 'like', "%{$request->search}%")
            )
            ->when($request->classroom_id, fn ($q) => $q->where('classroom_id', $request->classroom_id))
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($students);
    }

    // ── GET /api/admin/students/{student} ─────────────────────────────────

    public function show(string $student): JsonResponse
    {
        $student = Student::withTrashed()
            ->with(['user', 'classroom.major', 'guardian.user', 'todayAttendance'])
            ->findOrFail($student);

        return response()->json(['data' => $student]);
    }

    // ── POST /api/admin/students ──────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', Password::defaults()],
            'nis'          => ['required', 'string', 'max:20', 'unique:students,nis'],
            'classroom_id' => ['required', 'ulid', 'exists:classrooms,id'],
            'guardian_id'  => ['nullable', 'ulid', 'exists:guardians,id'],
        ]);

        $student = DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->assignRole('siswa');

            return Student::create([
                'user_id'      => $user->id,
                'nis'          => $data['nis'],
                'classroom_id' => $data['classroom_id'],
                'guardian_id'  => $data['guardian_id'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Siswa berhasil dibuat.',
            'data'    => $student->load(['user', 'classroom.major']),
        ], 201);
    }

    // ── PUT /api/admin/students/{student} ─────────────────────────────────

    public function update(Request $request, string $student): JsonResponse
    {
        $student = Student::withTrashed()->with('user')->findOrFail($student);

        $data = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'email'        => ['sometimes', 'email', "unique:users,email,{$student->user_id}"],
            'password'     => ['sometimes', Password::defaults()],
            'nis'          => ['sometimes', 'string', 'max:20', "unique:students,nis,{$student->id}"],
            'classroom_id' => ['sometimes', 'ulid', 'exists:classrooms,id'],
            'guardian_id'  => ['sometimes', 'nullable', 'ulid', 'exists:guardians,id'],
        ]);

        DB::transaction(function () use ($student, $data) {
            $userFields = array_filter([
                'name'     => $data['name'] ?? null,
                'email'    => $data['email'] ?? null,
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            ]);

            if ($userFields) {
                $student->user->update($userFields);
            }

            $student->update(array_intersect_key($data, array_flip(['nis', 'classroom_id', 'guardian_id'])));
        });

        return response()->json([
            'message' => 'Siswa berhasil diperbarui.',
            'data'    => $student->fresh(['user', 'classroom.major']),
        ]);
    }

    // ── DELETE /api/admin/students/{student} ──────────────────────────────

    public function destroy(string $student): JsonResponse
    {
        $student = Student::findOrFail($student);

        DB::transaction(function () use ($student) {
            $student->user->delete();
            $student->delete();
        });

        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }

    // ── POST /api/admin/students/{student}/restore ────────────────────────

    public function restore(string $student): JsonResponse
    {
        $student = Student::onlyTrashed()->with('user')->findOrFail($student);

        DB::transaction(function () use ($student) {
            $student->user->restore();
            $student->restore();
        });

        return response()->json(['message' => 'Siswa berhasil dipulihkan.', 'data' => $student]);
    }
}
