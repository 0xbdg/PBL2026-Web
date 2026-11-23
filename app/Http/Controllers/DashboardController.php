<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\WellnessSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $daftarSesi = $this->daftarSesiAktif();
        $sesi = $this->pilihSesi($daftarSesi, $request->integer('sesi'));

        return view('dashboard', [
            'sesi' => $sesi,
            'daftarSesi' => $daftarSesi,
            'ringkasan' => $this->ringkasan($sesi),
            'logTerbaru' => SensorLog::with('wellnessSession')
                ->latest('recorded_at')
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Endpoint JSON untuk polling real-time di dashboard.
     * Menerima ?sesi={id} agar polling mengikuti sesi yang dipilih di dropdown.
     */
    public function data(Request $request): JsonResponse
    {
        $sesi = $this->pilihSesi($this->daftarSesiAktif(), $request->integer('sesi'));

        return response()->json($this->ringkasan($sesi));
    }

    /**
     * Semua sesi berstatus "aktif" (untuk isi dropdown), terbaru di atas.
     */
    private function daftarSesiAktif(): Collection
    {
        return WellnessSession::where('status_sesi', 'aktif')
            ->latest('tanggal_sesi')
            ->latest('id')
            ->get();
    }

    /**
     * Pakai sesi yang dipilih jika masih aktif; jika tidak, ambil sesi aktif terbaru.
     * Jika tidak ada sesi aktif sama sekali, hasilnya null (dashboard kosong).
     */
    private function pilihSesi(Collection $daftarSesi, int $idDipilih): ?WellnessSession
    {
        return ($idDipilih ? $daftarSesi->firstWhere('id', $idDipilih) : null)
            ?? $daftarSesi->first();
    }

    private function ringkasan(?WellnessSession $sesi): array
    {
        // Tanpa sesi aktif, jangan query log sama sekali (hindari "IS NULL" yang bisa cocok ke data lain).
        $query = SensorLog::where('wellness_session_id', $sesi?->id ?? 0);

        $terbaru = (clone $query)->latest('recorded_at')->first();
        $tren = (clone $query)->latest('recorded_at')->take(30)->get()->reverse()->values();

        return [
            'sesi' => $sesi?->nama_wisatawan ?? '-',
            'rata_relaksasi' => round((float) $query->avg('tingkat_relaksasi'), 1),
            'rata_detak_jantung' => round((float) $query->avg('detak_jantung')),
            'rata_suhu_kulit' => round((float) $query->avg('suhu_kulit'), 1),
            'rata_gsr_eda' => round((float) $query->avg('gsr_eda'), 2),
            'saat_ini' => [
                'tingkat' => $terbaru?->tingkat_relaksasi ?? 0,
                'status' => $terbaru?->status_klasifikasi ?? 'Belum ada data',
                'detak_jantung' => $terbaru?->detak_jantung ?? 0,
                'suhu_kulit' => $terbaru?->suhu_kulit ?? 0,
                'gsr_eda' => $terbaru?->gsr_eda ?? 0,
                'waktu' => $terbaru?->recorded_at?->format('H:i:s') ?? '-',
            ],
            'tren' => [
                'labels' => $tren->map(
                    fn($log) => $log->recorded_at instanceof \Carbon\CarbonInterface
                        ? $log->recorded_at->format('H:i')
                        : '-'
                )->all(),
                'detak_jantung' => $tren->pluck('detak_jantung')->all(),
                'gsr_eda' => $tren->pluck('gsr_eda')->all(),
                'suhu_kulit' => $tren->pluck('suhu_kulit')->all(),
            ],
        ];
    }
}
