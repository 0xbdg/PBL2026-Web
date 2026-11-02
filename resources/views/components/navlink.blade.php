@props(['active' => false, 'icon' => null])

<a {{ $attributes->class([
    'group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition',
    'bg-white/15 text-white shadow-inner ring-1 ring-white/20' => $active,
    'text-emerald-100/80 hover:bg-white/10 hover:text-white' => ! $active,
]) }} @if ($active) aria-current="page" @endif>
    @if ($icon)
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $icon }}"/>
        </svg>
    @endif
    <span>{{ $slot }}</span>
</a>