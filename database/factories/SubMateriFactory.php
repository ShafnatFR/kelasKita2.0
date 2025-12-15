<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubMateri>
 */
class SubMateriFactory extends Factory
{
    public function definition()
    {
        return [
            'id_materi' => \App\Models\Materi::factory(),
            // Default null, nanti bisa di-override pakai state
            'id_video' => null,
            'id_dokumen' => null,
            'urutan' => $this->faker->numberBetween(1, 5),
            'judul_sub' => $this->faker->sentence(5),
            'teks_pembelajaran' => $this->faker->paragraph(3),
        ];
    }
}
