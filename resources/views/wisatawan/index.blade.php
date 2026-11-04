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

                {{-- Bagian Aksi yang Dirapikan --}}
                <td class="px-6 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('pemantauan.show', $sesi) }}" class="inline-flex items-center gap-1 font-medium text-emerald-600 hover:text-emerald-800 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>

                        {{-- Tombol Hapus (Menggunakan Form DELETE + CSRF) --}}
                        <form action="" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data sesi ini?')" class="inline-flex items-center gap-1 font-medium text-red-600 hover:text-red-800 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
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
