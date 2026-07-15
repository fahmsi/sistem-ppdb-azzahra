@props(['presentation', 'showDescription' => false])

<div {{ $attributes->class('min-w-0') }}>
    <span class="inline-flex max-w-full items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $presentation['badge_class'] }}"
          data-attention="{{ $presentation['attention'] }}">
        <i data-lucide="{{ $presentation['icon'] }}" class="h-3.5 w-3.5 shrink-0" aria-hidden="true"></i>
        <span>{{ $presentation['label'] }}</span>
    </span>
    @if($showDescription)
        <p class="mt-2 text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">{{ $presentation['description'] }}</p>
    @endif
</div>
