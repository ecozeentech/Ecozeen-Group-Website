<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'type' => 'subsidiary',
            'category' => fake()->randomElement(['Financial Technology', 'Enterprise IT Services', 'Leisure & Entertainment', 'Mining & Commodities', 'Agriculture']),
            'short_description' => fake()->sentence(16),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'status' => 'published',
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }
}
