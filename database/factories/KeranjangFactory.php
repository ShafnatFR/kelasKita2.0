<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Keranjang>
 */
class KeranjangFactory extends Factory
{
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory()->state(['role' => 'student']),
            'id_kelas' => \App\Models\Kelas::factory(),
            'created_at' => now(),
        ];
    }
}
