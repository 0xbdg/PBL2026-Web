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

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Indeks Relaksasi: gauge chart + smiley --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs text-slate-500">Indeks Relaksasi</p>
            <div class="relative mx-auto mt-2 h-36 w-36">
                <canvas id="gaugeRelaksasi"></canvas>
                <svg id="gaugeFace" class="pointer-events-none absolute inset-0 m-auto h-16 w-16" viewBox="0 0 64 64" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5">
                    <circle cx="32" cy="32" r="28" id="faceCircle" stroke="#10b981" fill="#ecfdf5"/>
                    <circle cx="22" cy="26" r="2.5" id="faceEyeL" fill="#10b981" stroke="none"/>
                    <circle cx="42" cy="26" r="2.5" id="faceEyeR" fill="#10b981" stroke="none"/>
                    <path id="faceMouth" d="M20 38 Q32 50 44 38" stroke="#10b981"/>
                </svg>
            </div>
            <p class="mt-2 text-center text-2xl font-bold text-slate-800">{{ $terbaru ? number_format($terbaru->tingkat_relaksasi, 1) : '0.0' }}%</p>
            <div class="text-center">
                <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $terbaru?->badge_class ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">{{ $terbaru?->status_klasifikasi ?? 'Belum ada data' }}</span>
            </div>
        </div>

        {{-- Detak Jantung: line chart --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs text-slate-500">Detak Jantung</p>
            <p class="text-2xl font-bold text-slate-800">{{ $terbaru?->detak_jantung ?? '-' }} <span class="text-sm font-medium text-slate-500">BPM</span></p>
            <div class="mt-3 h-32"><canvas id="chartDetak"></canvas></div>
        </div>

        {{-- Suhu Kulit: line chart --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs text-slate-500">Suhu Kulit</p>
            <p class="text-2xl font-bold text-slate-800">{{ $terbaru ? number_format($terbaru->suhu_kulit, 2) : '-' }} <span class="text-sm font-medium text-slate-500">°C</span></p>
            <div class="mt-3 h-32"><canvas id="chartSuhu"></canvas></div>
        </div>

        {{-- Respons EDA: line chart --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-xs text-slate-500">Respons EDA</p>
            <p class="text-2xl font-bold text-slate-800">{{ $terbaru ? number_format($terbaru->gsr_eda, 2) : '-' }} <span class="text-sm font-medium text-slate-500">µS</span></p>
            <div class="mt-3 h-32"><canvas id="chartEda"></canvas></div>
        </div>
    </div>

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
    @php
        $chartRows = $grafik->map(function ($l) {
            return [
                'waktu' => $l->recorded_at->format('H:i:s'),
                'hr'    => (float) $l->detak_jantung,
                'suhu'  => (float) $l->suhu_kulit,
                'eda'   => (float) $l->gsr_eda,
            ];
        })->all();
    @endphp
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function () {
                // Data untuk chart (urut dari waktu terlama ke terbaru)
                const rows = @json($chartRows);
                const labels = rows.map(r => r.waktu);

                // ===== Gauge Indeks Relaksasi =====
                const persen = {{ $terbaru ? round((float) $terbaru->tingkat_relaksasi, 1) : 0 }};
                const status = @json(strtolower($terbaru?->status_klasifikasi ?? ''));

                let warna, mulut, mataBulat = true;
                const s = status.replace(/\s+/g, ' ').trim();
                if (s.includes('stres') || s.includes('stress') || s.includes('akut')) {
                    warna = '#ef4444';                 // merah - cemberut (Stres Akut)
                    mulut = 'M19 48 Q32 32 45 48';
                } else if (s.includes('rileks') || s.includes('relax')) {
                    warna = '#10b981';                 // hijau - tersenyum
                    mulut = 'M20 38 Q32 50 44 38';
                } else {
                    warna = '#f59e0b';                 // kuning - datar (pemulihan)
                    mulut = 'M21 42 L43 42';
                }

                const bgFace = { '#10b981': '#ecfdf5', '#ef4444': '#fef2f2', '#f59e0b': '#fffbeb' }[warna];
                document.getElementById('faceCircle').setAttribute('stroke', warna);
                document.getElementById('faceCircle').setAttribute('fill', bgFace);
                document.getElementById('faceEyeL').setAttribute('fill', warna);
                document.getElementById('faceEyeR').setAttribute('fill', warna);
                document.getElementById('faceMouth').setAttribute('d', mulut);
                document.getElementById('faceMouth').setAttribute('stroke', warna);

                new Chart(document.getElementById('gaugeRelaksasi'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [persen, Math.max(0, 100 - persen)],
                            backgroundColor: [warna, '#e2e8f0'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '78%',
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });

                // ===== Line chart helper =====
                function buatLine(id, label, data, warna, satuan) {
                    new Chart(document.getElementById(id), {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                borderColor: warna,
                                backgroundColor: warna + '22',
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                                tension: 0.35,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => ctx.parsed.y + ' ' + satuan
                                    }
                                }
                            },
                            scales: {
                                x: { ticks: { maxTicksLimit: 4, font: { size: 10 } }, grid: { display: false } },
                                y: { ticks: { maxTicksLimit: 4, font: { size: 10 } }, grid: { color: '#f1f5f9' } }
                            }
                        }
                    });
                }

                buatLine('chartDetak', 'Detak Jantung', rows.map(r => r.hr),   '#ef4444', 'BPM');
                buatLine('chartSuhu',  'Suhu Kulit',    rows.map(r => r.suhu), '#f59e0b', '°C');
                buatLine('chartEda',   'Respons EDA',   rows.map(r => r.eda),  '#3b82f6', 'µS');
            })();
        </script>
    @endpush
</x-layout>
