<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Visitor',
            'email' => 'visitor@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'visitor',
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'staff',
        ]);
    }
}
