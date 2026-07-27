# PR 0 — Baseline Audit dan Menu Regression

## Identitas baseline

- Repository: `C:\Herd\ppdb-azzahra-rebuild`
- Branch: `rebuild/spmb-v2`
- Commit baseline: `c48b9d1 chore(spmb): restore pre-PR1 baseline for rebuild`
- Kondisi awal worktree: bersih
- Tanggal audit: 27 Juli 2026
- Scope: audit dan regression lock; tidak ada perubahan workflow, route, controller, view, dependency, migration, atau schema.

## Ringkasan

Baseline memiliki tiga role (`parent`, `admin`, dan `super_admin`) yang menggunakan satu layout dashboard bersama, yaitu `resources/views/layouts/app.blade.php`. Super admin tidak memiliki dashboard/layout terpisah: role tersebut membuka `admin.dashboard` dan menerima seluruh akses admin melalui perilaku `RoleMiddleware`, kemudian memperoleh route tambahan yang dibatasi `role:super_admin`.

Audit menemukan 99 route terdaftar:

- public/guest/vendor: 15;
- authenticated shared: 13;
- parent: 14;
- admin: 46;
- super-admin-only: 11.

Tidak ada duplicate route name. Seluruh link sidebar utama memiliki named route GET yang valid dan seluruh halaman menu utama berhasil server-render dalam regression test.

## Matriks role

| Role | Dashboard | Sidebar/navigation | Halaman utama | Create/edit/delete | Authorization utama |
|---|---|---|---|---|---|
| Orang Tua/Wali (`parent`) | `parent.dashboard`, `/parent/dashboard`, closure → `parent.dashboard` | Dashboard; Data Anak/Lengkapi Data Anak; Daftar Gelombang; Status Pendaftaran; Profil dari topbar | Data anak, gelombang tersedia, status, informasi pembayaran, kartu, profil | CRUD satu data anak; memilih gelombang; unggah/unggah ulang bukti bayar; hapus akun bila password benar | `auth` + `role:parent`; ownership anak, pendaftaran, pembayaran, receipt, dan dokumen diperiksa lagi di controller |
| Admin (`admin`) | `admin.dashboard`, `/admin/dashboard`, `Admin\DashboardController@index` → `admin.dashboard` | Sembilan menu admin; profil dari topbar | Gelombang, siswa, verifikasi, pembayaran, konten, pengaturan | Kelola gelombang; input/soft-delete siswa; verifikasi; export; CRUD konten; update setting | `auth` + `role:admin`; tidak dapat membuka route berlapis `role:super_admin` |
| Super Admin (`super_admin`) | Route/view dashboard yang sama dengan admin | Seluruh menu admin + Kelola Admin + Activity Log | Seluruh halaman admin ditambah pengelolaan admin, activity log, restore/force-delete siswa | Seluruh aksi admin; CRUD/suspend admin; restore/force-delete siswa | `RoleMiddleware` secara eksplisit mengizinkan `super_admin` melewati `role:admin`, kemudian route eksklusif mewajibkan `role:super_admin` |

`RoleMiddleware` juga memaksa logout akun yang sedang disuspend. Route bersama `/dashboard` mengarahkan admin/super admin ke `admin.dashboard`, selain itu ke `parent.dashboard`.

## Matriks menu admin

Semua menu berikut berada pada sidebar desktop/shared layout. Kolom “Render” diverifikasi melalui feature test pada baseline kosong.

