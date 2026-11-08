<x-layout>
    <x-slot:title>Pemantauan Wellness</x-slot:title>

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Pemantauan Wellness</h1>
        <p class="text-sm text-slate-500">Cari dan pantau sesi relaksasi wisatawan berdasarkan nama, lokasi resort, atau tanggal.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Form Pencarian / Filter Hybrid --}}
        <form action="{{ route('pemantauan.index') }}" method="GET"
              class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 h-fit">
            <h2 class="text-base font-bold text-slate-800">Cari & Filter Sesi</h2>

            {{-- Filter Nama Wisatawan --}}
            <div>
                <label for="nama_wisatawan" class="mb-1 block text-sm font-medium text-slate-600">Nama Wisatawan</label>
                <select id="nama_wisatawan" name="nama_wisatawan"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    <option value="">Semua Wisatawan</option>
                    @foreach ($daftarWisatawan as $nama)
                        <option value="{{ $nama }}" {{ request('nama_wisatawan') == $nama ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Lokasi Resort --}}
            <div>
                <label for="lokasi_resort" class="mb-1 block text-sm font-medium text-slate-600">Lokasi Resort</label>
                <select id="lokasi_resort" name="lokasi_resort"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    <option value="">Semua Lokasi</option>
                    @foreach ($daftarResort as $lokasi)
                        <option value="{{ $lokasi }}" {{ request('lokasi_resort') == $lokasi ? 'selected' : '' }}>
                            {{ $lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Sesi --}}
            <div>
                <label for="tanggal_sesi" class="mb-1 block text-sm font-medium text-slate-600">Tanggal Sesi</label>
                <input id="tanggal_sesi" name="tanggal_sesi" type="date" value="{{ request('tanggal_sesi') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700 text-center">
                    Cari Sesi
                </button>
                @if(request()->filled('nama_wisatawan') || request()->filled('lokasi_resort') || request()->filled('tanggal_sesi'))
                    <a href="{{ route('pemantauan.index') }}" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Sesi aktif / hasil pencarian --}}
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
                    Belum ada sesi yang berjalan atau data yang sesuai dengan pencarian.
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
