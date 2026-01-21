<?php

namespace Database\Factories;

use App\Models\Tags;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'offer_received', 'complete', 'canceled']),
            'user_id' => \App\Models\User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\project $project) {
            // Attach 1-5 random existing tags (adjust count as needed)
            $tags = Tags::inRandomOrder()->take(rand(1, 3))->get();
            $project->tags()->attach($tags);
        });
    }
}