| Label menu | Route name / URL | Controller dan method | Blade view | Middleware | Terlihat oleh | Dapat diakses oleh | Render | Status |
|---|---|---|---|---|---|---|---|---|
| Dashboard | `admin.dashboard` — `/admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` | `web`, `auth`, `role:admin` | Admin, Super Admin | Admin, Super Admin | Ya | Aktif |
| Gelombang SPMB | `admin.pendaftaran.index` — `/admin/pendaftaran` | `Admin\PendaftaranManageController@index` | `admin.pendaftaran.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif; nama domain/controller “Pendaftaran”, label UI “Gelombang SPMB” |
| Data Siswa | `admin.siswa.index` — `/admin/siswa` | `Admin\SiswaController@index` | `admin.siswa.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif |
| Verifikasi Data | `admin.verifikasi.index` — `/admin/verifikasi` | `Admin\VerifikasiController@index` | `admin.verifikasi.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif |
| Rekap Pembayaran | `admin.pembayaran.index` — `/admin/pembayaran` | `Admin\PembayaranController@index` | `admin.pembayaran.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif |
| Konfigurasi Pembayaran | `admin.payment-settings.edit` — `/admin/payment-settings` | `Admin\PaymentSettingController@edit` | `admin.payment-settings.edit` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif; terpisah dari Pengaturan Situs |
| Kelola Testimoni | `admin.testimonials.index` — `/admin/testimonials` | `Admin\TestimonialController@index` | `admin.testimonials.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | CRUD aktif |
| Kelola Gallery | `admin.gallery.index` — `/admin/gallery` | `Admin\GalleryController@index` | `admin.gallery.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | CRUD aktif; ejaan label baseline menggunakan “Gallery” |
| Pengaturan Situs | `admin.settings.index` — `/admin/settings` | `Admin\SettingController@index` | `admin.settings.index` | sama | Admin, Super Admin | Admin, Super Admin | Ya | Aktif; bulk setting + CRUD prestasi |
| Kelola Admin | `admin.kelola-admin.index` — `/admin/kelola-admin` | `Admin\AdminManageController@index` | `admin.kelola-admin.index` | `web`, `auth`, `role:admin`, `role:super_admin` | Super Admin | Super Admin | Ya | Aktif; CRUD/suspend admin |
| Activity Log | `admin.activity-log.index` — `/admin/activity-log` | `Admin\AdminManageController@activityLogs` | `admin.activity-log.index` | sama | Super Admin | Super Admin | Ya | Aktif; read/filter/pagination |

### Fitur aksi per menu

| Area | Route/aksi baseline |
|---|---|
| Gelombang | index, create, store, edit, update, toggle status. Tidak ada delete route. |
| Data Siswa | index, create/store manual, show, soft-delete, trash, export; restore dan force-delete hanya Super Admin. Tidak ada edit/update siswa oleh admin. Form create admin menggunakan `parent.siswa.create` dengan mode/parameter admin. |
| Verifikasi | index/show, mulai verifikasi, terima, tolak, minta revisi, delete, verifikasi pembayaran, export. |
| Pembayaran | index dan export; keputusan pembayaran dilakukan melalui `admin.pembayaran.verify`. |
| Konfigurasi Pembayaran | edit/update singleton informasi bank, nominal, QRIS opsional, dan catatan. |
| Testimoni | index/create/store/edit/update/delete. |
| Galeri | index/create/store/edit/update/delete. |
| Pengaturan Situs | index/update setting; create/update/delete prestasi di halaman yang sama. |
| Kelola Admin | index/create/store/edit/update/suspend/unsuspend/delete. |
| Activity Log | index/filter/pagination; tidak ada mutation route dari halaman ini. |

Tidak ada menu utama yang hanya placeholder. Empty state Galeri/Testimoni di landing page adalah fallback ketika belum ada data, bukan fitur placeholder.

### Menu tersembunyi dan route tanpa menu

- Kelola Admin dan Activity Log sengaja tidak dirender untuk admin biasa.
- Search modal admin tidak memuat Gelombang SPMB atau Konfigurasi Pembayaran, walaupun keduanya tersedia pada sidebar.
- Route aksi/create/edit/show/export/toggle/verify tidak dibuat sebagai item sidebar terpisah; route tersebut dicapai dari halaman indeks/detail.
- `admin.siswa.trash` tidak menjadi menu sidebar, tetapi ditautkan dari halaman Data Siswa.
- Parent tidak memiliki menu sidebar khusus pembayaran, kartu, receipt, atau profil; halaman tersebut dicapai dari status/dashboard/topbar.
- Ketika parent sudah memiliki data anak, sidebar berubah ke “Data Anak”, tetapi search modal tetap menampilkan “Lengkapi Data Anak” yang menuju `parent.siswa.create`. Ini inkonsistensi baseline non-blocking.

