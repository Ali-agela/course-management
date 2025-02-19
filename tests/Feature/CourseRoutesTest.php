<?php

use App\Models\User;
use App\Models\Course;
use Pest\Laravel\assertDatabaseHas;
use Pest\Laravel\assertDatabaseMissing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(classAndTraits: RefreshDatabase::class);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



it('instructor can create his own course  and an admin can create any course and assign it to an instructor', function () {

    //checking an un authenticated user
    $response = $this->postJson('/api/courses', [
        'title' => 'Laravel',
        'description' => 'Laravel course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
    ]);
    $response->assertStatus(401);//asserting the status code

    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $response = $this->actingAs($instructor)->postJson(
        '/api/courses',
        [
            'title' => 'Laravel',
            'description' => 'Laravel course',
            'category' => 'web development',
            'price' => 100,
            'duration' => '2 months',
        ],
        [
            'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,

        ]
    );
    $response->assertStatus(201);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('courses', [
        'title' => 'Laravel',
        'description' => 'Laravel course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
        'instructor_id' => $instructor->id,
    ]);

    //creating an admin
    $admin = User::factory()->create(['role' => 'admin']);

    //creating a course for the instructor
    $response = $this->actingAs($admin)->postJson(
        '/api/courses',
        [
            'title' => 'Vue.js',
            'description' => 'Vue.js course',
            'category' => 'web development',
            'price' => 100,
            'duration' => '2 months',
            'instructor_id' => $instructor->id,
        ],
        [
            'Authorization' => 'Bearer ' . $admin->createToken('authToken')->plainTextToken,
        ]
    );
    $response->assertStatus(201);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('courses', [
        'title' => 'Vue.js',
        'description' => 'Vue.js course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
        'instructor_id' => $instructor->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------




it('instructor and admin can update a course', function () {

    //checking an un authenticated user
    $response = $this->putJson('/api/courses/1', [
        'title' => 'Laravel',
        'description' => 'Laravel course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
    ]);
    $response->assertStatus(401);//asserting the status code


    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //updating the course
    $response = $this->actingAs($instructor)->putJson(
        '/api/courses/' . $course->id,
        [
            'title' => 'Vue.js',
            'description' => 'Vue.js course',
            'category' => 'web development',
            'price' => 100,
            'duration' => '2 months',
        ],
        [
            'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,

        ]
    );
    $response->assertStatus(200);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('courses', [
        'title' => 'Vue.js',
        'description' => 'Vue.js course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
        'instructor_id' => $instructor->id,
    ]);

    //creating an admin
    $admin = User::factory()->create(['role' => 'admin']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //updating the course
    $response = $this->actingAs($admin)->putJson(
        '/api/courses/' . $course->id,
        [
            'title' => 'Vue.js',
            'description' => 'Vue.js course',
            'category' => 'web development',
            'price' => 100,
            'duration' => '2 months',
        ],
        [
            'Authorization' => 'Bearer ' . $admin->createToken('authToken')->plainTextToken,
        ]
    );
    $response->assertStatus(200);//asserting the status code

    //asserting the data is in the database
    assertDatabaseHas('courses', [
        'title' => 'Vue.js',
        'description' => 'Vue.js course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
        'instructor_id' => $instructor->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


it('instructor and admin can delete a course', function () {

    //checking an un authenticated user
    $response = $this->deleteJson('/api/courses/1');
    $response->assertStatus(401);//asserting the status code

    //creating an instructor
    $instructor = User::factory()->create(['role' => 'instructor']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //deleting the course
    $response = $this->actingAs($instructor)->deleteJson('/api/courses/' . $course->id, [], [
        'Authorization' => 'Bearer ' . $instructor->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(200);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);

    //creating an admin
    $admin = User::factory()->create(['role' => 'admin']);

    //creating a course for the instructor
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);

    //deleting the course
    $response = $this->actingAs($admin)->deleteJson(
        '/api/courses/' . $course->id,
        [
            'Authorization' => 'Bearer ' . $admin->createToken('authToken')->plainTextToken,
        ]
    );
    $response->assertStatus(200);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



it('can get courses with filters applied', function () {
    // creating users
    $instructor = User::factory()->create(['role' => 'instructor']);
    $admin = User::factory()->create(['role' => 'admin']);

    // creating courses
    Course::factory()->create([
        'title' => 'Laravel',
        'description' => 'Laravel course',
        'category' => 'web development',
        'price' => 100,
        'duration' => '2 months',
        'instructor_id' => $instructor->id,
    ]);

    Course::factory()->create([
        'title' => 'Vue.js',
        'description' => 'Vue.js course',
        'category' => 'web development',
        'price' => 150,
        'duration' => '3 months',
        'instructor_id' => $instructor->id,
    ]);

    // applying filters
    $response = $this->actingAs($admin)->getJson('/api/courses?category=web development&price=100');
    $response->assertStatus(200);
    $response->assertJsonCount(1);


    $response = $this->actingAs($admin)->getJson('/api/courses?category=web development&price=200');
    $response->assertStatus(200);
    $response->assertJsonCount(2);

    $response = $this->actingAs($admin)->getJson('/api/courses?category=web development&price=150');
    $response->assertStatus(200);
    $response->assertJsonCount(2);
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

