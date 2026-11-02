<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\WellnessSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $sesi = $this->sesiUtama();

        return view('dashboard', [
            'sesi' => $sesi,
            'ringkasan' => $this->ringkasan($sesi),
            'logTerbaru' => SensorLog::with('wellnessSession')
                ->latest('recorded_at')
                ->take(10)
                ->get(),
        ]);
    }

    /**
     * Endpoint JSON untuk polling real-time di dashboard.
     */
    public function data(): JsonResponse
    {
        return response()->json($this->ringkasan($this->sesiUtama()));
    }

    private function sesiUtama(): ?WellnessSession
    {
        return WellnessSession::where('status_sesi', 'aktif')->latest('tanggal_sesi')->latest('id')->first()
            ?? WellnessSession::latest('tanggal_sesi')->latest('id')->first();
    }

    private function ringkasan(?WellnessSession $sesi): array
    {
        $query = SensorLog::where('wellness_session_id', $sesi?->id);

        $terbaru = (clone $query)->latest('recorded_at')->first();
        $tren = (clone $query)->latest('recorded_at')->take(30)->get()->reverse()->values();

        return [
            'sesi' => $sesi?->nama_wisatawan ?? '-',
            'rata_relaksasi' => round((float) $query->avg('tingkat_relaksasi'), 1),
            'rata_detak_jantung' => round((float) $query->avg('detak_jantung')),
            'rata_suhu_kulit' => round((float) $query->avg('suhu_kulit'), 1),
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
                    fn ($log) => $log->recorded_at instanceof \Carbon\CarbonInterface
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