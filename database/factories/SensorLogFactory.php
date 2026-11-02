<?php

namespace Database\Factories;

use App\Models\SensorLog;
use App\Models\WellnessSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorLog>
 */
class SensorLogFactory extends Factory
{
    public function definition(): array
    {
        return array_merge(
            [
                'wellness_session_id' => WellnessSession::factory(),
                'recorded_at' => now(),
            ],
            $this->profil($this->faker->randomElement(SensorLog::STATUS))
        );
    }

    /**
     * State: paksa log menghasilkan nilai sensor sesuai kelas klasifikasi.
     */
    public function klasifikasi(string $status): static
    {
        return $this->state(fn () => $this->profil($status));
    }

    /**
     * Rentang nilai realistis tiap kelas: [detak jantung, suhu kulit, GSR/EDA, indeks relaksasi].
     */
    private function profil(string $status): array
    {
        [$hr, $suhu, $gsr, $indeks] = match ($status) {
            'Stres Akut' => [[92, 118], [32.0, 33.4], [8.0, 14.0], [12, 40]],
            'Pemulihan' => [[70, 86], [33.3, 34.3], [4.0, 8.0], [50, 74]],
            default => [[56, 72], [33.8, 35.2], [1.5, 4.5], [76, 96]],
        };

        return [
            'detak_jantung' => $this->faker->numberBetween($hr[0], $hr[1]),
            'suhu_kulit' => $this->faker->randomFloat(2, $suhu[0], $suhu[1]),
            'gsr_eda' => $this->faker->randomFloat(2, $gsr[0], $gsr[1]),
            'tingkat_relaksasi' => $this->faker->randomFloat(2, $indeks[0], $indeks[1]),
            'status_klasifikasi' => $status,
        ];
    }
}