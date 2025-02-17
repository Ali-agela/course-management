<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            [
            'title' => 'Laravel 8 - Lesson 1',
            'content' => 'Learn Laravel 8 from scratch - Lesson 1',
            'course_id' => 1,
            ],
            [
            'title' => 'Laravel 8 - Lesson 2',
            'content' => 'Learn Laravel 8 from scratch - Lesson 2',
            'course_id' => 1,
            ],
            [
            'title' => 'React JS - Lesson 1',
            'content' => 'Learn React JS from scratch - Lesson 1',
            'course_id' => 2,
            ],
            [
            'title' => 'React JS - Lesson 2',
            'content' => 'Learn React JS from scratch - Lesson 2',
            'course_id' => 2,
            ],
            [
            'title' => 'Vue JS - Lesson 1',
            'content' => 'Learn Vue JS from scratch - Lesson 1',
            'course_id' => 3,
            ],
            [
            'title' => 'Vue JS - Lesson 2',
            'content' => 'Learn Vue JS from scratch - Lesson 2',
            'course_id' => 3,
            ],
            [
            'title' => 'Node JS - Lesson 1',
            'content' => 'Learn Node JS from scratch - Lesson 1',
            'course_id' => 4,
            ],
            [
            'title' => 'Node JS - Lesson 2',
            'content' => 'Learn Node JS from scratch - Lesson 2',
            'course_id' => 4,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}
