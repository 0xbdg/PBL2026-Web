<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;
use App\Models\User;
use App\Models\Device;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WisatawanController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));

        $daftarSesi = WellnessSession::with('latestLog')
            ->withCount('sensorLogs')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_wisatawan', 'like', "%{$keyword}%")
                        ->orWhere('lokasi_resort', 'like', "%{$keyword}%");
                });
            })
            ->latest('tanggal_sesi')
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        return view('wisatawan.index', [
            'daftarSesi' => $daftarSesi,
            'keyword' => $keyword,
        ]);
    }
    public function create()
    {
        $devices = Device::withExists([
            'sessions as sedang_dipakai' => fn($q) => $q->where('status_sesi', 'aktif'),
        ])->orderBy('device_name')->get();
        return view('wisatawan.tambah', compact('devices'));
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_wisatawan' => ['required', 'string', 'max:100'],
            'lokasi_resort' => ['required', 'string', 'max:150'],
            'tanggal_sesi' => ['required', 'date'],
        ]);

        $session = WellnessSession::create($validate + [
            'user_id' => auth()->id() ?? User::query()->value('id'),
            'status_sesi' => 'aktif',
        ]);
        return redirect()->route('wisatawan.index', $session)->with('sukses', 'Sesi wellness untuk ' . $session->nama_wisatawan . ' berhasil dibuat.');
    }

    public function edit(WellnessSession $wisatawan)
    {
        if ($wisatawan->status_sesi === 'selesai') {
            return redirect()->route('wisatawan.index')
                ->with('error', 'Sesi yang sudah selesai tidak dapat diedit.');
        }

        $devices = Device::withExists([
            'sessions as sedang_dipakai' => fn($q) => $q
                ->where('status_sesi', 'aktif')
                ->where('id', '!=', $wisatawan->id),
        ])->orderBy('device_name')->get();

        return view('wisatawan.edit', [
            'sesi'    => $wisatawan,
            'devices' => $devices,
        ]);
    }

    public function update(Request $request, WellnessSession $wisatawan)
    {
        if ($wisatawan->status_sesi === 'selesai') {
            return redirect()->route('wisatawan.index')
                ->with('error', 'Sesi yang sudah selesai tidak dapat diedit.');
        }

        $validate = $request->validate([
            'nama_wisatawan' => ['required', 'string', 'max:100'],
            'lokasi_resort'  => ['required', 'string', 'max:150'],
            'tanggal_sesi'   => ['required', 'date'],
            'device_id'      => [
                'required',
                'exists:devices,id',
                Rule::unique('wellness_sessions', 'device_id')
                    ->where('status_sesi', 'aktif')
                    ->ignore($wisatawan->id),
            ],
        ], [
            'device_id.unique' => 'Device ini sedang dipakai oleh sesi wisatawan lain.',
        ]);

        $wisatawan->update($validate);

        return redirect()->route('wisatawan.index')
            ->with('sukses', 'Sesi wellness untuk ' . $wisatawan->nama_wisatawan . ' berhasil diperbarui.');
    }

    public function destroy(WellnessSession $wisatawan)
    {
        $wisatawan->delete();
        return redirect()->route("wisatawan.index")->with('sukses', 'Sesi wellness untuk ' . $wisatawan->nama_wisatawan . ' berhasil di hapus');
    }
}
