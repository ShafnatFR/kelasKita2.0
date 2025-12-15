<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory()->state(['role' => 'student']),
            'id_kelas' => \App\Models\Kelas::factory(),
            'bintang' => $this->faker->numberBetween(1, 5),
            'isi_review' => $this->faker->paragraph(2),
        ];
    }
}
