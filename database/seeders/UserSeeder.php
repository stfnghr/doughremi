<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user.admin@email.com'],
            [
                'name' => 'Admin01',
                'password' => 'admin123', // <-- PLAIN TEXT, model will hash
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
