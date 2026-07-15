@props(['registration', 'compact' => false])

@php
    $steps = \App\Support\SpmbStatusPresenter::timeline($registration);
    $stateClasses = [
        'completed' => 'border-emerald-500 bg-emerald-500 text-white',
        'active' => 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200',
        'action' => 'border-amber-500 bg-amber-50 text-amber-800 dark:bg-amber-500/20 dark:text-amber-200',
        'stopped' => 'border-rose-400 bg-rose-50 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200',
        'upcoming' => 'border-slate-300 bg-white text-slate-400 dark:border-slate-600 dark:bg-[#2b2c40] dark:text-slate-400',
    ];
@endphp

<ol {{ $attributes->class(['grid gap-3', 'sm:grid-cols-2 xl:grid-cols-4' => !$compact, 'sm:grid-cols-2' => $compact]) }} aria-label="Tahapan proses SPMB">
    @foreach($steps as $step)
        <li class="flex min-w-0 items-start gap-3 rounded-lg border border-[#d9dee3] bg-white p-3 dark:border-[#434463] dark:bg-[#2b2c40]">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 {{ $stateClasses[$step['state']] }}">
                <i data-lucide="{{ $step['state'] === 'completed' ? 'check' : $step['icon'] }}" class="h-4 w-4" aria-hidden="true"></i>
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $step['label'] }}</span>
                <span class="mt-0.5 block text-xs {{ $step['state'] === 'action' ? 'font-semibold text-amber-700 dark:text-amber-300' : ($step['state'] === 'stopped' ? 'font-semibold text-rose-700 dark:text-rose-300' : 'text-[#a1b0cb]') }}">{{ $step['state_label'] }}</span>
            </span>
        </li>
    @endforeach
</ol>
