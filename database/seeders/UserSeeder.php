<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'ali agela',
                'email' => 'ali@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'student',
            ],
            [
                'name' => 'monther ibrahem',
                'email' => 'monther@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'instructor',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ],
            [
                'name' => 'ahmed mohamed',
                'email' => 'ahmed@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'student',
            ],
            [
                'name' => 'mohamed ali',
                'email' => 'mohamed@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'instructor',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
