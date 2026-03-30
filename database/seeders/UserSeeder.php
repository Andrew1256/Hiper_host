<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@blog.com',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_ADMIN,
        ]);

        User::create([
            'name'     => 'Editor User',
            'email'    => 'editor@blog.com',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_EDITOR,
        ]);
    }
}
