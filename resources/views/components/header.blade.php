@php
    $namaUser = auth()->user()?->name ?? 'Admin Wellness';
    $inisial = strtoupper(mb_substr($namaUser, 0, 1));
@endphp

<header class="sticky top-0 z-20 flex items-center gap-4 border-b border-slate-200 bg-white/80 px-4 py-3 backdrop-blur sm:px-6 lg:px-8">
    {{-- Tombol sidebar (mobile) --}}
    <button id="sidebar-toggle" type="button" class="rounded-xl p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Pencarian --}}
    <form action="{{ route('wisatawan.index') }}" method="GET" class="relative max-w-md flex-1">
        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m21 21-5.2-5.2M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
        </svg>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari wisatawan atau resort..."
               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2 pl-10 pr-4 text-sm placeholder:text-slate-400 focus:border-emerald-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </form>

    <div class="ml-auto flex items-center gap-4">
        {{-- Lokasi & cuaca --}}
        <div class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-800 md:flex">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>
            </svg>
            Ubud, Bali - 28°C Cerah
        </div>

        {{-- Profil dengan Dropdown (Alpine.js) --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="flex items-center gap-3 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 p-1">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-bold text-white shadow">
                    {{ $inisial }}
                </div>
                <div class="hidden leading-tight text-left sm:block">
                    <p class="text-sm font-semibold text-slate-800">{{ $namaUser }}</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
                <svg class="hidden h-4 w-4 text-slate-400 transition-transform duration-200 sm:block" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown Menu Logout --}}
            <div x-show="open"
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 rounded-2xl border border-slate-100 bg-white py-1.5 shadow-xl ring-1 ring-black/5 focus:outline-none z-50"
                 style="display: none;">

                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
