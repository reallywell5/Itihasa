<?php

namespace Database\Seeders;

use App\Models\Museum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $firstMuseum = Museum::first();
        $museumId = $firstMuseum?->id;

        User::updateOrCreate(
            ['email' => 'superadmin@itihasa.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'museum_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@itihasa.com'],
            [
                'name' => 'Admin Museum',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'museum_id' => $museumId,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@itihasa.com'],
            [
                'name' => 'Petugas Museum',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'museum_id' => $museumId,
            ]
        );

        User::updateOrCreate(
            ['email' => 'visitor@itihasa.com'],
            [
                'name' => 'Pengunjung',
                'password' => Hash::make('visitor123'),
                'role' => 'visitor',
                'museum_id' => null,
            ]
        );
    }
}
