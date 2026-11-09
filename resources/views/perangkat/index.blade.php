<x-layout>
    <x-slot:title>Sensor & Perangkat IoT</x-slot:title>

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sensor &amp; Perangkat IoT</h1>
            <p class="text-sm text-slate-500">Status perangkat keras pemantau psikofisiologis wisatawan.</p>
        </div>
        <p class="text-xs text-slate-500">
            Data terakhir:
            <span class="font-semibold text-slate-700">{{ $terakhir ? $terakhir->recorded_at->format('d M Y, H:i:s') : '-' }}</span>
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($perangkat as $alat)
            <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset
                        {{ $online ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-rose-200' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $online ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ $online ? 'Online' : 'Offline' }}
                    </span>
                </div>

                <h2 class="mt-4 text-lg font-bold text-slate-800">{{ $alat['nama'] }}</h2>
                <p class="text-sm text-slate-500">{{ $alat['fungsi'] }}</p>

                <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Pembacaan</dt>
                        <dd class="font-semibold text-slate-800">{{ $alat['pembacaan'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Antarmuka</dt>
                        <dd class="font-semibold text-slate-800">{{ $alat['protokol'] }}</dd>
                    </div>
                </dl>
            </div>
        @endforeach
    </div>

    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5 text-sm text-emerald-900">
        Perangkat dianggap <strong>online</strong> bila ESP32 mengirim data dalam 10 menit terakhir.
    </div>
</x-layout>
