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
            \App\Models\Table::firstOrCreate(
                ['number' => $i],
                [
                    'capacity' => 4,
                    'status' => 'active'
                ]
            );
        }

       User::firstOrCreate(
            ['email' => 'owner@taraka.com'],
            [
                'name' => 'Owner Taraka',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
            ]
        );

       User::firstOrCreate(
            ['email' => 'admin@taraka.com'],
            [
                'name' => 'Admin Taraka',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        
       User::firstOrCreate(
            ['email' => 'barista@taraka.com'],
            [
                'name' => 'Barista Taraka',
                'password' => Hash::make('barista123'),
                'role' => 'barista',
            ]
        );

        // Lo bisa tambahin seeder dummy customer di sini kalau perlu
        User::firstOrCreate(
            ['email' => 'rafly@mail.com'],
            [
                'name' => 'Rafly Kurniawan',
                'password' => Hash::make('rafly123'),
                'role' => 'customer',
            ]
        );

        $this->call([
            IngredientSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
