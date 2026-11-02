<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Contracts\View\View;

class PerangkatController extends Controller
{
    public function index(): View
    {
        $terakhir = SensorLog::latest('recorded_at')->first();

        // Perangkat dianggap online bila mengirim data dalam 10 menit terakhir.
        $online = $terakhir && $terakhir->recorded_at->gt(now()->subMinutes(10));

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
                'pembacaan' => $terakhir ? $terakhir->detak_jantung.' BPM' : '-',
                'protokol' => 'I2C',
            ],
            [
                'nama' => 'MAX30205',
                'fungsi' => 'Suhu kulit (Skin Temperature)',
                'pembacaan' => $terakhir ? number_format($terakhir->suhu_kulit, 2).' °C' : '-',
                'protokol' => 'I2C',
            ],
            [
                'nama' => 'Grove-GSR',
                'fungsi' => 'Respons resistansi kulit (EDA)',
                'pembacaan' => $terakhir ? number_format($terakhir->gsr_eda, 2).' µS' : '-',
                'protokol' => 'Analog (ADC)',
            ],
        ];

        return view('perangkat.index', [
            'perangkat' => $perangkat,
            'online' => $online,
            'terakhir' => $terakhir,
        ]);
    }
}