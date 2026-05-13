<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
        ]);

        // Simpan
        $attendance = Attendance::create([
            'user_id'     => auth()->id(), // Mengambil ID dari token login
            'waktu_masuk' => now(),
            'status'      => $request->status,
            'keterangan'  => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil!',
            'data'    => $attendance
        ], 201);
    }
}