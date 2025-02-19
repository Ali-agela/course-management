<?php

namespace Database\Factories;
use App\Models\Course;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['web development', 'mobile development', 'networking', 'security', 'data science', 'machine learning', 'AI', 'blockchain', 'backend', 'frontend']),
            'price' => $this->faker->numberBetween(100, 1000),
            'duration' => $this->faker->randomElement(['1 month', '2 months', '3 months']),
            'instructor_id' => User::factory()->create(['role' => 'instructor'])->id,
        ];
    }
}
