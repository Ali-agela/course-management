<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
            'student_id' => 1,
            'course_id' => 1,
            'rating' => 5,
            'comment' => 'Great course, I learned a lot from it.',
            ],
            [
            'student_id' => 1,
            'course_id' => 2,
            'rating' => 4,
            'comment' => 'Good course, I learned a lot from it.',
            ],
            [
            'student_id' => 1,
            'course_id' => 3,
            'rating' => 3,
            'comment' => 'Nice course, I learned a lot from it.',
            ],
            [
            'student_id' => 1,
            'course_id' => 4,
            'rating' => 2,
            'comment' => 'Bad course, I learned nothing from it.',
            ],
            [
            'student_id' => 4,
            'course_id' => 1,
            'rating' => 5,
            'comment' => 'Great course, I learned a lot from it.',
            ],
            [
            'student_id' => 4,
            'course_id' => 2,
            'rating' => 4,
            'comment' => 'Good course, I learned a lot from it.',
            ],
            [
            'student_id' => 4,
            'course_id' => 3,
            'rating' => 3,
            'comment' => 'Nice course, I learned a lot from it.',
            ],
            [
            'student_id' => 4,
            'course_id' => 4,
            'rating' => 2,
            'comment' => 'Bad course, I learned nothing from it.',
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