## Audit khusus Pengaturan Situs

1. Menu **Pengaturan Situs ada** pada baseline.
2. Menu terlihat oleh Admin dan Super Admin pada sidebar dan search modal.
3. Route yang digunakan:
   - `GET admin.settings.index` — `/admin/settings`;
   - `PUT admin.settings.update` — `/admin/settings`;
   - `POST admin.settings.achievements.store`;
   - `PUT admin.settings.achievements.update`;
   - `DELETE admin.settings.achievements.destroy`.
4. Halaman dapat dibuka oleh Admin dan Super Admin; Parent menerima 403.
5. Implementasi tersimpan dan koneksinya:
   - model: `App\Models\Setting`;
   - migration: `2026_05_11_090002_create_settings_table.php`;
   - seeder: `Database\Seeders\SettingSeeder`;
   - controller: `Admin\SettingController`;
   - view: `admin.settings.index`;
   - landing, halaman syarat, dan privasi membaca `Setting`;
   - `ActivityLogObserver` mengamati perubahan `Setting`.
6. Nama UI “Pengaturan Situs” berbeda dari nama teknis generik `settings`, `SettingController`, dan `Setting`.
7. `SettingController@update` menerima array `settings` dari `Request` dan hanya meng-update key yang sudah ada. Tidak ada Form Request atau aturan validasi khusus untuk bulk site settings.
8. `SettingSeeder` tersedia, tetapi `DatabaseSeeder` baseline hanya memanggil `AdminSeeder`. Karena itu `migrate:fresh --seed` tidak mengisi setting landing dan tidak membuat Super Admin melalui `SuperAdminSeeder`; halaman tetap render dengan koleksi kosong/fallback.
9. Sebelum PR 0 belum ada test khusus Pengaturan Situs. `BaselineRegressionTest` sekarang mengunci visibility, route, render, dan authorization-nya.

Konfigurasi Pembayaran bukan alias Pengaturan Situs. Fitur tersebut memakai model/migration/request/controller/view tersendiri: `PaymentSetting`, `create_payment_settings_table`, `UpdatePaymentSettingRequest`, `PaymentSettingController`, dan `admin.payment-settings.edit`.

## Matriks route

### Public dan guest (15)

| Keluarga route | Nama penting | Keterangan |
|---|---|---|
| Landing/legal | `home`, `terms`, `privacy` | Public closure yang membaca setting; landing juga membaca testimoni, prestasi, galeri |
| Media public terkontrol | `achievements.image`, `galleries.image` | Invokable controller; hanya media aktif yang dapat diambil |
| Auth guest | `login`, `register`, `password.request`, `password.email`, `password.reset`, `password.store` | `guest`; POST login/register memakai throttle |
| Framework/vendor | `/up`, `_boost/browser-logs` | Tidak memakai middleware aplikasi; bukan menu/halaman bisnis |

### Authenticated shared (13)

| Keluarga route | Nama penting | Keterangan |
|---|---|---|
| Redirect dashboard | `dashboard` | Redirect berdasarkan role |
| Profile | `profile.edit`, `profile.update`, `password.update`, `profile.destroy` | Semua role; admin tidak boleh menghapus akun lewat profil |
| Session/notification | `logout`, `notifications.markAllRead` | Semua user login |
| Email/password confirmation | `verification.notice`, `verification.verify`, `verification.send`, `password.confirm` | Auth; signed/throttled saat relevan |
| Dokumen privat | `dokumen.show` | `role:parent,admin,super_admin`; controller menegakkan ownership dan whitelist field |

### Parent (14)

| Area | Route names |
|---|---|
| Dashboard | `parent.dashboard` |
| Siswa | `parent.siswa.create`, `store`, `show`, `edit`, `update`, `destroy`, `kartu` |
| Pendaftaran | `parent.pendaftaran.index`, `show`, `daftar`, `status` |
| Pembayaran | `parent.pembayaran.store`, `receipt` |

