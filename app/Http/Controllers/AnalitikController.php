<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class AnalitikController extends Controller
{
    /**
     * Kolom => [label, satuan, apakah nilai lebih tinggi = lebih baik (null = netral)]
     */
    private const METRIK = [
        'tingkat_relaksasi' => ['Indeks Relaksasi', '%', true],
        'detak_jantung' => ['Detak Jantung', 'BPM', false],
        'suhu_kulit' => ['Suhu Kulit', '°C', null],
        'gsr_eda' => ['Respons EDA', 'µS', false],
    ];

    public function index(): View
    {
        $sesiSelesai = WellnessSession::where('status_sesi', 'selesai')
            ->with(['sensorLogs' => fn ($q) => $q->orderBy('recorded_at')])
            ->latest('tanggal_sesi')
            ->latest('id')
            ->take(10)
            ->get();

        // "Sebelum" = 5 pembacaan awal sesi, "Sesudah" = 5 pembacaan akhir sesi.
        $rekap = $sesiSelesai
            ->filter(fn ($sesi) => $sesi->sensorLogs->count() >= 10)
            ->map(fn ($sesi) => [
                'sesi' => $sesi,
                'sebelum' => $this->rata($sesi->sensorLogs->take(5)),
                'sesudah' => $this->rata($sesi->sensorLogs->take(-5)),
            ])
            ->values();

        $ringkasan = collect(self::METRIK)->map(function ($m, $kolom) use ($rekap) {
            $sebelum = round((float) $rekap->avg("sebelum.$kolom"), 2);
            $sesudah = round((float) $rekap->avg("sesudah.$kolom"), 2);
            $selisih = round($sesudah - $sebelum, 2);

            return [
                'label' => $m[0],
                'satuan' => $m[1],
                'sebelum' => $sebelum,
                'sesudah' => $sesudah,
                'selisih' => $selisih,
                'baik' => $m[2] === null ? null : ($m[2] ? $selisih > 0 : $selisih < 0),
            ];
        });

        return view('analitik.index', [
            'rekap' => $rekap,
            'ringkasan' => $ringkasan,
            'metrik' => self::METRIK,
        ]);
    }

    private function rata(Collection $logs): array
    {
        return collect(self::METRIK)
            ->map(fn ($m, $kolom) => round((float) $logs->avg($kolom), 2))
            ->all();
    }
}