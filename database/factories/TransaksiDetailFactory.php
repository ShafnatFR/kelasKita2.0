<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiDetail>
 */
class TransaksiDetailFactory extends Factory
{
    public function definition()
    {
        return [
            'id_transaksi' => \App\Models\Transaksi::factory(),
            'id_kelas' => \App\Models\Kelas::factory(),
            'harga_saat_beli' => $this->faker->numberBetween(50, 1000) * 1000,
        ];
    }
}