Seluruh route parent memakai `web`, `auth`, `role:parent`.

### Admin (46)

| Area | Route names |
|---|---|
| Dashboard | `admin.dashboard` |
| Gelombang | `admin.pendaftaran.index`, `create`, `store`, `edit`, `update`, `toggle` |
| Siswa | `admin.siswa.index`, `create`, `store`, `show`, `destroy`, `trash`, `export` |
| Verifikasi | `admin.verifikasi.index`, `show`, `start`, `terima`, `tolak`, `revisi`, `destroy`, `export` |
| Pembayaran | `admin.pembayaran.index`, `export`, `verify` |
| Konfigurasi pembayaran | `admin.payment-settings.edit`, `update` |
| Pengaturan situs/prestasi | `admin.settings.index`, `update`, `achievements.store`, `achievements.update`, `achievements.destroy` |
| Testimoni | resource index/create/store/edit/update/destroy, serta route `show` bermasalah yang dicatat di temuan |
| Galeri | resource index/create/store/edit/update/destroy, serta route `show` bermasalah yang dicatat di temuan |

Seluruh route memakai `web`, `auth`, `role:admin`; `RoleMiddleware` membuatnya juga dapat diakses Super Admin.

### Super Admin (11)

| Area | Route names |
|---|---|
| Kelola Admin | `admin.kelola-admin.index`, `create`, `store`, `edit`, `update`, `suspend`, `unsuspend`, `destroy` |
| Activity Log | `admin.activity-log.index` |
| Recovery siswa | `admin.siswa.restore`, `admin.siswa.force-delete` |

Route ini berada di dalam group admin dan group super admin, sehingga middleware efektifnya adalah `web`, `auth`, `role:admin`, `role:super_admin`.

## Workflow baseline

### Orang Tua/Wali

1. Registrasi akun melalui `/register`; `RegisterRequest` mewajibkan persetujuan syarat dan privasi. User dibuat dengan role `parent`, langsung login, lalu diarahkan ke dashboard.
2. Login memakai email/password, throttle, regenerasi session, serta penolakan akun suspended.
3. Parent mengisi satu record data anak (`User::siswa()` adalah `HasOne`) beserta foto publik dan dokumen KK/akta privat.
4. Parent melihat gelombang yang dapat dipilih. Sebelum mendaftar, parent harus menyatakan kebenaran data.
5. Transaksi pendaftaran memeriksa status gelombang, tanggal, kuota, penerimaan sebelumnya, pendaftaran aktif di gelombang lain, dan duplikasi. Record baru dibuat dengan status `pending` dan nomor `SPMB-{tahun}-{id}`.
6. Status detail yang dikenali baseline: `pending`, `menunggu_verifikasi`, `perlu_revisi`, `diterima`, `ditolak`.
7. Jika admin meminta revisi, update data anak mengembalikan status `perlu_revisi` menjadi `menunggu_verifikasi`.
8. Setelah status `diterima`, parent dapat mengunggah bukti pembayaran sesuai nominal `PaymentSetting`. Status pembayaran menjadi `menunggu_verifikasi`; bukti lama diganti saat re-upload, kecuali pembayaran sudah `lunas`.
9. Status pembayaran baseline: `pending`, `menunggu_verifikasi`, `lunas`, `ditolak`.
10. Receipt tersedia melalui route ber-ownership. Kartu pendaftaran dapat dicetak setelah pendaftaran `diterima`; kartu tidak mensyaratkan status pembayaran `lunas`.
11. Profil tersedia bagi semua role. Parent dapat menghapus akun setelah konfirmasi password; admin/super admin ditolak oleh controller pada aksi hapus profil.

### Admin

