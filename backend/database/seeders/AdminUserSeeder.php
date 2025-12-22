<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@tandateam.kz'],
            [
                'name' => 'Admin',
                'password' => Hash::make('qwerty123456'),
            ]
        );
    }
}
