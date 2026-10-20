<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'template' => 'default',
            'status' => 'draft',
            'is_homepage' => false,
            'no_index' => false,
        ];
    }

    public function published(): static
    {
        return $this->state([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function homepage(): static
    {
        return $this->state(['is_homepage' => true]);
    }
}
