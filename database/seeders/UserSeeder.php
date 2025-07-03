<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create two specific users with known emails and passwords
        User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@3sc.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@3sc.com',
            'password' => Hash::make('123456'),
        ]);
    }
}    
