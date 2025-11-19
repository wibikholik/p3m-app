<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role
        $adminRole = Role::where('name', 'admin')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $reviewerRole = Role::where('name', 'reviewer')->first();

        // ===== Admin =====
        $admin = User::updateOrCreate(
            ['email' => 'admin@p3m.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // ===== Dosen =====
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@p3m.com'],
            [
                'name' => 'Dosen User',
                'nidn' => '1234567890',
                'jabatan_akademik' => 'Lektor',
                'password' => Hash::make('dosen123'),
            ]
        );
        $dosen->roles()->syncWithoutDetaching([$dosenRole->id]);

        // ===== Reviewer =====
        $reviewer = User::updateOrCreate(
            ['email' => 'reviewer@p3m.com'],
            [
                'name' => 'Reviewer User',
                'password' => Hash::make('reviewer123'),
            ]
        );
        $reviewer->roles()->syncWithoutDetaching([$reviewerRole->id]);

        // ===== Dosen & Reviewer sekaligus =====
        $multi = User::updateOrCreate(
            ['email' => 'dosenreviewer@p3m.com'],
            [
                'name' => 'Dosen & Reviewer',
                'nidn' => '0987654321',
                'jabatan_akademik' => 'Lektor Kepala',
                'password' => Hash::make('dosre123'),
            ]
        );
        $multi->roles()->syncWithoutDetaching([$dosenRole->id, $reviewerRole->id]);
    }
}
