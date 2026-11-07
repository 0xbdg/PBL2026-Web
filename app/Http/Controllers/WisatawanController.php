<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;
use App\Models\User;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        return view('wisatawan.tambah');
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

    public function destroy(WellnessSession $wisatawan)
    {
        $wisatawan->delete();
        return redirect()->route("wisatawan.index")->with('sukses', 'Sesi wellness untuk ' . $wisatawan->nama_wisatawan . ' berhasil di hapus');
    }
}
