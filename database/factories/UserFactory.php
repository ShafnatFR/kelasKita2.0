<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Default password
            'role' => $this->faker->randomElement(['student', 'student', 'student', 'mentor', 'admin']), // Lebih banyak student
            'deskripsi' => $this->faker->sentence(),
            'foto_profil' => 'avatars/default.jpg',
            'status' => $this->faker->randomElement(['active', 'active', 'inactive']),
            'created_at' => now(),
        ];
    }
}
