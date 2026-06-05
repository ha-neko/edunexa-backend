
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// =====================
// AUTH ROUTES
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::get('/forgot-password', fn() => view('auth.forgotpassword'))->name('auth.forgot-password');
    Route::get('/otp', fn() => view('auth.otp'))->name('auth.otp');
    Route::get('/reset-password', fn() => view('auth.resetpass'))->name('auth.reset-password');
});

// =====================
// STORE TOKEN
// =====================
Route::post('/auth/store-token', function(Request $request) {
    $request->session()->put('token', $request->token);
    $request->session()->put('role', $request->role);
    return response()->json(['success' => true]);
})->name('auth.store-token');

// =====================
// LOGOUT   
// =====================
Route::post('/logout', function(Request $request) {
    $request->session()->flush();
    return redirect()->route('login');
})->name('logout');

// =====================
// ADMIN ROUTES
// =====================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', fn() => view('admin.home'))->name('home');
    Route::get('/master-user', fn() => view('admin.user'))->name('master-user');
    Route::get('/master-jurusan', fn() => view('admin.jurusan'))->name('master-jurusan');
    Route::get('/master-siswa', fn() => view('admin.Role.siswa'))->name('master-siswa');
    Route::get('/master-guru', fn() => view('admin.Role.guru'))->name('master-guru');
    Route::get('/master-kelas', fn() => view('admin.class'))->name('master-kelas');
    Route::get('/jadwal', fn() => view('admin.jadwal'))->name('jadwal');
    Route::get('/shift', fn() => view('admin.shift'))->name('shift');
    Route::get('/scanner', fn() => view('admin.scanner'))->name('scanner');
    Route::get('/update-log', fn() => view('admin.update'))->name('update-log');
});

// =====================
// GURU ROUTES
// =====================
Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('/home', fn() => view('guru.home'))->name('home');
    Route::get('/scanner', fn() => view('guru.scanner'))->name('scanner');
    Route::get('/siswa', fn() => view('guru.siswa'))->name('siswa');
});