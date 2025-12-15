<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentor>
 */
class MentorFactory extends Factory
{
    public function definition()
    {
        return [
            // Membuat user baru dengan role mentor secara otomatis
            'id_user' => \App\Models\User::factory()->state(['role' => 'mentor']),
            'rekening_bank' => $this->faker->bankAccountNumber(),
            'bank_name' => $this->faker->randomElement(['BCA', 'Mandiri', 'BRI', 'BNI']),
            'keahlian' => $this->faker->jobTitle(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
