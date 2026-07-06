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
        for ($i = 1; $i <= 15; $i++) {
            \App\Models\Table::create([
                'number' => $i,
                'capacity' => 4,
                'status' => 'active'
            ]);
        }

       User::create([
            'name' => 'Admin Taraka',
            'email' => 'admin@taraka.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // Role khusus admin
        ]);
        
        // Lo bisa tambahin seeder dummy customer di sini kalau perlu
        User::create([
            'name' => 'Rafly Kurniawan',
            'email' => 'rafly@mail.com',
            'password' => Hash::make('rafly123'),
            'role' => 'customer',
        ]);

        $this->call([
            IngredientSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
