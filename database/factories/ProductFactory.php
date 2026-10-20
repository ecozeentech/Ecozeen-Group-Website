<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->unique()->words(2, true),
            'tagline' => fake()->sentence(8),
            'short_description' => fake()->sentence(16),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'features' => [fake()->words(3, true), fake()->words(3, true)],
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
