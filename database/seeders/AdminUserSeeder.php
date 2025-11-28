<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::create([
            'username' => 'farr',
            'email' => 'farr@gmail.com',
            'password' => Hash::make('123'), // password admin
            'role' => 1, // 1 = admin
            'photo' => null,
        ]);
    }
}
