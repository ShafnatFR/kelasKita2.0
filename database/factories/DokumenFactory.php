<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dokumen>
 */
class DokumenFactory extends Factory
{
    public function definition()
    {
        return [
            'file_path' => 'docs/modul-' . $this->faker->uuid() . '.pdf',
            'tipe_file' => 'pdf',
        ];
    }
}
