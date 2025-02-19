<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Pest\Laravel\assertDatabaseHas;
use Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
uses(classAndTraits: RefreshDatabase::class);


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


it('can register a new user  as student or instructor but not admin', function () {

    // testing the registration of a student
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'John@gmail.com',
        'password' => bcrypt('123456'),
        'role' => 'student',
    ]);
    $response->assertStatus(201);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'John@gmail.com',
        'role' => 'student',
    ]);

    // testing the registration of an instructor
    $response = $this->postJson('/api/register', [
        'name' => 'Jane Doe',
        'email' => 'Jane@gmail.com',
        'password' => bcrypt('123456'),
        'role' => 'instructor',
    ]);
    $response->assertStatus(201);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'Jane@gmail.com',
        'role' => 'instructor',
    ]);

    // testing the registration of an admin
    $response = $this->postJson('/api/register', [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('123456'),
        'role' => 'admin',
    ]);
    $response->assertStatus(401);//asserting the status code
    //asserting the data is not in the database
    assertDatabaseMissing('users', [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'role' => 'admin',
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



it('admin can regester ana admin', function () {
    //creating an admin
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);  //acting as the admin
    // testing the registration of an admin
    $response = $this->postJson('/api/register', [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('123456'),
        'role' => 'admin',
    ]);
    $response->assertStatus(201);//asserting the status code
    //asserting the data is in the database
    assertDatabaseHas('users', [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'role' => 'admin',
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


it('can login a user', function () {
    //creating a user
    $user = User::factory()->create(['password' => bcrypt('password'), 'role' => 'student']);
    //testing the  login  with the right criadentials
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $response->assertStatus(200);//asserting the status code

    //testing the  login  with the wrong criadentials
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);
    $response->assertStatus(401);//asserting the status code
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



it('can get the user profile', function () {
    //testing the user profile without authentication
    $response = $this->getJson('/api/user');
    $response->assertStatus(401);//asserting the status code

    //creating a user  and authenticating the user
    $user = User::factory()->create(['role' => 'student']);
    $this->actingAs($user);  //acting as the user
    //testing the user profile
    $response = $this->getJson('/api/user', [
        'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(200);//asserting the status code
    $response->assertJson($user->toArray());//asserting the data
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


it('can update the user profile', function () {

    //testing the user profile without authentication
    $response = $this->putJson('/api/user', [
        'name' => 'John Doe',
        'email' => 'new_email@gmail.com',
    ]);
    $response->assertStatus(401);//asserting the status code  

    //creating two users 
    $user = User::factory()->create(['role' => 'student']);
    $user2 = User::factory()->create(['role' => 'student']);

    // making sure user 1 can update his profile but cant use  user 2 email
    $this->actingAs($user);  //acting as the user
    $response = $this->putJson('/api/user', [
        'name' => 'John Doe',
        'email' => $user2->email,
    ], [
        'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken,
    ]);
    $response->assertStatus(422);//asserting the status code

    // making sure user 1 can update his profile
    $response = $this->putJson(
        '/api/user',
        [
            'name' => 'John Doe',
            'email' => 'new_email@gmail.com',
        ],
        [
            'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken,
        ]
    );
    $response->assertStatus(200);//asserting the status code

    //asserting the data is in the database
    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'new_email@gmail.com',
    ]);
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


it('can delete the user profile', function () {

    //testing the user deleting  without authentication
    $response = $this->deleteJson('/api/user');
    $response->assertStatus(401);//asserting the status code  

    //creating a user
    $user = User::factory()->create(['role' => 'student']);

    // authenticating the user
    $this->actingAs($user);  //acting as the user

    //testing the user profile
    $response = $this->deleteJson(
        '/api/user',
        [
        ],
        [
            'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken,
        ]
    );

    $response->assertStatus(200);//asserting the status code

    //asserting the data is not in the database
    assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------



it('can get all users for admins only', function () {

    //testing the user deleting  without authentication
    $response = $this->getJson('/api/users');
    $response->assertStatus(401);//asserting the status code  

    //creating a user
    $user = User::factory()->create(['role' => 'student']);

    // authenticating the user
    $this->actingAs($user);  //acting as the user

    //testing the user profile
    $response = $this->getJson('/api/users');

    $response->assertStatus(401);//asserting the status code

    //creating an admin
    $admin = User::factory()->create(['role' => 'admin']);

    // authenticating the admin
    $this->actingAs($admin);  //acting as the admin

    //testing the user profile
    $response = $this->getJson('/api/users', [
        'Authorization' => 'Bearer ' . $admin->createToken('authToken')->plainTextToken,
    ]);

    $response->assertStatus(200);//asserting the status code
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------