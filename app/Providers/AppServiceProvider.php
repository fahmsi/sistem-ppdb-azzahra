<?php

namespace App\Providers;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register ActivityLog observers on key models
        User::observe(ActivityLogObserver::class);
        Siswa::observe(ActivityLogObserver::class);
        Pendaftaran::observe(ActivityLogObserver::class);
        PendaftaranDetail::observe(ActivityLogObserver::class);
        Pembayaran::observe(ActivityLogObserver::class);
        Setting::observe(ActivityLogObserver::class);

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
        return (new MailMessage)
            ->subject('Pemberitahuan Reset Password')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('Tautan reset password ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.');
    });
    }
}
