<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\MajorController;
use App\Http\Controllers\Api\Admin\ClassroomController;
use App\Http\Controllers\Api\Admin\TeacherController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\GuardianController;
use App\Http\Controllers\Api\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Api\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Api\Guru\ClassroomController as GuruClassroomController;
use App\Http\Controllers\Api\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Api\Guru\ReportController as GuruReportController;
use App\Http\Controllers\Api\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Api\Siswa\AttendanceController as SiswaAttendanceController;
use App\Http\Controllers\Api\Guardian\ProfileController as GuardianProfileController;
use App\Http\Controllers\Api\Guardian\StudentController as GuardianStudentController;
use App\Http\Controllers\Api\Guardian\AttendanceController as GuardianAttendanceController;
use App\Http\Controllers\Api\ProfilePhotoController;

// QR Attendance additions controllers
use App\Http\Controllers\Api\Admin\ShiftController;
use App\Http\Controllers\Api\Admin\ClassroomShiftScheduleController;
use App\Http\Controllers\Api\Admin\AttendancePdfController as AdminAttendancePdfController;
use App\Http\Controllers\Api\Guru\AttendancePdfController as GuruAttendancePdfController;
use App\Http\Controllers\Api\QrScanController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Sistem Absensi SMK CS
|--------------------------------------------------------------------------
|
| Prefix   : /api
| Auth     : JWT via guard 'api'
| Roles    : admin | guru | siswa | guardian
|
*/

// ── SCANNER ENDPOINTS (Authenticated via secret header) ────────────────
Route::middleware('scanner.auth')->prefix('attendance')->group(function () {
    Route::get ('scan/student-info', [QrScanController::class, 'studentInfo']); // Preview before scanning
    Route::post('scan',              [QrScanController::class, 'scan']);        // Clock in / out logging
});

// ── Role-Specific Auth Routes ──────────────────────────────────────────
$roles = ['admin', 'guru', 'siswa', 'guardian'];

foreach ($roles as $role) {
    Route::prefix($role . '/auth')->group(function () {

        // Public login
        Route::post('login', [AuthController::class, 'login']);

        // Authenticated sessions via JWT
        Route::middleware('auth:api')->group(function () {
            Route::get ('me',              [AuthController::class, 'me']);
            Route::post('refresh',         [AuthController::class, 'refresh']);
            Route::post('logout',          [AuthController::class, 'logout']);
            Route::put ('change-password', [AuthController::class, 'changePassword']);
        });

    });
}

// ── Authenticated Core Routes ─────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {

    // ── Shared — semua role ───────────────────────────────────────────────
    Route::post('user/profile-photo', [ProfilePhotoController::class, 'update']);

    // ── Scanner — today's attendance list for scanner page ───────────────
    Route::get('attendance/today', [QrScanController::class, 'todayAttendances']);

    // ── ADMIN ─────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Users
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/restore', [UserController::class, 'restore']);

        // Jurusan
        Route::apiResource('majors', MajorController::class);
        Route::post('majors/{major}/restore', [MajorController::class, 'restore']);

        // Kelas
        Route::apiResource('classrooms', ClassroomController::class);
        Route::post('classrooms/{classroom}/restore', [ClassroomController::class, 'restore']);

        // Guru
        Route::apiResource('teachers', TeacherController::class);
        Route::post('teachers/{teacher}/restore', [TeacherController::class, 'restore']);

        // Siswa
        Route::apiResource('students', StudentController::class);
        Route::post('students/{student}/restore', [StudentController::class, 'restore']);

        // Wali murid
        Route::apiResource('guardians', GuardianController::class);
        Route::post('guardians/{guardian}/restore', [GuardianController::class, 'restore']);

        // Absensi (no store — dibuat oleh guru)
        Route::apiResource('attendances', AdminAttendanceController::class)->except(['store']);

        // Laporan
        Route::get('reports/attendance', [AdminReportController::class, 'attendance']);
        Route::get('reports/attendance/pdf/daily', [AdminAttendancePdfController::class, 'daily']);
        Route::get('reports/attendance/pdf/daily-range', [AdminAttendancePdfController::class, 'dailyRange']);

        // ── NEW: Shift Management ──
        Route::apiResource('shifts', ShiftController::class);

        // ── NEW: Class Shift Schedules ──
        Route::get   ('classrooms/{classroom}/shift-schedule',            [ClassroomShiftScheduleController::class, 'index']);
        Route::post  ('classrooms/{classroom}/shift-schedule',            [ClassroomShiftScheduleController::class, 'store']);
        Route::delete('classrooms/{classroom}/shift-schedule/{schedule}', [ClassroomShiftScheduleController::class, 'destroy']);

        // ── NEW: Student QR Management ──
        Route::post('students/{student}/regenerate-qr', [QrScanController::class, 'regenerateQr']);
    });

    // ── GURU ──────────────────────────────────────────────────────────────
    Route::middleware('role:guru')->prefix('guru')->group(function () {

        // Profil
        Route::get('profile', [GuruProfileController::class, 'show']);
        Route::put('profile', [GuruProfileController::class, 'update']);

        // Kelas yang diwali
        Route::get('classrooms',                      [GuruClassroomController::class, 'index']);
        Route::get('classrooms/{classroom}',          [GuruClassroomController::class, 'show']);
        Route::get('classrooms/{classroom}/students', [GuruClassroomController::class, 'students']);

        // Absensi
        Route::get ('attendances',              [GuruAttendanceController::class, 'index']);
        Route::post('attendances',              [GuruAttendanceController::class, 'store']);
        Route::put ('attendances/{attendance}', [GuruAttendanceController::class, 'update']);

        // Laporan
        Route::get('reports/attendance', [GuruReportController::class, 'attendance']);
        Route::get('reports/attendance/pdf/daily', [GuruAttendancePdfController::class, 'daily']);
        Route::get('reports/attendance/pdf/daily-range', [GuruAttendancePdfController::class, 'dailyRange']);
        Route::get('reports/attendance/pdf/range', [GuruAttendancePdfController::class, 'export']);
    });

    // ── SISWA ─────────────────────────────────────────────────────────────
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {

        // Profil
        Route::get('profile', [SiswaProfileController::class, 'show']);

        // Absensi milik sendiri
        Route::get('attendances',              [SiswaAttendanceController::class, 'index']);
        Route::get('attendances/{attendance}', [SiswaAttendanceController::class, 'show']);
    });

    // ── GUARDIAN ──────────────────────────────────────────────────────────
    Route::middleware('role:guardian')->prefix('guardian')->group(function () {

        // Profil
        Route::get('profile', [GuardianProfileController::class, 'show']);

        // Data anak wali
        Route::get('students',           [GuardianStudentController::class, 'index']);
        Route::get('students/{student}', [GuardianStudentController::class, 'show']);

        // Absensi anak wali
        Route::get('attendances',              [GuardianAttendanceController::class, 'index']);
        Route::get('attendances/{attendance}', [GuardianAttendanceController::class, 'show']);
    });
});
