@extends('layouts.app')

@section('title', 'Detail & Verifikasi Pendaftaran')
@section('header_title', 'Verifikasi Dokumen Pendaftar')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Back Button & Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Status Saat Ini:</span>
            @if($detail->status === 'pending')
                <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb] border border-[#d9dee3] dark:border-[#434463]">Pending</span>
            @elseif($detail->status === 'menunggu_verifikasi')
                <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">Menunggu Verifikasi</span>
            @elseif($detail->status === 'diterima')
                <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Diterima</span>
            @elseif($detail->status === 'ditolak')
                <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">Ditolak</span>
            @elseif($detail->status === 'perlu_revisi')
                <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20">Perlu Revisi</span>
            @endif
        </div>
    </div>

    <!-- Split View Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Data & Dokumen (Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Profil Anak -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                <div class="bg-[#e7e7ff] dark:bg-[#696cff]/20 px-6 py-4 border-b border-[#d9dee3] dark:border-[#434463] flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[#696cff]"></i>
                    <h3 class="font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Profil Calon Siswa</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-6 mb-6">
                        <img src="{{ $detail->siswa->foto ? Storage::url($detail->siswa->foto) : asset('images/default-avatar.png') }}" class="w-24 h-32 object-cover rounded-lg border border-[#d9dee3] dark:border-[#434463] shadow-sm" alt="Foto Siswa">
                        <div class="flex-1 space-y-2">
                            <div>
                                <p class="text-xs text-[#a1b0cb] uppercase tracking-wider">Nama Lengkap</p>
                                <p class="font-bold text-[#566a7f] dark:text-[#d5d5e2] text-lg">{{ $detail->siswa->nama ?? '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-[#a1b0cb] uppercase">Jenis Kelamin</p>
                                    <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-[#a1b0cb] uppercase">Tempat, Tgl Lahir</p>
                                    <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->tempat_lahir }}, {{ $detail->siswa->tanggal_lahir }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 pt-4 border-t border-[#d9dee3] dark:border-[#434463] text-sm">
                        <div>
                            <p class="text-[#a1b0cb] mb-1">NIK Ayah / Nama Ayah</p>
                            <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->nik_ayah ?? '-' }} / {{ $detail->siswa->nama_ayah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[#a1b0cb] mb-1">NIK Ibu / Nama Ibu</p>
                            <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->nik_ibu ?? '-' }} / {{ $detail->siswa->nama_ibu ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[#a1b0cb] mb-1">Alamat Domisili</p>
                            <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->alamat }}, {{ $detail->siswa->kelurahan }}, {{ $detail->siswa->kecamatan }}, {{ $detail->siswa->kota }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pratinjau Dokumen -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                <div class="bg-[#e7e7ff] dark:bg-[#696cff]/20 px-6 py-4 border-b border-[#d9dee3] dark:border-[#434463] flex items-center gap-2">
                    <i data-lucide="folder-open" class="w-5 h-5 text-[#696cff]"></i>
                    <h3 class="font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Berkas Lampiran</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- KK -->
                    <div class="border border-[#d9dee3] dark:border-[#434463] rounded-lg p-4 flex flex-col items-center text-center hover:border-[#696cff] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors">
                        <i data-lucide="file-text" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                        <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Kartu Keluarga (KK)</h4>
                        <p class="text-xs text-[#a1b0cb] mb-4">No: {{ $detail->siswa->no_kk ?? '-' }}</p>
                        <a href="{{ $detail->siswa->foto_kk ? route('dokumen.show', ['path' => $detail->siswa->foto_kk]) : '#' }}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 px-3 py-2 text-sm font-medium text-[#696cff] bg-[#e7e7ff] dark:bg-[#696cff]/20 hover:bg-[#d4d5ff] dark:hover:bg-[#696cff]/30 rounded-md transition-colors">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Lihat Dokumen
                        </a>
                    </div>

                    <!-- Akta -->
                    <div class="border border-[#d9dee3] dark:border-[#434463] rounded-lg p-4 flex flex-col items-center text-center hover:border-[#696cff] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors">
                        <i data-lucide="file-badge-2" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                        <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Akta Kelahiran</h4>
                        <p class="text-xs text-[#a1b0cb] mb-4">Pastikan terbaca jelas</p>
                        <a href="{{ $detail->siswa->foto_akta ? route('dokumen.show', ['path' => $detail->siswa->foto_akta]) : '#' }}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 px-3 py-2 text-sm font-medium text-[#696cff] bg-[#e7e7ff] dark:bg-[#696cff]/20 hover:bg-[#d4d5ff] dark:hover:bg-[#696cff]/30 rounded-md transition-colors">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Lihat Dokumen
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pembayaran / Daftar Ulang -->
            @if($detail->pembayaran)
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden mt-6">
                <div class="bg-blue-50 dark:bg-blue-500/10 px-6 py-4 border-b border-blue-100 dark:border-blue-500/20 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        <h3 class="font-heading font-semibold text-blue-900 dark:text-blue-300">Bukti Daftar Ulang (Pembayaran)</h3>
                    </div>
                    @if($detail->pembayaran->status === 'lunas')
                        <span class="px-3 py-1 bg-secondary-100 text-secondary-800 text-xs font-bold rounded-full">LUNAS</span>
                    @elseif($detail->pembayaran->status === 'ditolak')
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">DITOLAK</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">PERLU VERIFIKASI</span>
                    @endif
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-[#a1b0cb] mb-1">Nominal Ditransfer</p>
                        <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Rp {{ number_format($detail->pembayaran->jumlah, 0, ',', '.') }}</p>
                        
                        <a href="{{ route('dokumen.show', ['path' => $detail->pembayaran->bukti_bayar]) }}" target="_blank" class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-[#e7e7ff] dark:hover:bg-[#696cff]/10 text-[#566a7f] dark:text-[#d5d5e2] text-sm font-medium rounded-lg transition-colors border border-[#d9dee3] dark:border-[#434463]">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Lihat Foto Bukti
                        </a>
                        
                        @if($detail->pembayaran->catatan_admin)
                        <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg">
                            <p class="text-xs text-red-800 font-semibold mb-1">Catatan Admin Sebelumnya:</p>
                            <p class="text-sm text-red-700">{{ $detail->pembayaran->catatan_admin }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="border-l border-[#d9dee3] dark:border-[#434463] pl-0 md:pl-6">
                        <form action="{{ route('admin.pembayaran.verify', $detail->pembayaran->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Tindakan</label>
                                <select name="status" id="paymentStatusSelect" class="sneat-input" onchange="togglePaymentNote()">
                                    <option value="">-- Pilih Keputusan --</option>
                                    <option value="lunas" {{ $detail->pembayaran->status === 'lunas' ? 'selected' : '' }}>Terima (Lunas)</option>
                                    <option value="ditolak" {{ $detail->pembayaran->status === 'ditolak' ? 'selected' : '' }}>Tolak (Revisi)</option>
                                </select>
                            </div>

                            <div id="paymentNoteContainer" class="{{ $detail->pembayaran->status === 'ditolak' ? 'block' : 'hidden' }}">
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Catatan Penolakan</label>
                                <textarea name="catatan_admin" rows="3" class="sneat-input" placeholder="Contoh: Bukti buram, nominal tidak sesuai...">{{ $detail->pembayaran->catatan_admin }}</textarea>
                            </div>

                            <button type="submit" class="w-full sneat-btn-primary justify-center py-2.5">
                                <i data-lucide="save" class="w-4 h-4"></i> Simpan Verifikasi Bayar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <script>
                function togglePaymentNote() {
                    const status = document.getElementById('paymentStatusSelect').value;
                    const container = document.getElementById('paymentNoteContainer');
                    if (status === 'ditolak') {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                }
            </script>
            @endif
        </div>

        <!-- Right Column: Aksi Verifikasi -->
        <div class="space-y-6 relative">
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden sticky top-6">
                <div class="bg-[#2b2c40] dark:bg-[#232333] px-6 py-4 border-b border-[#434463] flex items-center gap-2">
                    <i data-lucide="check-square" class="w-5 h-5 text-[#a1b0cb]"></i>
                    <h3 class="font-heading font-semibold text-white">Aksi Verifikasi</h3>
                </div>
                
                <div class="p-6">
                    
                    @if($detail->status === 'diterima' || $detail->status === 'ditolak')
                        {{-- Final status reached — show badge only --}}
                        <div class="text-center py-4">
                            @if($detail->status === 'diterima')
                                <div class="w-14 h-14 mx-auto rounded-full bg-secondary-100 flex items-center justify-center mb-3">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-secondary-600"></i>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-secondary-100 text-secondary-700 border border-secondary-200">DITERIMA</span>
                                <p class="text-xs text-[#a1b0cb] mt-3">Pendaftaran ini sudah disetujui. Tidak ada aksi lanjutan.</p>
                            @else
                                <div class="w-14 h-14 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-3">
                                    <i data-lucide="x-circle" class="w-8 h-8 text-red-600"></i>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200">DITOLAK</span>
                                <p class="text-xs text-[#a1b0cb] mt-3">Pendaftaran ini sudah ditolak.</p>
                            @endif
                        </div>

                        {{-- Also hide payment verification if already lunas --}}
                        @if($detail->pembayaran && $detail->pembayaran->status === 'lunas')
                            <div class="mt-4 pt-4 border-t border-[#d9dee3] dark:border-[#434463] text-center">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-secondary-100 text-secondary-700">
                                    <i data-lucide="check" class="w-4 h-4"></i> Pembayaran Lunas
                                </span>
                            </div>
                        @endif

                    @elseif($detail->status === 'pending')
                        <div class="text-center pb-4 border-b border-[#d9dee3] dark:border-[#434463] mb-4">
                            <i data-lucide="info" class="w-8 h-8 text-blue-500 mx-auto mb-2"></i>
                            <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb]">Pendaftar baru masuk. Silakan ubah status ke <b class="text-[#566a7f] dark:text-[#d5d5e2]">Menunggu Verifikasi</b> untuk mulai mengecek dokumen.</p>
                        </div>
                        <form action="{{ route('admin.verifikasi.start', $detail->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full flex justify-center items-center gap-2 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i data-lucide="play-circle" class="w-4 h-4"></i> Mulai Verifikasi
                            </button>
                        </form>
                    @else
                        <!-- Form for Terima / Tolak -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Catatan Tambahan / Alasan</label>
                                <textarea id="notifikasi" name="notifikasi" rows="4" class="sneat-input" placeholder="Opsional jika diterima. Wajib diisi jika ditolak / minta revisi..."></textarea>
                                <p class="text-xs text-[#a1b0cb] mt-1">Catatan ini akan dilihat oleh Wali Murid.</p>
                            </div>

                            <div class="flex flex-col gap-3 pt-2">
                                <button type="button" onclick="openModal('modalTerima')" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-secondary-600 hover:bg-secondary-700 text-white font-semibold rounded-lg shadow-sm transition-colors">
                                    <i data-lucide="check" class="w-5 h-5"></i> Setujui Pendaftaran
                                </button>
                                
                                <button type="button" onclick="openModal('modalRevisi')" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-white hover:bg-orange-50 text-orange-600 border border-orange-200 font-semibold rounded-lg transition-colors">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i> Minta Revisi Dokumen
                                </button>

                                <button type="button" onclick="openModal('modalTolak')" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-white hover:bg-red-50 text-red-600 border border-red-200 font-semibold rounded-lg transition-colors">
                                    <i data-lucide="x" class="w-5 h-5"></i> Tolak Pendaftaran
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terima -->
<div id="modalTerima" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#2b2c40] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#d9dee3] dark:border-[#434463]">
                <div class="bg-white dark:bg-[#2b2c40] px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-secondary-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="check-circle" class="h-6 w-6 text-secondary-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-[#566a7f] dark:text-[#d5d5e2]" id="modal-title">Konfirmasi Persetujuan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb]">Apakah Anda yakin dokumen pendaftar atas nama <b class="text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->nama }}</b> sudah valid dan lengkap? Tindakan ini akan mengubah status menjadi <span class="font-bold text-emerald-600">DITERIMA</span>.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-[#f5f5f9] dark:bg-[#232333] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form action="{{ route('admin.verifikasi.terima', $detail->id) }}" method="POST" id="formTerima">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="notifikasi" id="hiddenNotifikasiTerima">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-secondary-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-secondary-500 sm:ml-3 sm:w-auto">Ya, Setujui Pendaftar</button>
                    </form>
                    <button type="button" onclick="closeModal('modalTerima')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-[#2b2c40] px-4 py-2 font-semibold text-[#697a8d] dark:text-[#a1b0cb] shadow-sm ring-1 ring-inset ring-[#d9dee3] dark:ring-[#434463] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="modalTolak" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#2b2c40] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#d9dee3] dark:border-[#434463]">
                <div class="bg-white dark:bg-[#2b2c40] px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-[#566a7f] dark:text-[#d5d5e2]" id="modal-title">Konfirmasi Penolakan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb]">Anda akan menolak pendaftar ini secara permanen. Pastikan Anda telah mengisi "Catatan Tambahan" agar wali murid tahu alasannya.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-[#f5f5f9] dark:bg-[#232333] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form action="{{ route('admin.verifikasi.tolak', $detail->id) }}" method="POST" id="formTolak">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="notifikasi" id="hiddenNotifikasiTolak">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Ya, Tolak Pendaftaran</button>
                    </form>
                    <button type="button" onclick="closeModal('modalTolak')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-[#2b2c40] px-4 py-2 font-semibold text-[#697a8d] dark:text-[#a1b0cb] shadow-sm ring-1 ring-inset ring-[#d9dee3] dark:ring-[#434463] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revisi -->
<div id="modalRevisi" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#2b2c40] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#d9dee3] dark:border-[#434463]">
                <div class="bg-white dark:bg-[#2b2c40] px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="edit-3" class="h-6 w-6 text-orange-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-[#566a7f] dark:text-[#d5d5e2]" id="modal-title">Konfirmasi Permintaan Revisi</h3>
                            <div class="mt-2">
                                <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb]">Anda akan meminta revisi untuk pendaftar ini. Pastikan Anda telah mengisi "Catatan Tambahan" secara detail (contoh: berkas KK kurang jelas) agar wali murid dapat memperbaikinya.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-[#f5f5f9] dark:bg-[#232333] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form action="{{ route('admin.verifikasi.revisi', $detail->id) }}" method="POST" id="formRevisi">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="notifikasi" id="hiddenNotifikasiRevisi">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-orange-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">Ya, Minta Revisi</button>
                    </form>
                    <button type="button" onclick="closeModal('modalRevisi')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-[#2b2c40] px-4 py-2 font-semibold text-[#697a8d] dark:text-[#a1b0cb] shadow-sm ring-1 ring-inset ring-[#d9dee3] dark:ring-[#434463] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const notifikasiText = document.getElementById('notifikasi').value;
        
        // Pass textarea value to hidden inputs in modals
        if(modalId === 'modalTerima') {
            document.getElementById('hiddenNotifikasiTerima').value = notifikasiText;
        } else if (modalId === 'modalTolak') {
            if(!notifikasiText.trim()) {
                alert("Harap isi Catatan/Alasan Tolak terlebih dahulu!");
                document.getElementById('notifikasi').focus();
                return;
            }
            document.getElementById('hiddenNotifikasiTolak').value = notifikasiText;
        } else if (modalId === 'modalRevisi') {
            if(!notifikasiText.trim()) {
                alert("Harap isi Catatan/Alasan Revisi terlebih dahulu!");
                document.getElementById('notifikasi').focus();
                return;
            }
            document.getElementById('hiddenNotifikasiRevisi').value = notifikasiText;
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }
</script>

@endsection
