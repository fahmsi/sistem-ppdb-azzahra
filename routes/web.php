<?php

use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PendaftaranManageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AchievementImageController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Models\Setting;
use App\Models\Achievement;
use App\Models\Gallery;
use App\Models\PaymentSetting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $settings = Setting::pluck('value', 'key')->toArray();
    $testimonials = Testimonial::latest()->get();
    $achievements = Achievement::where('is_active', true)
        ->orderBy('sort_order')
        ->orderByDesc('achievement_year')
        ->get();
    $galleries = Gallery::where('is_active', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    return view('welcome', compact('settings', 'testimonials', 'achievements', 'galleries'));
})->name('home');

Route::get('/syarat-ketentuan', function () {
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('app.legal.terms', compact('settings'));
})->name('terms');

Route::get('/kebijakan-privasi', function () {
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('app.legal.privacy', compact('settings'));
})->name('privacy');

Route::get('/prestasi/{achievement}/gambar', AchievementImageController::class)
    ->name('achievements.image');

Route::get('/gallery/{gallery}/gambar', GalleryImageController::class)
    ->name('galleries.image');

/*
|--------------------------------------------------------------------------
| Guest Routes (unauthenticated only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'parent.dashboard');
    })->name('dashboard');

    // Secure Document Viewer
    Route::get('/siswa/{siswa}/dokumen/{field}/{pembayaran?}', [DokumenController::class, 'show'])
        ->middleware('role:parent,admin,super_admin')
        ->name('dokumen.show');

    /*
    |----------------------------------------------------------------------
    | Parent Routes (role: parent)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', function () {
            return view('parent.dashboard', [
                'paymentSetting' => PaymentSetting::current(),
            ]);
        })->name('dashboard');

        // Siswa (child data) management
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/kartu', [SiswaController::class, 'kartu'])->name('siswa.kartu');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Registration (pendaftaran)
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
        Route::post('/pendaftaran/{pendaftaran}/daftar', [PendaftaranController::class, 'daftar'])->name('pendaftaran.daftar');
        Route::get('/status', [PendaftaranController::class, 'status'])->name('pendaftaran.status');

        // Pembayaran
        Route::post('/pembayaran/{detail}', [App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('/pembayaran/{detail}/receipt', [App\Http\Controllers\PembayaranController::class, 'receipt'])->name('pembayaran.receipt');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Routes (role: admin — super_admin also has access via middleware)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Pendaftaran period management
        Route::get('/pendaftaran', [PendaftaranManageController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/create', [PendaftaranManageController::class, 'create'])->name('pendaftaran.create');
        Route::post('/pendaftaran', [PendaftaranManageController::class, 'store'])->name('pendaftaran.store');
        Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranManageController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/pendaftaran/{pendaftaran}', [PendaftaranManageController::class, 'update'])->name('pendaftaran.update');
        Route::patch('/pendaftaran/{pendaftaran}/toggle', [PendaftaranManageController::class, 'toggleStatus'])->name('pendaftaran.toggle');

        // Verifikasi (registration review & verification)
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        // Route Export Verifikasi (define before parameterized routes to avoid conflicts)
        Route::get('/verifikasi/export', [VerifikasiController::class, 'export'])->name('verifikasi.export');
        Route::get('/verifikasi/{detail}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{detail}/start', [VerifikasiController::class, 'startVerifikasi'])->name('verifikasi.start');
        Route::patch('/verifikasi/{detail}/terima', [VerifikasiController::class, 'terima'])->name('verifikasi.terima');
        Route::patch('/verifikasi/{detail}/tolak', [VerifikasiController::class, 'tolak'])->name('verifikasi.tolak');
        Route::patch('/verifikasi/{detail}/revisi', [VerifikasiController::class, 'revisi'])->name('verifikasi.revisi');
        Route::delete('/verifikasi/{detail}', [VerifikasiController::class, 'destroy'])->name('verifikasi.destroy');
        Route::patch('/pembayaran/{pembayaran}/verify', [VerifikasiController::class, 'verifyPembayaran'])->name('pembayaran.verify');

        // Route Export Siswa (Letakkan sebelum resource siswa)
        Route::get('/siswa/create', [App\Http\Controllers\Admin\SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [App\Http\Controllers\Admin\SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/trash', [App\Http\Controllers\Admin\SiswaController::class, 'trash'])->name('siswa.trash');
        Route::get('/siswa/export', [App\Http\Controllers\Admin\SiswaController::class, 'export'])->name('siswa.export');
        Route::delete('/siswa/{siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::resource('/siswa', App\Http\Controllers\Admin\SiswaController::class)->only(['index', 'show']);

        // Route Export Pembayaran
        Route::get('/pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');

        // Payment information shown to parents
        Route::get('/payment-settings', [PaymentSettingController::class, 'edit'])->name('payment-settings.edit');
        Route::put('/payment-settings', [PaymentSettingController::class, 'update'])->name('payment-settings.update');

        // Settings (admin & super_admin)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/achievements', [AchievementController::class, 'store'])->name('settings.achievements.store');
        Route::put('/settings/achievements/{achievement}', [AchievementController::class, 'update'])->name('settings.achievements.update');
        Route::delete('/settings/achievements/{achievement}', [AchievementController::class, 'destroy'])->name('settings.achievements.destroy');

        Route::resource('testimonials', TestimonialController::class);

        // Gallery CRUD
        Route::resource('gallery', GalleryController::class);
        // ... rute create, store, dll ...

        /*
        |------------------------------------------------------------------
        | Super Admin Only Routes
        |------------------------------------------------------------------
        */
        Route::middleware('role:super_admin')->group(function () {
            // Kelola Admin (Admin Management CRUD)
            Route::get('/kelola-admin', [AdminManageController::class, 'index'])->name('kelola-admin.index');
            Route::get('/kelola-admin/create', [AdminManageController::class, 'create'])->name('kelola-admin.create');
            Route::post('/kelola-admin', [AdminManageController::class, 'store'])->name('kelola-admin.store');
            Route::get('/kelola-admin/{user}/edit', [AdminManageController::class, 'edit'])->name('kelola-admin.edit');
            Route::put('/kelola-admin/{user}', [AdminManageController::class, 'update'])->name('kelola-admin.update');
            Route::patch('/kelola-admin/{user}/suspend', [AdminManageController::class, 'suspend'])->name('kelola-admin.suspend');
            Route::patch('/kelola-admin/{user}/unsuspend', [AdminManageController::class, 'unsuspend'])->name('kelola-admin.unsuspend');
            Route::delete('/kelola-admin/{user}', [AdminManageController::class, 'destroy'])->name('kelola-admin.destroy');

            // Activity Logs
            Route::get('/activity-log', [AdminManageController::class, 'activityLogs'])->name('activity-log.index');

            Route::patch('/siswa/{id}/restore', [App\Http\Controllers\Admin\SiswaController::class, 'restore'])->name('siswa.restore');
            Route::delete('/siswa/{id}/force', [App\Http\Controllers\Admin\SiswaController::class, 'forceDelete'])->name('siswa.force-delete');
        });
    });
});
