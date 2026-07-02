@extends('layouts.app')

@section('title', 'Konfigurasi Pembayaran')
@section('header_title', 'Konfigurasi Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 rounded-lg border border-[#d9dee3] bg-white p-5 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="flex items-center gap-2 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">
                <i data-lucide="landmark" class="h-5 w-5 text-[#696cff]"></i>
                Konfigurasi Pembayaran
            </h2>
            <p class="mt-1 text-sm text-[#697a8d] dark:text-[#a1b0cb]">Informasi ini ditampilkan kepada wali murid setelah pendaftaran diterima.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold {{ $paymentSetting ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400' }}">
            <i data-lucide="{{ $paymentSetting ? 'circle-check' : 'circle-alert' }}" class="h-3.5 w-3.5"></i>
            {{ $paymentSetting ? 'Sudah dikonfigurasi' : 'Belum dikonfigurasi' }}
        </span>
    </div>

    <form method="POST" action="{{ route('admin.payment-settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
            <div class="border-b border-[#d9dee3] bg-[#f5f5f9] px-6 py-4 dark:border-[#434463] dark:bg-[#232333]">
                <h3 class="flex items-center gap-2 font-heading text-base font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
                    <i data-lucide="building-2" class="h-4 w-4 text-[#696cff]"></i>
                    Rekening Tujuan
                </h3>
            </div>

            <div class="grid gap-5 p-6 md:grid-cols-2">
                <div>
                    <label for="bank_name" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Nama Bank <span class="text-red-500">*</span></label>
                    <input id="bank_name" name="bank_name" type="text" maxlength="100" required value="{{ old('bank_name', $paymentSetting?->bank_name) }}" class="sneat-input" placeholder="Contoh: Bank Syariah Indonesia">
                    @error('bank_name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_number" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Nomor Rekening <span class="text-red-500">*</span></label>
                    <input id="account_number" name="account_number" type="text" inputmode="numeric" maxlength="50" required value="{{ old('account_number', $paymentSetting?->account_number) }}" class="sneat-input" placeholder="Masukkan nomor rekening">
                    @error('account_number')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_holder_name" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Atas Nama <span class="text-red-500">*</span></label>
                    <input id="account_holder_name" name="account_holder_name" type="text" maxlength="150" required value="{{ old('account_holder_name', $paymentSetting?->account_holder_name) }}" class="sneat-input" placeholder="Nama pemilik rekening">
                    @error('account_holder_name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="amount" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Nominal Pembayaran <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-semibold text-[#697a8d]">Rp</span>
                        <input id="amount" name="amount" type="number" min="0" max="9999999999999.99" step="0.01" required value="{{ old('amount', $paymentSetting?->amount) }}" class="sneat-input !pl-10" placeholder="0">
                    </div>
                    @error('amount')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
            <div class="border-b border-[#d9dee3] bg-[#f5f5f9] px-6 py-4 dark:border-[#434463] dark:bg-[#232333]">
                <h3 class="flex items-center gap-2 font-heading text-base font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
                    <i data-lucide="qr-code" class="h-4 w-4 text-[#696cff]"></i>
                    QRIS & Instruksi Tambahan
                </h3>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-[minmax(0,1fr)_280px]">
                <div class="space-y-5">
                    <div>
                        <label for="qris" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Gambar QRIS <span class="font-normal text-[#a1b0cb]">(opsional)</span></label>
                        <input id="qris" name="qris" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg border border-[#d9dee3] text-sm text-[#697a8d] file:mr-4 file:border-0 file:bg-[#e7e7ff] file:px-4 file:py-2.5 file:font-semibold file:text-[#696cff] hover:file:bg-[#d8d8ff] dark:border-[#434463] dark:bg-[#232333] dark:text-[#a1b0cb]">
                        <p class="mt-1.5 text-xs text-[#a1b0cb]">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
                        @error('qris')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    @if($paymentSetting?->qris_url)
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                            <input type="checkbox" name="remove_qris" value="1" @checked(old('remove_qris')) class="mt-0.5 rounded border-red-300 text-red-600 focus:ring-red-500">
                            <span>
                                <span class="block font-semibold">Hapus QRIS yang tersimpan</span>
                                <span class="text-xs opacity-80">Centang jika pembayaran hanya menggunakan transfer bank.</span>
                            </span>
                        </label>
                    @endif

                    <div>
                        <label for="payment_note" class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Catatan / Instruksi Pembayaran <span class="font-normal text-[#a1b0cb]">(opsional)</span></label>
                        <textarea id="payment_note" name="payment_note" rows="5" maxlength="2000" class="sneat-input" placeholder="Contoh: Cantumkan nama anak pada berita transfer.">{{ old('payment_note', $paymentSetting?->payment_note) }}</textarea>
                        @error('payment_note')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="rounded-lg border border-dashed border-[#d9dee3] bg-[#f5f5f9] p-4 text-center dark:border-[#434463] dark:bg-[#232333]">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-[#a1b0cb]">QRIS Saat Ini</p>
                    @if($paymentSetting?->qris_url)
                        <img src="{{ $paymentSetting->qris_url }}" alt="QRIS pembayaran saat ini" class="mx-auto max-h-64 w-full rounded-lg bg-white object-contain p-2">
                    @else
                        <div class="flex min-h-48 flex-col items-center justify-center text-[#a1b0cb]">
                            <i data-lucide="image-off" class="h-10 w-10"></i>
                            <p class="mt-2 text-sm font-medium">Belum ada QRIS</p>
                            <p class="mt-1 text-xs">Bagian QRIS tidak akan tampil kepada wali murid.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="sneat-btn-primary">
                <i data-lucide="save" class="h-4 w-4"></i>
                Simpan Konfigurasi
            </button>
        </div>
    </form>
</div>
@endsection
