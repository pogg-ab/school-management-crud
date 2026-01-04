<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles first to be safe
        $adminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // 1. Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // 2. Create Teacher
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'Mr. Teacher',
                'password' => Hash::make('password'),
            ]
        );
        $teacher->assignRole($teacherRole);

        // 3. Create Student
        $student = User::updateOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => 'Student Alice',
                'password' => Hash::make('password'),
            ]
        );
        $student->assignRole($studentRole);
    }
}