<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgressSubMateri>
 */
class ProgressSubMateriFactory extends Factory
{
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory()->state(['role' => 'student']),
            'id_kelas' => \App\Models\Kelas::factory(),
            'id_sub_materi' => \App\Models\SubMateri::factory(),
            'is_completed' => $this->faker->boolean(50), // 50% chance true/false
        ];
    }
}
