<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\LayananController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\HistoryController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Auth\UserPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Halaman awal -> redirect ke login user
Route::get('/', fn() => redirect()->route('user.dashboard'));

// ====================== AUTH ======================
// User Login
Route::get('/login/user', [LoginController::class, 'showUserLoginForm'])->name('user.login.form');
Route::post('/login/user', [LoginController::class, 'userLogin'])->name('user.login');

//  Alias bawaan Laravel biar middleware auth gak error
Route::get('/login', [LoginController::class, 'showUserLoginForm'])->name('login');


// Admin Login
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login.form');
Route::post('/login/admin', [LoginController::class, 'adminLogin'])->name('admin.login');

// Register (untuk user)
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// ====================== PASSWORD RESET VIA OTP ======================
Route::get('/forgot-password', [UserPasswordController::class, 'forgotPasswordForm'])->name('user.password.request');
Route::post('/forgot-password', [UserPasswordController::class, 'sendOtp'])->name('user.password.sendOtp');

Route::get('/verify-otp', [UserPasswordController::class, 'verifyOtpForm'])->name('user.password.verifyOtpForm');
Route::post('/verify-otp', [UserPasswordController::class, 'verifyOtp'])->name('user.password.verifyOtp');

Route::get('/reset-password', [UserPasswordController::class, 'resetPasswordForm'])->name('user.password.resetForm');
Route::post('/reset-password', [UserPasswordController::class, 'resetPassword'])->name('user.password.reset');

// ====================== USER ======================
// Halaman publik (guest boleh akses)
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // publik
});

// Hanya user login
Route::middleware(['auth', 'isUser'])->prefix('user')->name('user.')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('edit-profile');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Ganti Password
    Route::get('/profile/password', [UserPasswordController::class, 'edit'])->name('edit-password');
    Route::put('/profile/password/update', [UserPasswordController::class, 'update'])->name('password.update');

    // Booking
    Route::get('/booking', [BookingController::class, 'booking'])->name('booking');
    Route::get('/booking/create', [BookingController::class, 'createBooking'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'storeBooking'])->name('booking.store');
    Route::get('/booking/{id}/edit', [BookingController::class, 'editBooking'])->name('booking.edit');
    Route::put('/booking/{id}/update', [BookingController::class, 'updateBooking'])->name('booking.update');
    Route::get('/booking/{id}/print', [HistoryController::class, 'printBookingDetail'])->name('booking.print');
    Route::post('/booking/{id}/selesai', [HistoryController::class, 'selesai'])->name('booking.selesai');
    Route::delete('/booking/{id}', [BookingController::class, 'destroyBooking'])->name('booking.destroy');

    // History
    Route::get('/history', [HistoryController::class, 'history'])->name('history');
});

// ====================== ADMIN ======================
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Customer Management
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Transaksi Management
    Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}', [\App\Http\Controllers\Admin\TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [\App\Http\Controllers\Admin\TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/{id}/print', [\App\Http\Controllers\Admin\TransaksiController::class, 'print'])->name('transaksi.print');

    // Layanan Management
    Route::resource('layanan', AdminLayananController::class)->except(['show']);

    // Barang Management
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class)->except(['show']);
    Route::get('/barang/export/pdf', [App\Http\Controllers\Admin\BarangController::class, 'exportPdf'])
    ->name('barang-pdf');


    // Petugas Management
    Route::resource('petugas', \App\Http\Controllers\Admin\PetugasController::class)->except(['show']);
    Route::post('/petugas/{id}/toggle-status', [\App\Http\Controllers\Admin\PetugasController::class, 'toggleStatus'])
        ->name('petugas.toggle-status');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pesanan', [LaporanController::class, 'pesanan'])->name('laporan.pesanan');
    Route::get('/laporan/detail', [LaporanController::class, 'detail'])->name('laporan.detail');
    //  export Excel & PDF
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/export/daily', [LaporanController::class, 'exportExcelHarian'])->name('laporan.export.daily');
Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});

// Logout
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    $redirect = 'user.login.form';
    if (Auth::check() && Auth::user()->role === 'admin') {
        $redirect = 'admin.login.form';
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route($redirect)->with('success', 'Anda berhasil logout.');
})->name('logout');

// Test Mail
Route::get('/test-mail', function () {
    Mail::raw('Ini email test Laravel SMTP', function ($message) {
        $message->to('qubabarbershoptesting@gmail.com')
                ->subject('Test Email Laravel');
    });
    return 'Email dikirim!';
});
