<?php

namespace Database\Factories;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MediaAsset>
 */
class MediaAssetFactory extends Factory
{
    public function definition(): array
    {
        $filename = Str::uuid()->toString().'.jpg';

        return [
            'disk' => 'public',
            'path' => 'media/'.$filename,
            'filename' => $filename,
            'original_filename' => fake()->word().'.jpg',
            'extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(1000, 500000),
            'width' => 800,
            'height' => 600,
            'alt_text' => fake()->sentence(4),
        ];
    }
}