1. Mengelola gelombang: membuat, mengubah, membuka/menutup, dan memantau kuota.
2. Melihat data pendaftar dan data induk siswa; dapat menambah siswa secara manual, melihat detail, soft-delete, membuka trash, dan export.
3. Memproses verifikasi dari pending/menunggu menjadi diterima, ditolak, atau perlu revisi; notifikasi dan activity log mengikuti aksi controller.
4. Melihat rekap pembayaran, memverifikasi menjadi lunas/ditolak, dan export.
5. Export siswa, verifikasi, pembayaran mendukung XLSX, CSV, dan PDF bila DomPDF tersedia.
6. Mengelola Testimoni, Galeri, Pengaturan Situs/Prestasi, dan Konfigurasi Pembayaran.

### Super Admin

1. Menggunakan seluruh route dan dashboard admin.
2. Melihat statistik/log tambahan pada dashboard bila data tersedia.
3. Membuat, mengubah, suspend/unsuspend, dan menghapus akun admin dengan guard yang ada di `AdminManageController`.
4. Membuka Activity Log.
5. Restore dan force-delete siswa dari trash; controller kembali memverifikasi `isSuperAdmin()` dan menolak force-delete bila masih ada riwayat pendaftaran/pembayaran.

## Layout, sidebar, topbar, dark mode, dan responsive

- Parent, Admin, dan Super Admin menggunakan `layouts.app`; tidak ada layout super admin terpisah.
- Sidebar desktop memakai breakpoint `md`; mobile memakai tombol, overlay, dan close button.
- Main content adalah scroll surface tersendiri (`#mainScrollArea`).
- Topbar memuat search modal, theme selector light/dark/system, notifikasi untuk parent, profil, dan logout.
- Active state memakai `request()->routeIs(...)` per keluarga route. `parent.pendaftaran.status` diperiksa terpisah dari index/show.
- Dark mode diterapkan melalui class `dark:` dan pilihan disimpan di `localStorage`.
- Audit server-render dan build tidak menemukan error Blade yang memblokir. PR 0 tidak melakukan redesign atau visual mutation.

## Temuan baseline dan koneksi yang hilang

### Route tersedia tetapi halaman/controller action tidak ada

1. `admin.gallery.show` (`GET /admin/gallery/{gallery}`) dibuat otomatis oleh `Route::resource`, tetapi `GalleryController` tidak memiliki method `show`.
2. `admin.testimonials.show` (`GET /admin/testimonials/{testimonial}`) dibuat otomatis oleh `Route::resource`, tetapi `TestimonialController` tidak memiliki method `show`.

Keduanya tidak ditautkan dari sidebar/index baseline, tetapi request langsung ke route tersebut akan gagal karena controller method tidak tersedia. PR 0 tidak mengubah route ini agar baseline tetap murni.

### Controller method tersedia tetapi tidak memiliki route

1. `Admin\VerifikasiController@bulkUpdate`.
2. `AuthController@changePassword`.

Pergantian password yang benar-benar terhubung saat ini adalah `ProfileController@updatePassword` melalui `password.update`.

### Inkonsistensi lain yang perlu dijaga/diputuskan pada PR berikutnya

- Search modal parent tidak mengikuti state data anak dan tetap menuju form create.
- `SettingSeeder` dan `SuperAdminSeeder` tidak dipanggil `DatabaseSeeder`.
- Flash sukses pendaftaran menyebut “menunggu verifikasi”, sedangkan record baru dibuat sebagai `pending`; transisi ke `menunggu_verifikasi` dilakukan lewat aksi admin `startVerifikasi`.
- Route `admin.siswa.trash` dapat dibuka admin biasa, tetapi tombol restore/force-delete hanya muncul untuk Super Admin dan action-nya juga dilindungi middleware/controller.
- Route resource Gallery/Testimoni menghasilkan route `show` yang tidak dipakai dan rusak sebagaimana dijelaskan di atas.

## Fitur baseline yang wajib dipertahankan

