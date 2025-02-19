<?php
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// students can add review to any course 
it('students can add review to any course', function () {

    //creating a student
    $student = User::factory()->create(['role' => 'student']);
    //creating a course
    $course = Course::factory()->create();
    //creating a review for the course
    $response = $this->actingAs($student)->postJson('/api/course/' . $course->id . '/reviews', [
        'rating' => 5,
        'comment' => 'Great course',
    ],[
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);
    //asserting the status code
    $response->assertStatus(201);
    assertDatabaseHas('reviews', [
        'student_id' => $student->id,
        'course_id' => $course->id,
        'rating' => 5,
        'comment' => 'Great course',
    ]);//asserting the data is in the database
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



//a student can update only his reviews 
it('a student can update only his reviews', function () {
    //creating a student
    $student = User::factory()->create(['role' => 'student']);
    //creating a course
    $course = Course::factory()->create();
    //creating a review for the course
    $review = Review::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);
    //creating a student
    $student2 = User::factory()->create(['role' => 'student']);
    //creating a review for the course
    $review2 = Review::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);

    //updating the review
    $response = $this->actingAs($student)->putJson(
        '/api/reviews/' . $review->id,
        [
            'rating' => 4,
            'comment' => 'Good course',
        ],
        [
            'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
        ]
    );

    //asserting the status code
    $response->assertStatus(200);
    assertDatabaseHas('reviews', [
        'id' => $review->id,
        'student_id' => $student->id,
        'course_id' => $course->id,
        'rating' => 4,
        'comment' => 'Good course',
    ]);//asserting the data is in the database


    //updating the review
    $response = $this->actingAs($student2)->putJson(
        '/api/reviews/' . $review->id,
        [
            'rating' => 4,
            'comment' => 'Good course',
        ],
        [
            'Authorization' => 'Bearer ' . $student2->createToken('authToken')->plainTextToken,
        ]
    );
    //asserting the status code
    $response->assertStatus(404);
    assertDatabaseMissing('reviews', [
        'id' => $review->id,
        'student_id' => $student2->id,
        'course_id' => $course->id,
        'rating' => 4,
        'comment' => 'Good course',
    ]);//asserting the data is not in the database
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


//a student can delete only his reviews
it('a student can delete only his reviews', function () {
    //creating a student
    $student = User::factory()->create(['role' => 'student']);
    //creating a course
    $course = Course::factory()->create();
    //creating a review for the course
    $review = Review::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);
    //creating a student
    $student2 = User::factory()->create(['role' => 'student']);
    //creating a review for the course
    $review2 = Review::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);

    //deleting the review
    $response = $this->actingAs($student2)->deleteJson('/api/reviews/' . $review->id, [], [
        'Authorization' => 'Bearer ' . $student2->createToken('authToken')->plainTextToken,
    ]);
    //asserting the status code
    $response->assertStatus(404);
    assertDatabaseHas('reviews', [
        'id' => $review2->id,
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);//asserting the data is in the database


    //deleting the review
    $response = $this->actingAs($student)->deleteJson('/api/reviews/' . $review->id, [], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);


    //asserting the status code
    $response->assertStatus(200);
    assertDatabaseMissing('reviews', [
        'id' => $review->id,
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);//asserting the data is not in the database
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// any one can get all the reviews of any course
it('any one can get all the reviews of any course', function () {
    //creating a course
    $course = Course::factory()->create();
    //creating a review for the course
    $review = Review::factory()->create([
        'course_id' => $course->id,
    ]);
    //getting the reviews
    $response = $this->getJson('api/course/' . $course->id . '/reviews');
    //asserting the status code
    $response->assertStatus(200);
});



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



//an admin can delete any review
it('an admin can delete any review', function () {
    //creating an admin
    $admin = User::factory()->create(['role' => 'admin']);
    //creating a course
    $course = Course::factory()->create();
    //creating a review for the course
    $review = Review::factory()->create([
        'course_id' => $course->id,
    ]);
    //deleting the review
    $response = $this->actingAs($admin)->deleteJson('/api/reviews/' . $review->id, [], [
        'Authorization' => 'Bearer ' . $admin->createToken('authToken')->plainTextToken,
    ]);
    //asserting the status code
    $response->assertStatus(200);
    assertDatabaseMissing('reviews', [
        'id' => $review->id,
        'course_id' => $course->id,
    ]);//asserting the data is not in the database
});