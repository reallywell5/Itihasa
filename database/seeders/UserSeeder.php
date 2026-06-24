<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@itihasa.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staff',
                'email' => 'staff@itihasa.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ],
            [
                'name' => 'Visitor',
                'email' => 'visitor@itihasa.com',
                'password' => Hash::make('password'),
                'role' => 'visitor',
            ],
        ];

        foreach ($users as $user) {
            if (!User::where('email', $user['email'])->exists()) {
                User::create($user);
                $this->command->info("✅ Created user: {$user['email']}");
            } else {
                $this->command->info("⏭️ User already exists: {$user['email']}");
            }
        }

        $this->command->newLine();
        $this->command->info('===========================================');
        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('===========================================');
        $this->command->info('📧 Admin   : admin@itihasa.com | password');
        $this->command->info('📧 Staff   : staff@itihasa.com | password');
        $this->command->info('📧 Visitor : visitor@itihasa.com | password');
        $this->command->info('===========================================');
    }
}