<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WellnessSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemantauanController extends Controller
{
    public function index(): View
    {
        $sesiAktif = WellnessSession::where('status_sesi', 'aktif')
            ->with('latestLog')
            ->withCount('sensorLogs')
            ->latest('tanggal_sesi')
            ->get();

        return view('pemantauan.index', compact('sesiAktif'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_wisatawan' => ['required', 'string', 'max:100'],
            'lokasi_resort' => ['required', 'string', 'max:150'],
            'tanggal_sesi' => ['required', 'date'],
        ]);

        $sesi = WellnessSession::create($data + [
            'user_id' => auth()->id() ?? User::query()->value('id'),
            'status_sesi' => 'aktif',
        ]);

        return redirect()
            ->route('pemantauan.show', $sesi)
            ->with('sukses', 'Sesi wellness untuk '.$sesi->nama_wisatawan.' berhasil dibuat.');
    }

    // Route Model Binding: {wellnessSession}
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
            ->with('sukses', 'Sesi '.$wellnessSession->nama_wisatawan.' telah diselesaikan.');
    }
}