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
            'metode' => $this->faker->randomElement(['BCA Virtual Account', 'GoPay', 'OVO', 'Credit Card', 'Indomaret']),
            'no_rek' => $this->faker->creditCardNumber(),
            'nama_pemilik' => 'PT Kelas Kita Indonesia',
            'is_active' => true,
        ];
    }
}
