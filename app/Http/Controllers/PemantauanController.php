<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemantauanController extends Controller
{
    public function index(Request $request): View
    {
        $query = WellnessSession::where('status_sesi', 'aktif')
            ->with('latestLog')
            ->withCount('sensorLogs')
            ->latest('tanggal_sesi');

        // 1. Filter berdasarkan Nama Wisatawan (jika diisi)
        if ($request->filled('nama_wisatawan')) {
            $query->where('nama_wisatawan', $request->nama_wisatawan);
        }

        // 2. Filter berdasarkan Lokasi Resort (jika diisi)
        if ($request->filled('lokasi_resort')) {
            $query->where('lokasi_resort', $request->lokasi_resort);
        }

        // 3. Filter berdasarkan Tanggal Sesi (jika diisi)
        if ($request->filled('tanggal_sesi')) {
            $query->whereDate('tanggal_sesi', $request->tanggal_sesi);
        }

        $sesiAktif = $query->get();

        // Data untuk opsi dropdown pilihan
        $daftarWisatawan = WellnessSession::select('nama_wisatawan')
            ->distinct()
            ->pluck('nama_wisatawan');

        $daftarResort = WellnessSession::select('lokasi_resort')
            ->distinct()
            ->pluck('lokasi_resort');

        return view('pemantauan.index', compact('sesiAktif', 'daftarWisatawan', 'daftarResort'));
    }

    public function show(WellnessSession $wellnessSession): View
    {
        return view('pemantauan.show', [
            'sesi' => $wellnessSession,
            'terbaru' => $wellnessSession->latestLog,
            'logs' => $wellnessSession->sensorLogs()->latest('recorded_at')->paginate(15),
        ]);
    }

    public function selesai(WellnessSession $wellnessSession): RedirectResponse
    {
        $wellnessSession->update(['status_sesi' => 'selesai']);

        return redirect()
            ->route('pemantauan.index')
            ->with('sukses', 'Sesi ' . $wellnessSession->nama_wisatawan . ' telah diselesaikan.');
    }
}

