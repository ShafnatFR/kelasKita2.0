<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materi>
 */
class MateriFactory extends Factory
{
    public function definition()
    {
        return [
            'id_kelas' => \App\Models\Kelas::factory(),
            'urutan' => $this->faker->numberBetween(1, 10),
            'judul_materi' => $this->faker->sentence(4),
        ];
    }
}
