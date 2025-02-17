<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
            'title' => 'Laravel 8',
            'description' => 'Learn Laravel 8 from scratch',
            'instructor_id' => 2,
            'category' => 'backend',
            'duration' => '10 hours',
            ],
            [
            'title' => 'React JS',
            'description' => 'Learn React JS from scratch',
            'instructor_id' => 5,
            'category' => 'frontend',
            'duration' => '12 hours',
            ],
            [
            'title' => 'Vue JS',
            'description' => 'Learn Vue JS from scratch',
            'instructor_id' => 2,
            'category' => 'frontend',
            'duration' => '8 hours',
            ],
            [
            'title' => 'Node JS',
            'description' => 'Learn Node JS from scratch',
            'instructor_id' => 5,
            'category' => 'web development',
            'duration' => '15 hours',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
