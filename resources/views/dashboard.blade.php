@php
    $s = $ringkasan['saat_ini'];
    $keliling = 2 * M_PI * 52;
    $kelasBadge = [
        'Rileks' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'Stres Akut' => 'bg-rose-100 text-rose-700 ring-rose-200',
        'Pemulihan' => 'bg-amber-100 text-amber-700 ring-amber-200',
    ];
@endphp

<x-layout>
    <x-slot:title>Beranda</x-slot:title>

    {{-- Banner --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-500 p-8 text-white shadow-lg">
        <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-20 right-24 h-48 w-48 rounded-full bg-white/10"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-100">Smart Wellness Tourism</p>
        <h1 class="mt-2 max-w-2xl text-2xl font-bold leading-snug sm:text-3xl">
            Pemantauan Respons Relaksasi Wisatawan Berbasis IoT
        </h1>
        <p class="mt-3 text-sm text-emerald-50">
            Sesi dipantau: <span class="font-semibold">{{ $ringkasan['sesi'] }}</span>
            @if ($sesi) &middot; {{ $sesi->lokasi_resort }} @endif
        </p>
    </section>

    {{-- Metric cards --}}
    <section class="grid gap-4 sm:grid-cols-3">
        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21c0-8 4-14 15-15 0 11-6 15-13 15M6 21c2-5 5-8 9-10"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Rata-Rata Relaksasi</p>
                <p class="text-2xl font-bold text-slate-800"><span id="avg-relaksasi">{{ number_format($ringkasan['rata_relaksasi'], 1) }}</span>%</p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-rose-500">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Rata-Rata Detak Jantung</p>
                <p class="text-2xl font-bold text-slate-800"><span id="avg-hr">{{ $ringkasan['rata_detak_jantung'] }}</span> <span class="text-sm font-medium text-slate-500">BPM</span></p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V4a2 2 0 1 0-4 0v10.76a4 4 0 1 0 4 0Z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Rata-Rata Suhu Kulit</p>
                <p class="text-2xl font-bold text-slate-800"><span id="avg-suhu">{{ number_format($ringkasan['rata_suhu_kulit'], 1) }}</span>°C</p>
            </div>
        </div>
    </section>

    {{-- Widget utama: 2 kolom --}}
    <section class="grid gap-6 lg:grid-cols-3">
        {{-- Kiri: chart --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Tren Parameter Wellness</h2>
                    <p class="text-xs text-slate-500">Detak jantung, respons kulit (EDA), dan suhu kulit &middot; 30 pembacaan terakhir</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span> Live
                </span>
            </div>
            <div class="relative h-80">
                <canvas id="trenChart"></canvas>
            </div>
        </div>

        {{-- Kanan: status saat ini --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-base font-bold text-slate-800">Status Wellness Saat Ini</h2>
            <p class="text-xs text-slate-500">Pembaruan terakhir: <span id="waktu-update">{{ $s['waktu'] }}</span></p>

            <div class="relative mx-auto mt-5 h-44 w-44">
                <svg viewBox="0 0 120 120" class="h-full w-full">
                    <circle cx="60" cy="60" r="52" fill="none" stroke="#e2e8f0" stroke-width="10"/>
                    <circle id="gauge-ring" cx="60" cy="60" r="52" fill="none" stroke="#10b981" stroke-width="10"
                            stroke-linecap="round" transform="rotate(-90 60 60)"
                            stroke-dasharray="{{ $keliling }}"
                            stroke-dashoffset="{{ $keliling * (1 - $s['tingkat'] / 100) }}"
                            style="transition: stroke-dashoffset .6s ease"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="gauge-text" class="text-3xl font-extrabold text-slate-800">{{ number_format($s['tingkat'], 0) }}%</span>
                    <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Indeks Relaksasi</span>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-center gap-2 text-sm">
                <span class="text-slate-500">Indikasi:</span>
                <span id="status-badge"
                      class="rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $kelasBadge[$s['status']] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                    {{ $s['status'] }}
                </span>
            </div>

            <dl class="mt-5 divide-y divide-slate-100 text-sm">
                <div class="flex items-center justify-between py-2.5">
                    <dt class="text-slate-500">Detak Jantung</dt>
                    <dd class="font-semibold text-slate-800"><span id="now-hr">{{ $s['detak_jantung'] }}</span> BPM</dd>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <dt class="text-slate-500">Suhu Kulit</dt>
                    <dd class="font-semibold text-slate-800"><span id="now-suhu">{{ number_format($s['suhu_kulit'], 2) }}</span> °C</dd>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <dt class="text-slate-500">Respons EDA</dt>
                    <dd class="font-semibold text-slate-800"><span id="now-gsr">{{ number_format($s['gsr_eda'], 2) }}</span> µS</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- Tabel log terbaru --}}
    <section class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">Log Data Sensor Terbaru</h2>
            <p class="text-xs text-slate-500">10 pembacaan terakhir dari seluruh sesi</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Waktu</th>
                        <th class="px-6 py-3 font-semibold">Wisatawan</th>
                        <th class="px-6 py-3 font-semibold">Detak Jantung</th>
                        <th class="px-6 py-3 font-semibold">Suhu Kulit</th>
                        <th class="px-6 py-3 font-semibold">GSR / EDA</th>
                        <th class="px-6 py-3 font-semibold">Indeks</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logTerbaru as $log)
                        <tr class="hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $log->recorded_at->format('d M, H:i:s') }}</td>
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $log->wellnessSession->nama_wisatawan }}</td>
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
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada data sensor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            const awal = @json($ringkasan);
            const URL_DATA = @json(route('dashboard.data'));
            const KELILING = 2 * Math.PI * 52;
            const BADGE_DASAR = 'rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset ';
            const KELAS_BADGE = {
                'Rileks': 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                'Stres Akut': 'bg-rose-100 text-rose-700 ring-rose-200',
                'Pemulihan': 'bg-amber-100 text-amber-700 ring-amber-200',
            };

            const chart = new Chart(document.getElementById('trenChart'), {
                type: 'line',
                data: {
                    labels: awal.tren.labels,
                    datasets: [
                        {
                            label: 'Detak Jantung (BPM)',
                            data: awal.tren.detak_jantung,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.12)',
                            yAxisID: 'y',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 2,
                        },
                        {
                            label: 'Respons Kulit EDA (µS)',
                            data: awal.tren.gsr_eda,
                            borderColor: '#0ea5e9',
                            backgroundColor: '#0ea5e9',
                            yAxisID: 'y1',
                            tension: 0.35,
                            pointRadius: 2,
                        },
                        {
                            label: 'Suhu Kulit (°C)',
                            data: awal.tren.suhu_kulit,
                            borderColor: '#f59e0b',
                            backgroundColor: '#f59e0b',
                            yAxisID: 'y2',
                            tension: 0.35,
                            pointRadius: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { position: 'left', title: { display: true, text: 'BPM' } },
                        y1: { position: 'right', title: { display: true, text: 'µS' }, grid: { drawOnChartArea: false } },
                        y2: { position: 'right', display: false, grid: { drawOnChartArea: false } },
                    },
                },
            });

            const isi = (id, nilai) => { document.getElementById(id).textContent = nilai; };

            function render(d) {
                const s = d.saat_ini;

                isi('avg-relaksasi', d.rata_relaksasi.toFixed(1));
                isi('avg-hr', d.rata_detak_jantung);
                isi('avg-suhu', d.rata_suhu_kulit.toFixed(1));

                isi('waktu-update', s.waktu);
                isi('gauge-text', Math.round(s.tingkat) + '%');
                isi('now-hr', s.detak_jantung);
                isi('now-suhu', Number(s.suhu_kulit).toFixed(2));
                isi('now-gsr', Number(s.gsr_eda).toFixed(2));

                document.getElementById('gauge-ring').style.strokeDashoffset = KELILING * (1 - s.tingkat / 100);

                const badge = document.getElementById('status-badge');
                badge.textContent = s.status;
                badge.className = BADGE_DASAR + (KELAS_BADGE[s.status] ?? 'bg-slate-100 text-slate-600 ring-slate-200');

                chart.data.labels = d.tren.labels;
                chart.data.datasets[0].data = d.tren.detak_jantung;
                chart.data.datasets[1].data = d.tren.gsr_eda;
                chart.data.datasets[2].data = d.tren.suhu_kulit;
                chart.update();
            }

            // Polling tiap 5 detik untuk memperbarui kartu, gauge, dan chart.
            setInterval(async () => {
                try {
                    const res = await fetch(URL_DATA, { headers: { Accept: 'application/json' } });
                    if (res.ok) render(await res.json());
                } catch (e) {
                    console.error('Gagal memuat data sensor:', e);
                }
            }, 5000);
        </script>
    @endpush
</x-layout>