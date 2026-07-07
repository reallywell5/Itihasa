<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@itihasa.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Petugas Museum',
            'email' => 'staff@itihasa.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff'
        ]);

        User::create([
            'name' => 'Visitor',
            'email' => 'visitor@itihasa.com',
            'password' => Hash::make('visitor123'),
            'role' => 'visitor'
        ]);
    }
}
