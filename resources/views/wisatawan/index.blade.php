<x-layout>
    <x-slot:title>Data Wisatawan</x-slot:title>

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Wisatawan</h1>
            <p class="text-sm text-slate-500">Daftar wisatawan dan status sesi relaksasi.</p>
        </div>
        <a href="{{ route('pemantauan.index') }}"
           class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700">
            + Sesi Baru
        </a>
    </div>

    @if ($keyword !== '')
        <p class="text-sm text-slate-500">Hasil pencarian untuk <span class="font-semibold text-slate-700">"{{ $keyword }}"</span></p>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Wisatawan</th>
                        <th class="px-6 py-3 font-semibold">Lokasi Resort</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Sesi</th>
                        <th class="px-6 py-3 font-semibold">Jumlah Log</th>
                        <th class="px-6 py-3 font-semibold">Klasifikasi Terakhir</th>
                        <th class="px-6 py-3 font-semibold">Status Sesi</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($daftarSesi as $sesi)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3 text-slate-400">{{ $daftarSesi->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $sesi->nama_wisatawan }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $sesi->lokasi_resort }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $sesi->tanggal_sesi->format('d M Y') }}</td>
                            <td class="px-6 py-3">{{ $sesi->sensor_logs_count }}</td>
                            <td class="px-6 py-3">
                                @if ($sesi->latestLog)
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $sesi->latestLog->badge_class }}">
                                        {{ $sesi->latestLog->status_klasifikasi }} &middot; {{ number_format($sesi->latestLog->tingkat_relaksasi, 0) }}%
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset
                                    {{ $sesi->status_sesi === 'aktif' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    {{ ucfirst($sesi->status_sesi) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('pemantauan.show', $sesi) }}" class="text-sm font-semibold text-emerald-700 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400">Data wisatawan tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $daftarSesi->links() }}</div>
</x-layout>