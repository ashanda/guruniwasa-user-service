<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students')->insert([
            'username' => mt_rand(1000000000, 9999999999),
            'password' => Hash::make('password'),
            'student_code' => Str::random(10),
            'full_name' => 'John Doe',
            'grade' => '10',
            'birthday' => '2004-01-01',
            'gender' => 'Male',
            'address' => '123 Main St',
            'school' => 'High School',
            'district' => 'District 1',
            'city' => 'City Name',
            'parent_phone' => '1234567890',
            'avatar' => null,
            'status' => 1,
        ]);
    }
}
