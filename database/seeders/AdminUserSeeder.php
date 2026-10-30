<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@clinic.local'],
            [
                'name' => 'admin',
                'password' => 'admin',
            ]
        );
    }
}