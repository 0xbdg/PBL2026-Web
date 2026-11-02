<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WellnessSession>
 */
class WellnessSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama_wisatawan' => fake('id_ID')->name(),
            'lokasi_resort' => fake()->randomElement([
                'Ubud Hanging Gardens',
                'Tegallalang Retreat',
                'Sayan Jungle Resort',
                'Kintamani Spa Haven',
            ]),
            'tanggal_sesi' => fake()->dateTimeBetween('-7 days', 'now')->format('Y-m-d'),
            'status_sesi' => 'selesai',
        ];
    }
}