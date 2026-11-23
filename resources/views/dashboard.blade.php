@php
    $s = $ringkasan['saat_ini'];
    $keliling = 2 * M_PI * 52;
    $kelasHero = [
        'Rileks' => 'from-emerald-700 via-emerald-600 to-teal-500',
        'Stres Akut' => 'from-rose-700 via-rose-600 to-orange-500',
        'Pemulihan' => 'from-amber-600 via-amber-500 to-orange-400',
        'Belum ada data' => 'from-slate-600 via-slate-500 to-slate-400',
    ];
    $kelasBadge = [
        'Rileks' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'Stres Akut' => 'bg-rose-100 text-rose-700 ring-rose-200',
        'Pemulihan' => 'bg-amber-100 text-amber-700 ring-amber-200',
    ];
    $saran = [
        'Rileks' => [
            'id' => 'saran-rileks',
            'judul' => 'Kondisi rileks: pertahankan',
            'poin' => [
                'Lanjutkan aktivitas yang sedang dilakukan, tanpa menambah intensitas.',
                'Atur napas secara santai dan teratur.',
                'Tetap minum air putih secara berkala.',
                'Nikmati suasana sekitar tanpa terburu-buru.',
            ],
        ],
        'Stres Akut' => [
            'id' => 'saran-stres',
            'judul' => 'Stres akut: segera tenangkan diri',
            'poin' => [
                'Hentikan aktivitas, lalu duduk atau berbaring di tempat yang tenang.',
                'Latihan napas: tarik 4 detik, hembuskan perlahan 6 detik, ulangi beberapa menit.',
                'Minum air putih dan kurangi rangsangan (suara, cahaya, keramaian).',
                'Minta petugas atau pendamping menemani sampai kondisi membaik.',
                'Jika tidak membaik, atau muncul pusing, sesak, atau nyeri dada, segera hubungi petugas medis.',
            ],
        ],
        'Pemulihan' => [
            'id' => 'saran-pemulihan',
            'judul' => 'Masa pemulihan: pulih dengan perlahan',
            'poin' => [
                'Lakukan aktivitas ringan, seperti berjalan santai atau peregangan lembut.',
                'Beristirahat cukup dan hindari aktivitas berat dulu.',
                'Minum air putih, dan nikmati musik atau aroma yang menenangkan.',
                'Terus pantau indikasi sampai kembali ke kondisi rileks.',
            ],
        ],
        'Belum ada data' => [
            'id' => 'saran-kosong',
            'judul' => 'Menunggu data sensor',
            'poin' => [
                'Pastikan perangkat sensor terpasang dengan baik dan menyala.',
                'Saran akan muncul otomatis setelah data pertama diterima.',
            ],
        ],
    ];
@endphp

