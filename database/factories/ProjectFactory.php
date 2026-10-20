<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'summary' => fake()->sentence(16),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'outcomes' => '<p>'.fake()->paragraph().'</p>',
            'status' => 'published',
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
