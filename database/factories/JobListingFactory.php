<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->jobTitle(),
            'employment_type' => 'full_time',
            'description' => '<p>'.fake()->paragraph().'</p>',
            'status' => 'open',
        ];
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed']);
    }
}
