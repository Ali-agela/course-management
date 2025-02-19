<?php
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Pest\Laravel\assertDatabaseHas;
use Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;


uses(classAndTraits: RefreshDatabase::class);


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// test for students can enrrol in a course 
it('students only can enrrol in a course', function () {
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->create();

    $response = $this->actingAs($student)->postJson('/api/enrollment', ['course_id' => $course->id], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);

    $response->assertStatus(201);
    assertDatabaseHas('enrollments', [
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);
});
// test for non-students cannot enrrol in a course
it('non-students cannot enrrol in a course', function () {
    $teacher = User::factory()->create(['role' => 'instructor']);
    $course = Course::factory()->create();

    $response = $this->actingAs($teacher)->postJson('/api/enrollment', ['course_id' => $course->id], [
        'Authorization' => 'Bearer ' . $teacher->createToken('authToken')->plainTextToken,

    ]);

    $response->assertStatus(401);
    assertDatabaseMissing('enrollments', [
        'student_id' => $teacher->id,
        'course_id' => $course->id,
    ]);
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// test for guests cannot enrrol in a course
it('guests cannot enrrol in a course', function () {
    $course = Course::factory()->create();

    $response = $this->postJson('/api/enrollment', ['course_id' => $course->id]);

    $response->assertStatus(401);
    assertDatabaseMissing('enrollments', [
        'course_id' => $course->id,
    ]);
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// test for students can unenrrol from a course
it('students only can unenrrol from a course', function () {
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->create();
    $enrollment = Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'approved',
    ]);
    $response = $this->actingAs($student)->deleteJson('/api/enrollment/' . $enrollment->id, [], [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(200);
    assertDatabaseMissing('enrollments', [
        'id' => $enrollment->id,
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// test for a student to get all his courses
it('a student can get all his courses', function () {
    $student = User::factory()->create(['role' => 'student']);
    $courses = Course::factory()->count(3)->create();

    foreach ($courses as $course) {
        Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }


    $response = $this->actingAs($student)->getJson('/api/enrollment',  [
        'Authorization' => 'Bearer ' . $student->createToken('authToken')->plainTextToken,
    ]);

    // assert the response status
    $response->assertStatus(200);
    
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// test for instructors only to set the state of an enrollment 
it('instructors only can set the state of an enrollment', function () {

    // create an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);
    // create a student
    $student = User::factory()->create(['role' => 'student']);
    // create a course
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);
    // create an enrollment
    $enrollment = Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);


    $response = $this->actingAs($instructor)->putJson('/api/enrollment/' . $enrollment->id, ['status' => 'approved'],[
        'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,
    ]);

    // assert the response status
    $response->assertStatus(200);
    assertDatabaseHas('enrollments', [
        'id' => $enrollment->id,
        'status' => 'approved',
    ]);// assert the data is in the database
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


