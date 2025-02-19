<?php


use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(classAndTraits: RefreshDatabase::class);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// instructor can add a Lesson to his course and other users classes can not 
it('instructor can add a Lesson to his course and other users classes can not', function () {

    //checking an un authenticated user
    $response = $this->postJson('/api/course/lesson', [
        'title' => 'Laravel',
        'content' => 'Laravel course',
        'course_id' => 1,
    ]);
    $response->assertStatus(401);//asserting the status code

    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //creating a lesson for the course
    $response = $this->actingAs($instructor)->postJson(
        '/api/course/lesson',
        [
            'title' => 'Laravel',
            'content' => 'Laravel course',
            'course_id' => $course->id,
        ],
        [
            'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,
        ]
    );

    $response->assertStatus(200);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('lessons', [
        'title' => 'Laravel',
        'content' => 'Laravel course',
        'course_id' => $course->id,
    ]);

    //creating a student
    $student = User::factory()->create(['role' => 'student']);

    //creating a course for the student
    $course = Course::factory()->create(['instructor_id' => $student->id]);

    //creating a lesson for the course
    $response = $this->actingAs($student)->postJson('/api/course/lesson', [
        'title' => 'Vue.js',
        'content' => 'Vue.js course',
        'course_id' => $course->id,
    ], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(401);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('lessons', [
        'title' => 'Vue.js',
        'content' => 'Vue.js course',
        'course_id' => $course->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// instructor can update his own Lesson and other users classes can not
it('instructor can update his own Lesson and other users classes can not', function () {

    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //creating a lesson for the course
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    //updating the lesson
    $response = $this->actingAs($instructor)->putJson(
        '/api/course/lesson/' . $lesson->id,
        [
            'title' => 'Vue1.js',
            'content' => 'Vue1.js course',
        ],
        [
            'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,
        ]
    );
    $response->assertStatus(200);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('lessons', [
        'title' => 'Vue1.js',
        'content' => 'Vue1.js course',
        'course_id' => $course->id,
    ]);

    //creating a student
    $student = User::factory()->create(['role' => 'student']);


    //updating the lesson
    $response = $this->actingAs($student)->putJson('/api/course/lesson/' . $lesson->id, [
        'title' => 'Vue2.js',
        'content' => 'Vue2.js course',
        'course_id' => $course->id,
    ], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(401);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('lessons', [
        'title' => 'Vue2.js',
        'content' => 'Vue2.js course',
        'course_id' => $course->id,
    ]);
});



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// instructor can delete his own Lesson and other users classes can not
it('instructor can delete his own Lesson and other users classes can not', function () {

    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //creating a lesson for the course
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    //deleting the lesson
    $response = $this->actingAs($instructor)->deleteJson('/api/course/lesson/' . $lesson->id, [], [
        'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(200);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('lessons', [
        'id' => $lesson->id,
    ]);

    //creating a student
    $student = User::factory()->create(['role' => 'student']);


    //creating a lesson for the course
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    //deleting the lesson
    $response = $this->actingAs($student)->deleteJson('/api/course/lesson/' . $lesson->id, [], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(401);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('lessons', [
        'id' => $lesson->id,
        'course_id' => $course->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------