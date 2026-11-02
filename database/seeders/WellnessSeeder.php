<?php

namespace Database\Seeders;

use App\Models\SensorLog;
use App\Models\User;
use App\Models\WellnessSession;
use Illuminate\Database\Seeder;

class WellnessSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Wellness',
            'email' => 'admin@balisense.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Petugas Resort',
            'email' => 'petugas@balisense.test',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $wisatawan = [
            ['Emma Johnson', 'Ubud Hanging Gardens'],
            ['Kenji Tanaka', 'Tegallalang Retreat'],
            ['Sophie Martin', 'Sayan Jungle Resort'],
            ['Lucas Meier', 'Kintamani Spa Haven'],
            ['Anna Kowalski', 'Ubud Hanging Gardens'],
            ['Daniel Smith', 'Sayan Jungle Resort'],
        ];

        foreach ($wisatawan as $i => [$nama, $resort]) {
            $aktif = $i === 0;      // sesi pertama masih berjalan (data real-time)
            $total = $aktif ? 24 : 36;

            $sesi = WellnessSession::create([
                'user_id' => $admin->id,
                'nama_wisatawan' => $nama,
                'lokasi_resort' => $resort,
                'tanggal_sesi' => now()->subDays($aktif ? 0 : $i)->toDateString(),
                'status_sesi' => $aktif ? 'aktif' : 'selesai',
            ]);

            $mulai = $aktif
                ? now()->subMinutes($total - 1)
                : now()->subDays($i)->setTime(9, 0);

            // Fase: baseline (Stres Akut) -> meditasi (Rileks) -> pemulihan.
            for ($n = 0; $n < $total; $n++) {
                $status = match (true) {
                    $n < 12 => 'Stres Akut',
                    $n < 24 => 'Rileks',
                    default => 'Pemulihan',
                };

                SensorLog::factory()
                    ->klasifikasi($status)
                    ->create([
                        'wellness_session_id' => $sesi->id,
                        'recorded_at' => $mulai->copy()->addMinutes($n),
                    ]);
            }
        }
    }
}