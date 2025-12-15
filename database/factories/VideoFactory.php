<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    public function definition()
    {
        return [
            'file_path' => 'videos/sample-' . $this->faker->uuid() . '.mp4',
            'durasi' => $this->faker->numberBetween(5, 60) . ':' . $this->faker->numberBetween(10, 59),
        ];
    }
}
