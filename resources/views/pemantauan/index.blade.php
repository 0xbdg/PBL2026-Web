<x-layout>
    <x-slot:title>Pemantauan Wellness</x-slot:title>

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Pemantauan Wellness</h1>
        <p class="text-sm text-slate-500">Buat sesi relaksasi baru dan pantau sesi yang sedang berjalan.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Form sesi baru --}}
        <form action="{{ route('pemantauan.store') }}" method="POST"
              class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            <h2 class="text-base font-bold text-slate-800">Mulai Sesi Baru</h2>

            <div>
                <label for="nama_wisatawan" class="mb-1 block text-sm font-medium text-slate-600">Nama Wisatawan</label>
                <input id="nama_wisatawan" name="nama_wisatawan" type="text" value="{{ old('nama_wisatawan') }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                @error('nama_wisatawan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lokasi_resort" class="mb-1 block text-sm font-medium text-slate-600">Lokasi Resort</label>
                <input id="lokasi_resort" name="lokasi_resort" type="text" value="{{ old('lokasi_resort') }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                @error('lokasi_resort') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_sesi" class="mb-1 block text-sm font-medium text-slate-600">Tanggal Sesi</label>
                <input id="tanggal_sesi" name="tanggal_sesi" type="date" value="{{ old('tanggal_sesi', now()->toDateString()) }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                @error('tanggal_sesi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700">
                Mulai Pemantauan
            </button>
        </form>

        {{-- Sesi aktif --}}
        <div class="space-y-4 lg:col-span-2">
            <h2 class="text-base font-bold text-slate-800">Sesi Aktif ({{ $sesiAktif->count() }})</h2>

            @forelse ($sesiAktif as $sesi)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $sesi->nama_wisatawan }}</p>
                        <p class="text-xs text-slate-500">{{ $sesi->lokasi_resort }} &middot; {{ $sesi->tanggal_sesi->format('d M Y') }} &middot; {{ $sesi->sensor_logs_count }} log</p>
                    </div>

                    @if ($sesi->latestLog)
                        <div class="text-right text-sm">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $sesi->latestLog->badge_class }}">
                                {{ $sesi->latestLog->status_klasifikasi }}
                            </span>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $sesi->latestLog->detak_jantung }} BPM &middot;
                                {{ number_format($sesi->latestLog->suhu_kulit, 1) }} °C &middot;
                                {{ number_format($sesi->latestLog->gsr_eda, 1) }} µS
                            </p>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <a href="{{ route('pemantauan.show', $sesi) }}"
                           class="rounded-xl bg-emerald-50 px-3.5 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Pantau</a>
                        <form action="{{ route('pemantauan.selesai', $sesi) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-xl bg-slate-100 px-3.5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">Selesaikan</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl bg-white p-10 text-center text-sm text-slate-400 shadow-sm ring-1 ring-slate-200">
                    Belum ada sesi yang berjalan.
                </div>
            @endforelse
        </div>
    </div>
</x-layout>