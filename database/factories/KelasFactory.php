<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    public function definition()
    {
        $judul = $this->faker->sentence(3);
        return [
            'id_mentor' => \App\Models\Mentor::factory(),
            'nama_kelas' => rtrim($judul, '.'),
            'slug' => \Illuminate\Support\Str::slug($judul),
            'kategori' => $this->faker->randomElement(['Web Development', 'Design', 'Data Science', 'Marketing']),
            'harga' => $this->faker->numberBetween(50, 1000) * 1000, // Kelipatan 1000 (ex: 150000)
            'thumbnail' => 'thumbnails/course-' . $this->faker->numberBetween(1, 5) . '.jpg',
            'description' => $this->faker->paragraph(5),
            'status_publikasi' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
