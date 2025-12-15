<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory()->state(['role' => 'student']),
            'id_mp' => \App\Models\MetodePembayaran::factory(),
            'kode_invoice' => 'INV/' . date('Ymd') . '/' . $this->faker->unique()->numerify('#####'),
            'total_harga' => $this->faker->numberBetween(100, 2000) * 1000,
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'bukti_bayar' => null, // Default null
            'tgl_transaksi' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
