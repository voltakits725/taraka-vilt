<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       User::create([
            'name' => 'Super Admin',
            'email' => 'admin@cafe.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // Role khusus admin
        ]);
        
        // Lo bisa tambahin seeder dummy customer di sini kalau perlu
        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'customer@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}
