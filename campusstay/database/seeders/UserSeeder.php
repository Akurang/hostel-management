<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::updateOrCreate(
            ['email' => 'admin@campusstay.com'],
            [
                'name' => 'Platform Admin',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
                'approved_at' => Carbon::now(),
            ],
        );

        $managers = [
            ['name' => 'Kwame Asante', 'email' => 'manager1@campusstay.com'],
            ['name' => 'Abena Mensah', 'email' => 'manager2@campusstay.com'],
            ['name' => 'Kofi Boateng', 'email' => 'manager3@campusstay.com'],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                [
                    'name' => $manager['name'],
                    'password' => $password,
                    'role' => 'manager',
                    'is_active' => true,
                    'approved_at' => Carbon::now(),
                ],
            );
        }

        $students = [
            [
                'name' => 'Akosua Darko',
                'email' => 'student1@campusstay.com',
                'student_id' => 'CS/2021/001',
                'university' => 'KNUST',
            ],
            [
                'name' => 'Yaw Frimpong',
                'email' => 'student2@campusstay.com',
                'student_id' => 'CS/2022/002',
                'university' => 'UG',
            ],
            [
                'name' => 'Ama Owusu',
                'email' => 'student3@campusstay.com',
                'student_id' => 'CS/2022/003',
                'university' => 'UCC',
            ],
            [
                'name' => 'Kwesi Acheampong',
                'email' => 'student4@campusstay.com',
                'student_id' => 'CS/2023/004',
                'university' => 'KNUST',
            ],
            [
                'name' => 'Efua Asiedu',
                'email' => 'student5@campusstay.com',
                'student_id' => 'CS/2023/005',
                'university' => 'UG',
            ],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => $password,
                    'role' => 'student',
                    'student_id' => $student['student_id'],
                    'phone' => '+233240000000',
                    'university' => $student['university'],
                    'academic_year' => '2024/2025',
                    'is_active' => true,
                    'approved_at' => Carbon::now(),
                ],
            );
        }
    }
}
