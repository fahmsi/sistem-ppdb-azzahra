@if(! $paymentSetting)
    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
        <div class="flex items-start gap-2">
            <i data-lucide="info" class="mt-0.5 h-4 w-4 flex-shrink-0"></i>
            <p>Informasi pembayaran belum tersedia. Silakan hubungi admin sekolah.</p>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
        <div class="rounded-lg border border-secondary-100 bg-white p-3 dark:border-[#434463] dark:bg-[#232333]">
            <p class="text-xs text-gray-500 dark:text-[#a1b0cb]">Bank</p>
            <p class="font-semibold text-gray-900 dark:text-[#d5d5e2]">{{ $paymentSetting->bank_name }}</p>
        </div>
        <div class="rounded-lg border border-secondary-100 bg-white p-3 dark:border-[#434463] dark:bg-[#232333]">
            <p class="text-xs text-gray-500 dark:text-[#a1b0cb]">No. Rekening</p>
            <p class="break-all font-semibold text-gray-900 dark:text-[#d5d5e2]">{{ $paymentSetting->account_number }}</p>
        </div>
        <div class="rounded-lg border border-secondary-100 bg-white p-3 dark:border-[#434463] dark:bg-[#232333]">
            <p class="text-xs text-gray-500 dark:text-[#a1b0cb]">Atas Nama</p>
            <p class="font-semibold text-gray-900 dark:text-[#d5d5e2]">{{ $paymentSetting->account_holder_name }}</p>
        </div>
        <div class="rounded-lg border border-secondary-100 bg-white p-3 dark:border-[#434463] dark:bg-[#232333]">
            <p class="text-xs text-gray-500 dark:text-[#a1b0cb]">Nominal Pembayaran</p>
            <p class="font-semibold text-gray-900 dark:text-[#d5d5e2]">{{ $paymentSetting->formatted_amount }}</p>
        </div>
    </div>

    @if($paymentSetting->qris_url)
        <div class="mt-4 rounded-lg border border-secondary-100 bg-white p-4 text-center dark:border-[#434463] dark:bg-[#232333]">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-[#d5d5e2]">Atau pindai QRIS berikut</p>
            <img src="{{ $paymentSetting->qris_url }}" alt="QRIS pembayaran" decoding="async" class="mx-auto max-h-72 w-full max-w-xs object-contain">
        </div>
    @endif

    @if($paymentSetting->payment_note)
        <div class="mt-4 flex items-start gap-2 rounded-lg border border-blue-100 bg-blue-50 p-3 text-sm text-blue-800 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300">
            <i data-lucide="notebook-pen" class="mt-0.5 h-4 w-4 flex-shrink-0"></i>
            <p class="whitespace-pre-line">{{ $paymentSetting->payment_note }}</p>
        </div>
    @endif
@endif
