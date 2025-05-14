<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bryan',
            'email' => 'admin@livelaughlanguage.com',
            'password' => Hash::make('bryan@12-33'),
            'role' => 'admin', // Assign the admin role
        ]);
    }
}