<x-layout>
    <x-slot:title>Beranda</x-slot:title>

    {{-- Panel utama: gauge (kiri) + kartu rata-rata (kanan) --}}
    <section id="hero-box" class="{{ $kelasHero[$s['status']] ?? $kelasHero['Rileks'] }} relative -mx-4 -mt-4 overflow-hidden sm:-mx-6 sm:-mt-6 lg:-mx-8 lg:-mt-8 bg-gradient-to-r text-white shadow-lg flex min-h-[100svh] flex-col">
        {{-- Info sesi aktif (dropdown) --}}
        @if ($sesi)
            <form method="GET" action="{{ url()->current() }}" class="relative z-10 m-4 flex flex-col gap-1.5 text-sm text-white/90 sm:m-6 sm:flex-row sm:items-center sm:gap-2">
                <label for="pilih-sesi">Sesi aktif:</label>
                <select id="pilih-sesi" name="sesi" onchange="this.form.submit()"
                        class="w-full max-w-full rounded-lg border-0 bg-white py-1.5 pl-3 pr-8 text-sm font-semibold text-slate-700 shadow-sm focus:ring-2 focus:ring-white sm:w-auto">
                    @foreach ($daftarSesi as $item)
                        <option value="{{ $item->id }}" @selected($item->id === $sesi->id)>
                            {{ $item->nama_wisatawan }} &middot; {{ $item->lokasi_resort }} &middot; {{ $item->tanggal_sesi->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-700">Tampilkan</button></noscript>
            </form>
        @else
            <p class="relative z-10 m-4 text-sm text-white/80 sm:m-6">Belum ada sesi aktif.</p>
        @endif

        <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-20 right-24 h-48 w-48 rounded-full bg-white/10"></div>

        <div class="relative my-auto grid w-full items-center gap-6 px-4 py-6 sm:gap-8 sm:px-8 lg:grid-cols-2">
            {{-- Kiri: gauge dengan smiley --}}
            <div class="flex flex-col items-center">
                <div class="relative h-48 w-48 rounded-full bg-white p-2 shadow-md sm:h-56 sm:w-56">
                    <svg viewBox="0 0 120 120" class="h-full w-full">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#e2e8f0" stroke-width="10"/>
                        <circle id="gauge-ring" cx="60" cy="60" r="52" fill="none" stroke="#10b981" stroke-width="10"
                                stroke-linecap="round" transform="rotate(-90 60 60)"
                                stroke-dasharray="{{ $keliling }}"
                                stroke-dashoffset="{{ $keliling * (1 - $s['tingkat'] / 100) }}"
                                style="transition: stroke-dashoffset .6s ease"/>

                        {{-- Smiley: Rileks --}}
                        <g id="face-rileks" class="{{ $s['status'] === 'Rileks' ? '' : 'hidden' }}">
                            <circle cx="60" cy="60" r="38" fill="#d1fae5" stroke="#059669" stroke-width="2.5"/>
                            <ellipse cx="48" cy="52" rx="3" ry="5" fill="#065f46"/>
                            <ellipse cx="72" cy="52" rx="3" ry="5" fill="#065f46"/>
                            <path d="M40 64 Q60 90 80 64 Z" fill="#fff" stroke="#065f46" stroke-width="2.5" stroke-linejoin="round"/>
                        </g>
                        {{-- Smiley: Stres Akut --}}
                        <g id="face-stres" class="{{ $s['status'] === 'Stres Akut' ? '' : 'hidden' }}">
                            <circle cx="60" cy="60" r="38" fill="#ffe4e6" stroke="#e11d48" stroke-width="2.5"/>
                            <ellipse cx="48" cy="52" rx="3" ry="5" fill="#9f1239"/>
                            <ellipse cx="72" cy="52" rx="3" ry="5" fill="#9f1239"/>
                            <path d="M44 78 Q60 62 76 78" fill="none" stroke="#9f1239" stroke-width="3" stroke-linecap="round"/>
                        </g>
                        {{-- Smiley: Pemulihan --}}
                        <g id="face-pemulihan" class="{{ $s['status'] === 'Pemulihan' ? '' : 'hidden' }}">
                            <circle cx="60" cy="60" r="38" fill="#fef3c7" stroke="#d97706" stroke-width="2.5"/>
                            <ellipse cx="48" cy="52" rx="3" ry="5" fill="#92400e"/>
                            <ellipse cx="72" cy="52" rx="3" ry="5" fill="#92400e"/>
                            <path d="M45 72 H75" fill="none" stroke="#92400e" stroke-width="3" stroke-linecap="round"/>
                        </g>
                    </svg>
                </div>

                <div class="mt-4 text-center">
                    <span id="gauge-text" class="text-4xl font-extrabold">{{ number_format($s['tingkat'], 0) }}%</span>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-emerald-100">Indeks Relaksasi</p>
                </div>

                <div class="mt-3 flex items-center justify-center gap-2 text-sm">
                    <span class="text-emerald-50">Indikasi:</span>
                    <span id="status-badge"
                          class="rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $kelasBadge[$s['status']] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                        {{ $s['status'] }}
                    </span>
                </div>

                <p class="mt-3 text-xs text-emerald-100">Pembaruan terakhir: <span id="waktu-update">{{ $s['waktu'] }}</span></p>

                {{-- Saran tindakan sesuai indikasi --}}
                <div class="mt-4 w-full max-w-md">
                    @foreach ($saran as $namaStatus => $item)
                        <div id="{{ $item['id'] }}" class="rounded-2xl bg-white/15 p-4 text-left {{ ($saran[$s['status']] ?? $saran['Belum ada data'])['id'] === $item['id'] ? '' : 'hidden' }}">
                            <p class="text-sm font-bold">{{ $item['judul'] }}</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-xs text-emerald-50 sm:text-sm">
                                @foreach ($item['poin'] as $poin)
                                    <li>{{ $poin }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: 3 kartu rata-rata (bertumpuk) --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 rounded-2xl bg-white p-4 text-slate-800 shadow-sm ring-1 ring-slate-200 sm:gap-4 sm:p-5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl sm:h-14 sm:w-14 bg-emerald-100 text-emerald-600">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21c0-8 4-14 15-15 0 11-6 15-13 15M6 21c2-5 5-8 9-10"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Rata-Rata Relaksasi</p>
                        <p class="text-2xl font-bold text-slate-800"><span id="avg-relaksasi">{{ number_format($ringkasan['rata_relaksasi'], 1) }}</span>%</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl bg-white p-4 text-slate-800 shadow-sm ring-1 ring-slate-200 sm:gap-4 sm:p-5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl sm:h-14 sm:w-14 bg-rose-100 text-rose-500">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Rata-Rata Detak Jantung</p>
                        <p class="text-2xl font-bold text-slate-800"><span id="avg-hr">{{ $ringkasan['rata_detak_jantung'] }}</span> <span class="text-sm font-medium text-slate-500">BPM</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl bg-white p-4 text-slate-800 shadow-sm ring-1 ring-slate-200 sm:gap-4 sm:p-5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl sm:h-14 sm:w-14 bg-amber-100 text-amber-600">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V4a2 2 0 1 0-4 0v10.76a4 4 0 1 0 4 0Z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Rata-Rata Suhu Kulit</p>
                        <p class="text-2xl font-bold text-slate-800"><span id="avg-suhu">{{ number_format($ringkasan['rata_suhu_kulit'], 1) }}</span>°C</p>
                    </div>
                </div>
            </div>
        </div>

        <a href="#detail" class="relative mt-6 flex flex-col items-center pb-4 text-xs text-white/80 hover:text-white">
            <span>Gulir untuk melihat tren &amp; log data</span>
            <svg class="mt-1 h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </a>
    </section>

    {{-- Bawah: chart (kiri) + tabel (kanan) --}}
    <section id="detail" class="grid scroll-mt-4 items-start gap-6 lg:grid-cols-2">
        {{-- Kiri bawah: chart --}}
        <div class="min-w-0 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <div class="mb-4 flex flex-wrap items-start justify-between gap-2">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Tren Parameter Wellness</h2>
                    <p class="text-xs text-slate-500">Detak jantung, respons kulit (EDA), dan suhu kulit &middot; 30 pembacaan terakhir</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span> Live
                </span>
            </div>
            <div class="relative h-64 sm:h-80">
                <canvas id="trenChart"></canvas>
            </div>
        </div>

        {{-- Kanan bawah: tabel log terbaru --}}
        <div class="min-w-0 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-100 px-4 py-4 sm:px-6">
                <h2 class="text-base font-bold text-slate-800">Log Data Sensor Terbaru</h2>
                <p class="text-xs text-slate-500">10 pembacaan terakhir dari seluruh sesi</p>
            </div>

            {{-- Mobile: daftar kartu --}}
            <div class="divide-y divide-slate-100 sm:hidden">
                @forelse ($logTerbaru as $log)
                    <div class="px-4 py-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $log->wellnessSession->nama_wisatawan }}</p>
                                <p class="text-xs text-slate-500">{{ $log->recorded_at->format('d M, H:i:s') }}</p>
                            </div>
                            <span class="shrink-0 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $log->badge_class }}">{{ $log->status_klasifikasi }}</span>
                        </div>
                        <dl class="mt-2 grid grid-cols-2 gap-x-3 gap-y-1 text-xs">
                            <div class="flex justify-between"><dt class="text-slate-500">Detak Jantung</dt><dd class="font-medium text-slate-800">{{ $log->detak_jantung }} BPM</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Suhu Kulit</dt><dd class="font-medium text-slate-800">{{ number_format($log->suhu_kulit, 2) }} °C</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">GSR / EDA</dt><dd class="font-medium text-slate-800">{{ number_format($log->gsr_eda, 2) }} µS</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Indeks</dt><dd class="font-medium text-slate-800">{{ number_format($log->tingkat_relaksasi, 1) }}%</dd></div>
                        </dl>
                    </div>
                @empty
                    <p class="px-4 py-10 text-center text-sm text-slate-400">Belum ada data sensor.</p>
                @endforelse
            </div>

            {{-- Tablet/desktop: tabel --}}
            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Waktu</th>
                            <th class="px-4 py-3 font-semibold">Wisatawan</th>
                            <th class="px-4 py-3 font-semibold">Detak Jantung</th>
                            <th class="px-4 py-3 font-semibold">Suhu Kulit</th>
                            <th class="px-4 py-3 font-semibold">GSR / EDA</th>
                            <th class="px-4 py-3 font-semibold">Indeks</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($logTerbaru as $log)
                            <tr class="hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->recorded_at->format('d M, H:i:s') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $log->wellnessSession->nama_wisatawan }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $log->detak_jantung }} BPM</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ number_format($log->suhu_kulit, 2) }} °C</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ number_format($log->gsr_eda, 2) }} µS</td>
                                <td class="px-4 py-3">{{ number_format($log->tingkat_relaksasi, 1) }}%</td>
                                <td class="px-4 py-3">
                                    <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $log->badge_class }}">{{ $log->status_klasifikasi }}</span>
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
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            const awal = @json($ringkasan);
            const URL_DATA = @json(route('dashboard.data', ['sesi' => $sesi?->id]));
            const KELILING = 2 * Math.PI * 52;
            const BADGE_DASAR = 'rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset ';
            const KELAS_BADGE = {
                'Rileks': 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                'Stres Akut': 'bg-rose-100 text-rose-700 ring-rose-200',
                'Pemulihan': 'bg-amber-100 text-amber-700 ring-amber-200',
            };
            const HERO_DASAR = 'relative -mx-4 -mt-4 overflow-hidden sm:-mx-6 sm:-mt-6 lg:-mx-8 lg:-mt-8 bg-gradient-to-r text-white shadow-lg flex min-h-[100svh] flex-col ';
            const KELAS_HERO = {
                'Rileks': 'from-emerald-700 via-emerald-600 to-teal-500',
                'Stres Akut': 'from-rose-700 via-rose-600 to-orange-500',
                'Pemulihan': 'from-amber-600 via-amber-500 to-orange-400',
                'Belum ada data': 'from-slate-600 via-slate-500 to-slate-400',
            };
            const SARAN = {
                'Rileks': 'saran-rileks',
                'Stres Akut': 'saran-stres',
                'Pemulihan': 'saran-pemulihan',
                'Belum ada data': 'saran-kosong',
            };
            const WAJAH = {
                'Rileks': 'face-rileks',
                'Stres Akut': 'face-stres',
                'Pemulihan': 'face-pemulihan',
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

            function tampilWajah(status) {
                Object.values(WAJAH).forEach(id => document.getElementById(id).classList.add('hidden'));
                const aktif = WAJAH[status];
                if (aktif) document.getElementById(aktif).classList.remove('hidden');
            }

            function tampilSaran(status) {
                Object.values(SARAN).forEach(id => document.getElementById(id).classList.add('hidden'));
                document.getElementById(SARAN[status] ?? SARAN['Belum ada data']).classList.remove('hidden');
            }

            function render(d) {
                const s = d.saat_ini;

                isi('avg-relaksasi', d.rata_relaksasi.toFixed(1));
                isi('avg-hr', d.rata_detak_jantung);
                isi('avg-suhu', d.rata_suhu_kulit.toFixed(1));

                isi('waktu-update', s.waktu);
                isi('gauge-text', Math.round(s.tingkat) + '%');

                document.getElementById('gauge-ring').style.strokeDashoffset = KELILING * (1 - s.tingkat / 100);
                tampilWajah(s.status);
                tampilSaran(s.status);
                document.getElementById('hero-box').className = HERO_DASAR + (KELAS_HERO[s.status] ?? KELAS_HERO['Rileks']);

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
