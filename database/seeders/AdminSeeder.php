<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@wedding.com',
            'password' => Hash::make('hanni2000'),
            'is_admin' => true,
        ]);
    }
}
