<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 text-white shadow-xl transition-transform duration-200 lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-6 py-6">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/25">
            <svg class="h-6 w-6 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 21c0-8 4-14 15-15 0 11-6 15-13 15M6 21c2-5 5-8 9-10"/>
            </svg>
        </div>
        <div class="leading-tight">
            <p class="text-lg font-bold tracking-tight">BaliSense</p>
            <p class="text-[11px] uppercase tracking-widest text-emerald-200/80">Smart Wellness</p>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-1 px-4 py-2">
        <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-widest text-emerald-200/60">Menu Utama</p>

        <x-navlink href="/dashboard" :active="request()->is('dashboard*')"
                   icon="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75">
            Beranda
        </x-navlink>

        <x-navlink href="/wisatawan" :active="request()->is('wisatawan*')"
                   icon="M15 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4.5 20a7.5 7.5 0 0 1 15 0">
            Data Wisatawan
        </x-navlink>

        <x-navlink href="/pemantauan" :active="request()->is('pemantauan*')"
                   icon="M3 12h4l3 8 4-16 3 8h4">
            Pemantauan Wellness
        </x-navlink>

        <x-navlink href="/perangkat" :active="request()->is('perangkat*')"
                   icon="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z">
            Sensor &amp; Perangkat IoT
        </x-navlink>

        <x-navlink href="/analitik" :active="request()->is('analitik*')"
                   icon="M4 20V10m6 10V4m6 16v-7m4 7H2">
            Analitik &amp; Laporan
        </x-navlink>
    </nav>

    {{-- Info sensor --}}
    <div class="m-4 rounded-2xl bg-white/10 p-4 text-xs text-emerald-100 ring-1 ring-white/15">
        <p class="font-semibold text-white">Sensor Terintegrasi</p>
        <p class="mt-1 leading-relaxed text-emerald-100/80">Grove-GSR &middot; MAX30102 &middot; MAX30205 &middot; ESP32</p>
    </div>
</aside>