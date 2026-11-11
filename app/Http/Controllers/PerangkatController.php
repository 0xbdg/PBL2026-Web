<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\Device;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class PerangkatController extends Controller
{
    public function index(): View
    {

        $terakhir = SensorLog::latest('recorded_at')->first();

        $online = $terakhir && $terakhir->recorded_at->gt(now()->subMinutes(10));
        /*
        $perangkat = [
            [
                'nama' => 'ESP32 DevKit',
                'fungsi' => 'Mikrokontroler & gateway Wi-Fi',
                'pembacaan' => $online ? 'Mengirim data' : 'Tidak ada data',
                'protokol' => 'HTTP / MQTT via Wi-Fi',
            ],
            [
                'nama' => 'MAX30102',
                'fungsi' => 'Detak jantung (Heart Rate)',
                'pembacaan' => $terakhir ? $terakhir->detak_jantung . ' BPM' : '-',
                'protokol' => 'I2C',
            ],
            [
                'nama' => 'MAX30205',
                'fungsi' => 'Suhu kulit (Skin Temperature)',
                'pembacaan' => $terakhir ? number_format($terakhir->suhu_kulit, 2) . ' °C' : '-',
                'protokol' => 'I2C',
            ],
            [
                'nama' => 'Grove-GSR',
                'fungsi' => 'Respons resistansi kulit (EDA)',
                'pembacaan' => $terakhir ? number_format($terakhir->gsr_eda, 2) . ' µS' : '-',
                'protokol' => 'Analog (ADC)',
            ],
        ];
        */
        $device = Device::latest()->paginate(5);

        return view('perangkat.index', [
            'perangkat' => $device,
            'online' => $online,
            //'terakhir' => $terakhir,
        ]);
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'device_uid' => ['required', 'string', 'max:100'],
            'device_name' => ['required', 'string', 'max:100']
        ]);

        $device = Device::create($validate);

        return redirect()->route("perangkat.index", $device)->with("Sukses", $device->device_name . " berhasil ditambahkan");
    }

    public function destroy(Device $perangkat)
    {
        $perangkat->delete();

        return redirect()->route("perangkat.index")->with("Sukses", $perangkat->device_name . " berhasil dihapus");
    }
}

