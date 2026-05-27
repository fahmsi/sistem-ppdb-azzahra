# 🎓 Sistem Informasi PPDB Az-Zahra

Sistem Informasi Penerimaan Peserta Didik Baru (PPDB) berbasis website yang dirancang khusus untuk mengelola proses pendaftaran, seleksi, dan administrasi Peserta Didik Baru di lembaga pendidikan PAUD Az-Zahra Depok. 

Proyek ini dikembangkan dengan pendekatan metodologi **Agile** dan dievaluasi melalui mekanisme **User Acceptance Testing (UAT)** dengan metode **Alpha** dan **Beta Testing** untuk memastikan sistem berjalan sesuai dengan kebutuhan end-user, sekaligus sebagai pemenuhan luaran Tugas Akhir Skripsi Berdampak Program Studi Sistem Informasi Sekolah Tinggi Teknologi Terpadu Nurul Fikri.

## 🛠️ Tumpukan Teknologi (Tech Stack)
* **Backend:** Laravel
* **Frontend UI:** Sneat Admin Template (Tailwind CSS)
* **Database:** MySQL
* **Mail Service:** Resend SMTP
* **Design Tools:** Figma

## ✨ Fitur Utama
* **Autentikasi Aman:** Dilengkapi dengan validasi kata sandi *real-time*, fitur lihat kata sandi, dan proteksi middleware tingkat tinggi.
* **Manajemen Profil:** Antarmuka pembaruan data diri yang responsif dan intuitif.
* **Notifikasi Email:** Integrasi pengiriman email otomatis dan aman untuk kebutuhan pendaftaran serta pemulihan kata sandi (Reset Password).
* **Desain Modern:** Pendekatan UI/UX yang bersih dan terstruktur untuk memudahkan navigasi orang tua/wali murid maupun admin.

## 🚀 Cara Menjalankan Proyek (Lokal)
1. Clone repositori ini: `git clone https://github.com/fahmsi/sistem-ppdb-azzahra.git`
2. Masuk ke direktori proyek: `cd sistem-ppdb-azzahra`
3. Jalankan `composer install`
4. Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database dan mailer (Resend).
5. Jalankan `php artisan key:generate`
6. Jalankan migrasi: `php artisan migrate`
7. Nyalakan server: `php artisan serve`