<x-layout>
    <x-slot:title>Analitik & Laporan</x-slot:title>

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Analitik &amp; Laporan</h1>
        <p class="text-sm text-slate-500">
            Perbandingan rata-rata kondisi <strong>sebelum</strong> (5 pembacaan awal) dan <strong>sesudah</strong> (5 pembacaan akhir) sesi meditasi.
        </p>
    </div>

    {{-- Ringkasan keseluruhan --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($ringkasan as $item)
            @php
                $warna = $item['baik'] === null
                    ? 'text-slate-500 bg-slate-100'
                    : ($item['baik'] ? 'text-emerald-700 bg-emerald-100' : 'text-rose-700 bg-rose-100');
            @endphp
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-medium text-slate-500">{{ $item['label'] }}</p>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $item['sebelum'] }} {{ $item['satuan'] }}
                    <span class="mx-1 text-slate-300">&rarr;</span>
                    <span class="text-xl font-bold text-slate-800">{{ $item['sesudah'] }}</span> {{ $item['satuan'] }}
                </p>
                <span class="mt-2 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $warna }}">
                    {{ $item['selisih'] >= 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }} {{ $item['satuan'] }}
                </span>
            </div>
        @endforeach
    </section>

    {{-- Rekap per sesi --}}
    <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">Rekapitulasi per Sesi</h2>
            <p class="text-xs text-slate-500">Sesi selesai terbaru (maksimal 10). Format: sebelum &rarr; sesudah (selisih).</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Wisatawan</th>
                        <th class="px-6 py-3 font-semibold">Tanggal</th>
                        @foreach ($metrik as $m)
                            <th class="px-6 py-3 font-semibold">{{ $m[0] }} ({{ $m[1] }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rekap as $r)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $r['sesi']->nama_wisatawan }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $r['sesi']->tanggal_sesi->format('d M Y') }}</td>
                            @foreach ($metrik as $kolom => $m)
                                @php
                                    $d = $r['sesudah'][$kolom] - $r['sebelum'][$kolom];
                                    $baik = $m[2] === null ? null : ($m[2] ? $d > 0 : $d < 0);
                                @endphp
                                <td class="whitespace-nowrap px-6 py-3">
                                    {{ $r['sebelum'][$kolom] }} <span class="text-slate-300">&rarr;</span>
                                    <strong>{{ $r['sesudah'][$kolom] }}</strong>
                                    <span class="text-xs {{ $baik === null ? 'text-slate-400' : ($baik ? 'text-emerald-600' : 'text-rose-600') }}">
                                        ({{ $d >= 0 ? '+' : '' }}{{ number_format($d, 2) }})
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($metrik) }}" class="px-6 py-10 text-center text-slate-400">
                                Belum ada sesi selesai dengan data yang cukup.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>