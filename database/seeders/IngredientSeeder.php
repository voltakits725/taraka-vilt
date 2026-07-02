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
            ['name' => 'Susu Sapi', 'is_allergen' => true, 'allergen_type' => 'Susu'],
            ['name' => 'Telur', 'is_allergen' => true, 'allergen_type' => 'Telur'],
            ['name' => 'Ikan', 'is_allergen' => true, 'allergen_type' => 'Ikan'],
            ['name' => 'Kerang/Seafood', 'is_allergen' => true, 'allergen_type' => 'Kerang'],
            ['name' => 'Kacang Pohon', 'is_allergen' => true, 'allergen_type' => 'Kacang-kacangan'],
            ['name' => 'Kacang Tanah', 'is_allergen' => true, 'allergen_type' => 'Kacang-kacangan'],
            ['name' => 'Gandum', 'is_allergen' => true, 'allergen_type' => 'Gluten'],
            ['name' => 'Kedelai', 'is_allergen' => true, 'allergen_type' => 'Kedelai'],
            ['name' => 'Wijen', 'is_allergen' => true, 'allergen_type' => 'Wijen'],

            // Non-allergens
            ['name' => 'Biji Kopi Arabica', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Gula Aren', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Dada Ayam Fillet', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Coklat Bubuk', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Sirup Karamel', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Sirup Leci', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Keju Mozarella', 'is_allergen' => true, 'allergen_type' => 'Susu'],
            ['name' => 'Smoked Beef', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Stroberi Segar', 'is_allergen' => false, 'allergen_type' => null],
            ['name' => 'Daun Mint', 'is_allergen' => false, 'allergen_type' => null],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::firstOrCreate(['name' => $ing['name']], $ing);
        }
    }
}
