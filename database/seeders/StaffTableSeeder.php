<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('staff')->insert([
            [
                'photo' => 'path_to_photo_1.jpg',
                'name' => 'John Doe',
                'gender' => 'Male',
                'contact_no' => '1234567890',
                'second_contact_no' => '0987654321',
                'birthday' => '1990-01-01',
                'nic_no' => 'NIC123456789',
                'address' => '123 Main Street, City, Country',
                'basic' => 50000,
                'fixed_allowance' => 10000,
                'working_days_and_hours' => 'Monday to Friday, 9 AM - 5 PM',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password'),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'photo' => 'path_to_photo_2.jpg',
                'name' => 'Jane Doe',
                'gender' => 'Female',
                'contact_no' => '0987654321',
                'second_contact_no' => '1234567890',
                'birthday' => '1992-02-02',
                'nic_no' => 'NIC987654321',
                'address' => '456 Secondary Street, City, Country',
                'basic' => 60000,
                'fixed_allowance' => 12000,
                'working_days_and_hours' => 'Monday to Friday, 9 AM - 6 PM',
                'email' => 'jane.doe@example.com',
                'password' => bcrypt('password'),
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ]);
    }
}
