<?php

namespace App\Http\Controllers;

use App\Models\SpaLocation;
use App\Support\GoogleMapsLink;
use Illuminate\Http\Request;

class SpaLocationController extends Controller
{
    public function index()
    {
        return view('lokasi.index', ['lokasis' => SpaLocation::latest()->get()]);
    }

    public function store(Request $request)
    {
        SpaLocation::create($this->validated($request));

        return redirect()->route('lokasi.index');
    }

    public function update(Request $request, SpaLocation $lokasi)
    {
        $lokasi->update($this->validated($request));

        return redirect()->route('lokasi.index');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'spa_name' => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);
    }

    public function destroy(SpaLocation $lokasi)
    {
        $lokasi->delete();

        return redirect()->route('lokasi.index');
    }

    public function parseLink(Request $request)
    {
        $link = $request->validate(['link' => 'required|string|max:2000'])['link'];

        if (! GoogleMapsLink::isAllowed($link)) {
            return response()->json(['message' => 'Masukkan link Google Maps yang diawali https://'], 422);
        }

        try {
            $data = GoogleMapsLink::read($link);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['message' => 'Server gagal membuka link. Coba lagi.'], 422);
        }

        if ($data['latitude'] === null) {
            return response()->json(['message' => 'Koordinat tidak ditemukan. Salin URL lengkap dari address bar Google Maps.'], 422);
        }

        return response()->json($data);
    }
}