- Semua label dan destination sidebar sesuai matriks.
- Redirect dashboard berbasis role.
- Single-child relationship dan ownership checks baseline.
- Status pendaftaran dan pembayaran beserta transisinya.
- Consent registrasi dan deklarasi kebenaran data.
- Dokumen privat serta whitelist aksesnya.
- Gelombang/kuota dan pembatasan satu pendaftaran aktif.
- Export XLSX/CSV/PDF.
- Galeri, Testimoni, Prestasi, Pengaturan Situs, dan Konfigurasi Pembayaran.
- QRIS opsional serta fallback informasi pembayaran.
- Kelola Admin, suspend, Activity Log, restore/force-delete khusus Super Admin.
- Kartu dan receipt baseline.
- Profil, email verification, reset/confirm password, notifikasi, dark mode, dan responsive layout.

## Regression test PR 0

File: `tests/Feature/BaselineRegressionTest.php`.

Coverage:

- landing page;
- dashboard Parent, Admin, Super Admin;
- sembilan menu admin beserta href;
- dua menu Super Admin beserta href;
- sidebar parent sebelum dan sesudah data anak;
- visibility/render/authorization Pengaturan Situs;
- render Galeri, Testimoni, Konfigurasi Pembayaran;
- parent tidak dapat membuka halaman admin;
- admin biasa tidak dapat membuka halaman Super Admin;
- Super Admin dapat membuka Kelola Admin dan Activity Log;
- seluruh named route sidebar terdaftar sebagai GET dengan URI baseline yang tepat.

Assertion menggunakan route, status authorization, href, dan konten semantik; tidak mengunci class CSS dekoratif.

## Risiko untuk PR 1

1. Rebuild route group berisiko menghilangkan fitur konten dan settings karena tidak berada di workflow pendaftaran utama.
2. Memisahkan layout per role dapat menghilangkan menu/topbar/profile/theme/notification yang saat ini berada pada satu layout.
3. Mengubah model Parent/Siswa dapat merusak asumsi `HasOne`, ownership checks, kartu, receipt, dan query dashboard baseline.
4. Mengganti enum/status tanpa compatibility mapping dapat merusak halaman status, filter admin, notifikasi, pembayaran, dan test.
5. Merapikan resource route secara tidak sengaja dapat menyembunyikan fakta dua route `show` rusak; putuskan eksplisit pada PR tersendiri.
6. Mengandalkan `DatabaseSeeder` untuk demo akan menghasilkan hanya akun Admin dan tidak mengisi setting/super admin.
7. Memindahkan pembayaran ke settings umum dapat mencampur dua fitur yang baseline-nya terpisah.
8. Perubahan route order harus menjaga route statis seperti `create`, `trash`, `export`, dan `kartu` sebelum parameter dinamis.

## Checklist regression setiap PR

- [ ] Branch tetap sesuai rencana rebuild dan tidak ada perubahan di luar scope.
- [ ] Tidak ada menu baseline hilang untuk role yang berhak.
- [ ] Parent tidak melihat/mengakses menu admin.
- [ ] Admin biasa tidak mengakses fitur Super Admin.
- [ ] Semua named route sidebar tetap terdaftar dan render.
- [ ] Landing, legal, login, register, dan profile tetap render.
- [ ] Pengaturan Situs, Galeri, Testimoni, Prestasi, dan Konfigurasi Pembayaran tetap ada.
- [ ] Status pendaftaran/pembayaran dan authorization ownership tidak berubah tanpa migration plan.
- [ ] Export, dokumen privat, kartu, receipt, dan notifikasi tetap bekerja.
- [ ] Tidak ada duplicate route name atau controller/view baru yang tidak terhubung.
- [ ] Jalankan `php artisan optimize:clear`.
- [ ] Jalankan `php artisan migrate:fresh --seed`.
- [ ] Jalankan `php artisan test`.
- [ ] Jalankan `php artisan view:cache`.
- [ ] Jalankan `npm run build` (gunakan `npm.cmd run build` bila PowerShell memblokir shim).
- [ ] Jalankan `git diff --check`.
- [ ] Audit ulang `php artisan route:list`.
- [ ] Pastikan tidak ada migration/schema/dependency yang berubah kecuali memang menjadi scope PR.
