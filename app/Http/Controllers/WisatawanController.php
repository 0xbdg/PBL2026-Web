<?php

namespace App\Http\Controllers;

use App\Models\WellnessSession;
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
}