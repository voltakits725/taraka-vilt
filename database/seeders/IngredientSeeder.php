<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // The Big 9 Allergens
            ['name' => 'Susu Sapi', 'is_allergen' => true, 'allergen_type' => 'Susu', 'stock' => 5000, 'unit' => 'ml'],
            ['name' => 'Telur', 'is_allergen' => true, 'allergen_type' => 'Telur', 'stock' => 100, 'unit' => 'pcs'],
            ['name' => 'Ikan', 'is_allergen' => true, 'allergen_type' => 'Ikan', 'stock' => 2000, 'unit' => 'gram'],
            ['name' => 'Kerang/Seafood', 'is_allergen' => true, 'allergen_type' => 'Kerang', 'stock' => 2000, 'unit' => 'gram'],
            ['name' => 'Kacang Pohon', 'is_allergen' => true, 'allergen_type' => 'Kacang-kacangan', 'stock' => 1000, 'unit' => 'gram'],
            ['name' => 'Kacang Tanah', 'is_allergen' => true, 'allergen_type' => 'Kacang-kacangan', 'stock' => 1000, 'unit' => 'gram'],
            ['name' => 'Gandum', 'is_allergen' => true, 'allergen_type' => 'Gluten', 'stock' => 5000, 'unit' => 'gram'],
            ['name' => 'Kedelai', 'is_allergen' => true, 'allergen_type' => 'Kedelai', 'stock' => 2000, 'unit' => 'gram'],
            ['name' => 'Wijen', 'is_allergen' => true, 'allergen_type' => 'Wijen', 'stock' => 500, 'unit' => 'gram'],

            // Non-allergens
            ['name' => 'Biji Kopi Arabica', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 5000, 'unit' => 'gram'],
            ['name' => 'Gula Aren', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 2000, 'unit' => 'gram'],
            ['name' => 'Dada Ayam Fillet', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 5000, 'unit' => 'gram'],
            ['name' => 'Coklat Bubuk', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 1000, 'unit' => 'gram'],
            ['name' => 'Sirup Karamel', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 2000, 'unit' => 'ml'],
            ['name' => 'Sirup Leci', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 2000, 'unit' => 'ml'],
            ['name' => 'Keju Mozarella', 'is_allergen' => true, 'allergen_type' => 'Susu', 'stock' => 1500, 'unit' => 'gram'],
            ['name' => 'Smoked Beef', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 1000, 'unit' => 'gram'],
            ['name' => 'Stroberi Segar', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 500, 'unit' => 'gram'],
            ['name' => 'Daun Mint', 'is_allergen' => false, 'allergen_type' => null, 'stock' => 200, 'unit' => 'gram'],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::updateOrCreate(['name' => $ing['name']], $ing);
        }
    }
}
