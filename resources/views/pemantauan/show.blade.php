<x-layout>
    <x-slot:title>Detail Sesi</x-slot:title>

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('pemantauan.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali</a>
            <h1 class="mt-1 text-2xl font-bold text-slate-800">{{ $sesi->nama_wisatawan }}</h1>
            <p class="text-sm text-slate-500">{{ $sesi->lokasi_resort }} &middot; {{ $sesi->tanggal_sesi->format('d M Y') }}</p>
        </div>
        <span class="rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset
            {{ $sesi->status_sesi === 'aktif' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200' }}">
            Sesi {{ ucfirst($sesi->status_sesi) }}
        </span>
    </div>

    @if ($terbaru)
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs text-slate-500">Indeks Relaksasi</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($terbaru->tingkat_relaksasi, 1) }}%</p>
                <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $terbaru->badge_class }}">{{ $terbaru->status_klasifikasi }}</span>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs text-slate-500">Detak Jantung</p>
                <p class="text-2xl font-bold text-slate-800">{{ $terbaru->detak_jantung }} <span class="text-sm font-medium text-slate-500">BPM</span></p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs text-slate-500">Suhu Kulit</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($terbaru->suhu_kulit, 2) }} <span class="text-sm font-medium text-slate-500">°C</span></p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs text-slate-500">Respons EDA</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($terbaru->gsr_eda, 2) }} <span class="text-sm font-medium text-slate-500">µS</span></p>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">Riwayat Pembacaan Sensor</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Waktu</th>
                        <th class="px-6 py-3 font-semibold">Detak Jantung</th>
                        <th class="px-6 py-3 font-semibold">Suhu Kulit</th>
                        <th class="px-6 py-3 font-semibold">GSR / EDA</th>
                        <th class="px-6 py-3 font-semibold">Indeks</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $log->recorded_at->format('H:i:s') }}</td>
                            <td class="px-6 py-3">{{ $log->detak_jantung }} BPM</td>
                            <td class="px-6 py-3">{{ number_format($log->suhu_kulit, 2) }} °C</td>
                            <td class="px-6 py-3">{{ number_format($log->gsr_eda, 2) }} µS</td>
                            <td class="px-6 py-3">{{ number_format($log->tingkat_relaksasi, 1) }}%</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $log->badge_class }}">{{ $log->status_klasifikasi }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">Belum ada data sensor untuk sesi ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $logs->links() }}</div>
</x-layout>