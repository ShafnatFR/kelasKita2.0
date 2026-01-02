<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetodePembayaran>
 */
class MetodePembayaranFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_metode' => $this->faker->randomElement(['BCA Virtual Account', 'GoPay', 'OVO', 'Credit Card', 'Indomaret']),
            'nomor_rekening' => $this->faker->creditCardNumber(),
            'nama_pemilik' => 'PT Kelas Kita Indonesia',
            'is_active' => true,
        ];
    }
}
