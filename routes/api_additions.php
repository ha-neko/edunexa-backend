<?php

// =========================================================================
// TAMBAHAN ROUTES — Tambahkan ke routes/api.php yang sudah ada
// =========================================================================

use App\Http\Controllers\Api\Admin\ShiftController;
use App\Http\Controllers\Api\Admin\ClassroomShiftScheduleController;
use App\Http\Controllers\Api\QrScanController;

// ── PUBLIC — Scanner endpoint (tidak perlu login siswa) ──────────────────
// Amankan dengan middleware scanner_secret atau IP whitelist di production
Route::prefix('attendance')->group(function () {
    Route::get ('scan/student-info', [QrScanController::class, 'studentInfo']); // preview sebelum scan
    Route::post('scan',              [QrScanController::class, 'scan']);         // catat absensi
});


// ── Di dalam group Route::middleware('auth:api') yang sudah ada ──────────

// ── ADMIN ─────────────────────────────────────────────────────────────────
// Tambahkan di dalam: Route::middleware('role:admin')->prefix('admin')
//
//   // Shift
//   Route::apiResource('shifts', ShiftController::class);
//
//   // Jadwal shift per kelas
//   Route::get   ('classrooms/{classroom}/shift-schedule',              [ClassroomShiftScheduleController::class, 'index']);
//   Route::post  ('classrooms/{classroom}/shift-schedule',              [ClassroomShiftScheduleController::class, 'store']);
//   Route::delete('classrooms/{classroom}/shift-schedule/{schedule}',   [ClassroomShiftScheduleController::class, 'destroy']);
//
//   // Regenerate QR siswa
//   Route::post('students/{student}/regenerate-qr', [QrScanController::class, 'regenerateQr']);
// ─────────────────────────────────────────────────────────────────────────


// =========================================================================
// RINGKASAN SEMUA ENDPOINT BARU
// =========================================================================
//
// PUBLIC (scanner device):
//   GET  /api/attendance/scan/student-info?qr_token=xxx  → preview info siswa
//   POST /api/attendance/scan                            → catat scan masuk/keluar
//
// ADMIN (auth + role:admin):
//   GET    /api/admin/shifts                                     → list semua shift
//   POST   /api/admin/shifts                                     → buat shift baru
//   GET    /api/admin/shifts/{shift}                             → detail shift
//   PUT    /api/admin/shifts/{shift}                             → edit shift
//   DELETE /api/admin/shifts/{shift}                             → hapus shift
//
//   GET    /api/admin/classrooms/{classroom}/shift-schedule      → lihat jadwal kelas
//   POST   /api/admin/classrooms/{classroom}/shift-schedule      → set jadwal kelas
//   DELETE /api/admin/classrooms/{classroom}/shift-schedule/{s}  → hapus satu hari
//
//   POST   /api/admin/students/{student}/regenerate-qr           → reset QR siswa
// =========================================================================
