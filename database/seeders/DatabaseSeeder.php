<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 1. Create Admin
    $admin = \App\Models\User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@school.com',
    ]);
    $admin->assignRole('super_admin');

    // 2. Create Teacher
    $teacher = \App\Models\User::factory()->create([
        'name' => 'Mr. Teacher',
        'email' => 'teacher@school.com',
    ]);
    $teacher->assignRole('teacher');

    // 3. Create Student
    $student = \App\Models\User::factory()->create([
        'name' => 'Student Alice',
        'email' => 'student@school.com',
    ]);
    $student->assignRole('student');
}
}
