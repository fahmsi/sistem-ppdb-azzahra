@extends('layouts.app')

@section('title', 'Status Pendaftaran')
@section('header_title', 'Status Pendaftaran Anak')

@section('content')
@php
    $bankName = config('ppdb.bank_name', '-');
    $bankAccountNumber = config('ppdb.bank_account_number', '-');
    $bankAccountHolder = config('ppdb.bank_account_holder', '-');
    $daftarUlangAmount = (int) config('ppdb.daftar_ulang_amount', 0);
    $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('ppdb.admin_whatsapp', ''));
@endphp

<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-up">
        <div class="bg-primary-50 border-b border-primary-100 p-6 sm:px-8 sm:py-6">
            <h2 class="text-2xl font-heading font-bold flex items-center gap-2 text-primary-900">
                <i data-lucide="activity" class="w-6 h-6 text-primary-600"></i> Riwayat & Status Pendaftaran
            </h2>
            <p class="text-primary-600 text-sm mt-1">Pantau perkembangan proses verifikasi, penerimaan, dan daftar ulang anak Anda di sini.</p>
        </div>

        <div class="p-6 sm:p-8">
            @if(!isset($registrations) || $registrations->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-heading font-semibold text-gray-800">Belum Ada Pendaftaran</h3>
                    <p class="text-gray-500 mt-1 max-w-md mb-6">Anda belum mendaftar ke gelombang manapun. Silakan pilih gelombang yang tersedia.</p>
                    <a href="{{ route('parent.pendaftaran.index') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Lihat Gelombang Pendaftaran
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($registrations as $reg)
                        @php
                            $payment = $reg->pembayaran;
                            $paymentStatus = $payment?->status;
                            $isPaymentWaiting = $payment && in_array($paymentStatus, ['pending', 'menunggu_verifikasi'], true);
                            $isPaymentLunas = $paymentStatus === 'lunas';
                            $isPaymentRejected = $paymentStatus === 'ditolak';
                            $waMessage = "Assalamu'alaikum Admin, saya sudah mengunggah bukti pembayaran daftar ulang PPDB untuk nomor pendaftaran {$reg->nomor_pendaftaran}. Mohon konfirmasinya. Terima kasih.";
                            $waUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($waMessage) : null;
                        @endphp

                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Gelombang</p>
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $reg->pendaftaran->gelombang }}</h4>
                                    <p class="text-sm text-gray-500">Tahun Ajaran {{ $reg->pendaftaran->tahun_ajaran }}</p>
                                    <p class="text-xs text-gray-500 mt-1">No. Pendaftaran: <span class="font-semibold text-gray-700">{{ $reg->nomor_pendaftaran }}</span></p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider mb-1">Status Saat Ini</p>
                                    @if($reg->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">
                                            <i data-lucide="clock" class="w-4 h-4"></i> Pending
                                        </span>
                                    @elseif($reg->status === 'menunggu_verifikasi')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                            <i data-lucide="search" class="w-4 h-4"></i> Menunggu Verifikasi Admin
                                        </span>
                                    @elseif($reg->status === 'diterima')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-secondary-100 text-secondary-700 border border-secondary-200">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i> Diterima
                                        </span>
                                    @elseif($reg->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-200">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i> Ditolak
                                        </span>
                                    @elseif($reg->status === 'perlu_revisi')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i> Perlu Revisi Dokumen
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500 mb-1">Tanggal Mendaftar</h5>
                                        <p class="font-medium text-gray-900">{{ $reg->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500 mb-1">Nama Anak</h5>
                                        <p class="font-medium text-gray-900">{{ $reg->siswa->nama ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($reg->notifikasi)
                                    <div class="mt-6 p-4 rounded-lg {{ $reg->status === 'diterima' ? 'bg-secondary-50 border border-secondary-100 text-secondary-800' : ($reg->status === 'ditolak' ? 'bg-red-50 border border-red-100 text-red-800' : 'bg-blue-50 border border-blue-100 text-blue-800') }}">
                                        <h5 class="text-sm font-bold flex items-center gap-2 mb-1">
                                            <i data-lucide="message-square" class="w-4 h-4"></i> Pesan dari Admin:
                                        </h5>
                                        <p class="text-sm">{{ $reg->notifikasi }}</p>
                                    </div>
                                @endif

                                @if($reg->status === 'diterima')
                                    <div class="mt-6 bg-secondary-50 border border-secondary-200 rounded-xl p-5">
                                        @if(!$payment)
                                            <h5 class="text-base font-bold text-secondary-800 flex items-center gap-2 mb-3">
                                                <i data-lucide="check-circle" class="w-5 h-5 text-secondary-600"></i>
                                                Selamat, pendaftaran anak Anda telah diterima.
                                            </h5>
                                            <p class="text-sm text-gray-700 mb-4">Silakan melakukan daftar ulang dengan melakukan pembayaran ke rekening berikut:</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                                <div class="bg-white border border-secondary-100 rounded-lg p-3">
                                                    <p class="text-gray-500 text-xs">Bank</p>
                                                    <p class="font-semibold text-gray-900">{{ $bankName }}</p>
                                                </div>
                                                <div class="bg-white border border-secondary-100 rounded-lg p-3">
                                                    <p class="text-gray-500 text-xs">No. Rekening</p>
                                                    <p class="font-semibold text-gray-900">{{ $bankAccountNumber }}</p>
                                                </div>
                                                <div class="bg-white border border-secondary-100 rounded-lg p-3">
                                                    <p class="text-gray-500 text-xs">Atas Nama</p>
                                                    <p class="font-semibold text-gray-900">{{ $bankAccountHolder }}</p>
                                                </div>
                                                <div class="bg-white border border-secondary-100 rounded-lg p-3">
                                                    <p class="text-gray-500 text-xs">Nominal Daftar Ulang</p>
                                                    <p class="font-semibold text-gray-900">{{ $daftarUlangAmount > 0 ? 'Rp '.number_format($daftarUlangAmount, 0, ',', '.') : '-' }}</p>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-700 mt-4">Setelah melakukan pembayaran, silakan upload bukti pembayaran melalui tombol di bawah ini.</p>
                                        @elseif($isPaymentWaiting)
                                            <h5 class="text-base font-bold text-yellow-800 flex items-center gap-2 mb-2">
                                                <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                                                Bukti pembayaran Anda sedang menunggu verifikasi admin.
                                            </h5>
                                            <p class="text-sm text-gray-700">Silakan hubungi admin untuk konfirmasi.</p>
                                        @elseif($isPaymentLunas)
                                            <h5 class="text-base font-bold text-secondary-800 flex items-center gap-2 mb-2">
                                                <i data-lucide="check-circle-2" class="w-5 h-5 text-secondary-600"></i>
                                                Pembayaran daftar ulang telah diverifikasi. Proses daftar ulang selesai.
                                            </h5>
                                        @elseif($isPaymentRejected)
                                            <h5 class="text-base font-bold text-red-800 flex items-center gap-2 mb-2">
                                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                                                Bukti pembayaran perlu diperbaiki. Silakan upload ulang bukti pembayaran.
                                            </h5>
                                            @if($payment->catatan_admin)
                                                <p class="text-sm text-red-700"><strong>Catatan Admin:</strong> {{ $payment->catatan_admin }}</p>
                                            @endif
                                        @endif

                                        @if($waUrl && ($isPaymentWaiting || session('success')))
                                            <a href="{{ $waUrl }}" target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Admin via WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($payment)
                                    <div class="mt-4 p-4 rounded-lg bg-gray-50 border border-gray-200">
                                        <h5 class="text-sm font-bold text-gray-800 mb-2">Status Daftar Ulang / Pembayaran</h5>
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            @if($isPaymentLunas)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800">Lunas / Diverifikasi</span>
                                            @elseif($isPaymentRejected)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak / Perlu Revisi</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi Admin</span>
                                            @endif
                                            <span class="text-sm font-medium text-gray-700">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                                        </div>

                                        @if($payment->catatan_admin && $isPaymentRejected)
                                            <p class="text-xs text-red-600 mt-2"><strong>Catatan Admin:</strong> {{ $payment->catatan_admin }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($reg->status === 'diterima')
                                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-4 relative z-10">
                                        <a href="{{ route('parent.siswa.kartu') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary-600 hover:bg-secondary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm cursor-pointer">
                                            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Kartu Pendaftaran
                                        </a>

                                        @if($isPaymentLunas)
                                            <a href="{{ route('parent.pembayaran.receipt', $reg->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm cursor-pointer">
                                                <i data-lucide="file-down" class="w-4 h-4"></i> Cetak Bukti Bayar (PDF)
                                            </a>
                                        @elseif(!$payment || $isPaymentRejected)
                                            <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-primary-600 text-primary-600 hover:bg-primary-50 text-sm font-medium rounded-lg transition-colors shadow-sm cursor-pointer">
                                                <i data-lucide="upload" class="w-4 h-4"></i> {{ $payment ? 'Upload Ulang Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-medium rounded-lg">
                                                <i data-lucide="clock" class="w-4 h-4"></i> Menunggu Verifikasi Pembayaran
                                            </span>
                                        @endif
                                    </div>
                                @elseif($reg->status === 'perlu_revisi')
                                    <div class="mt-6 pt-6 border-t border-gray-100 flex gap-4 relative z-10">
                                        <a href="{{ route('parent.siswa.edit', $reg->siswa_id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm cursor-pointer">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i> Perbaiki Dokumen Anak
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($reg->status === 'diterima' && (!$payment || $isPaymentRejected))
                            <div id="modalPayment-{{ $reg->id }}" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')"></div>
                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pointer-events-none">
                                        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md pointer-events-auto">
                                            <div class="bg-primary-800 px-6 py-4 flex items-center justify-between">
                                                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                                                    <i data-lucide="credit-card" class="w-5 h-5"></i> Daftar Ulang
                                                </h3>
                                                <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')" class="text-primary-200 hover:text-white transition-colors">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('parent.pembayaran.store', $reg->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="px-6 py-6 space-y-5">
                                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-800 space-y-1">
                                                        <p class="font-semibold mb-2">Silakan transfer biaya daftar ulang ke rekening berikut:</p>
                                                        <p>Bank: <strong>{{ $bankName }}</strong></p>
                                                        <p>No. Rekening: <strong>{{ $bankAccountNumber }}</strong></p>
                                                        <p>Atas Nama: <strong>{{ $bankAccountHolder }}</strong></p>
                                                        <p>Nominal: <strong>{{ $daftarUlangAmount > 0 ? 'Rp '.number_format($daftarUlangAmount, 0, ',', '.') : '-' }}</strong></p>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer <span class="text-red-500">*</span></label>
                                                        <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/jpg,application/pdf" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 border border-gray-300 rounded-lg">
                                                        <p class="text-xs text-gray-500 mt-1">Format JPG, PNG, atau PDF max 2MB.</p>
                                                    </div>
                                                </div>

                                                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl border-t border-gray-200">
                                                    <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm flex items-center gap-2">
                                                        <i data-lucide="upload" class="w-4 h-4"></i> Unggah Bukti
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
