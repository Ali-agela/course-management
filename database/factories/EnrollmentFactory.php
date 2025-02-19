<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory()->create()->id,
            'student_id' => User::factory()->create(['role' => 'student'])->id,
            'status' => $this->faker->randomElement(['pending', 'approved', 'completed']),
        ];
    }
}
