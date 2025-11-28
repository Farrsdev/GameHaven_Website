<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@gamehaven.com',
                'password' => Hash::make('password'),
                'role' => 1, // Admin
                'photo' => 'https://ui-avatars.com/api/?name=Admin&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'john_doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'photo' => 'https://ui-avatars.com/api/?name=John+Doe&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'photo' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'gamer_pro',
                'email' => 'gamer.pro@example.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'photo' => 'https://ui-avatars.com/api/?name=Gamer+Pro&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'alice_wonder',
                'email' => 'alice.wonder@example.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'photo' => 'https://ui-avatars.com/api/?name=Alice+Wonder&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'bob_marley',
                'email' => 'bob.marley@example.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'photo' => 'https://ui-avatars.com/api/?name=Bob+Marley&background=6a9eff&color=fff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('Users seeded successfully!');
    }
}