<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin P3M',
            'email' => 'admin@p3m.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Reviewer
        User::create([
            'name' => 'Reviewer Sinta',
            'email' => 'reviewer@p3m.com',
            'password' => Hash::make('reviewer123'),
            'role' => 'reviewer',
        ]);

        // Dosen (default)
        User::create([
            'name' => 'Dosen Wibi',
            'email' => 'wibi@p3m.com',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
        ]);
    }
}
