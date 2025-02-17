<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;

class EnrrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = [
            [
                'student_id' => 1,
                'course_id' => 1,
                'status' => 'approved',
            ],
            [
                'student_id' => 1,
                'course_id' => 2,
                'status' => 'approved',
            ],
            [
                'student_id' => 1,
                'course_id' => 3,
                'status' => 'approved',
            ],
            [
                'student_id' => 1,
                'course_id' => 4,
                'status' => 'approved',
            ],
            [
                'student_id' => 4,
                'course_id' => 1,
                'status' => 'approved',
            ],
            [
                'student_id' => 4,
                'course_id' => 2,
                'status' => 'approved',
            ],
            [
                'student_id' => 4,
                'course_id' => 3,
                'status' => 'approved',
            ],
            [
                'student_id' => 4,
                'course_id' => 4,
                'status' => 'approved',
            ],
        ];

        foreach ($enrollments as $enrollment) {
            Enrollment::create($enrollment);
        }
    }
}
