<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory(),
            'id_kelas' => \App\Models\Kelas::factory(),
            'kategori' => $this->faker->randomElement(['Bug', 'Konten Error', 'Pembayaran', 'Lainnya']),
            'keterangan' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['open', 'resolved']),
        ];
    }
}
