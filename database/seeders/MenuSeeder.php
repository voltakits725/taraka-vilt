<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori ada
        $catDessert = Category::firstOrCreate(['slug' => 'dessert'], ['name' => 'Dessert']);
        $catCoffee = Category::firstOrCreate(['slug' => 'coffee'], ['name' => 'Coffee']);
        $catDrink = Category::firstOrCreate(['slug' => 'drink'], ['name' => 'Drink']);
        $catFood = Category::firstOrCreate(['slug' => 'food'], ['name' => 'Food']);

        $menus = [
            // Dessert (3)
            [
                'name' => 'Tiramisu Cake',
                'description' => 'Kue khas Italia dengan aroma kopi dan mascarpone cheese yang lembut.',
                'price' => 35000,
                'category_id' => $catDessert->id,
                'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Matcha Mille Crepe',
                'description' => 'Berlapis-lapis crepe lembut dengan krim matcha premium dari Jepang.',
                'price' => 42000,
                'category_id' => $catDessert->id,
                'image' => 'https://images.unsplash.com/photo-1621236378699-8597fa6b7189?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Choco Lava Cake',
                'description' => 'Kue coklat hangat dengan lumeran coklat pekat di dalamnya, disajikan dengan es krim vanilla.',
                'price' => 38000,
                'category_id' => $catDessert->id,
                'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?q=80&w=600&auto=format&fit=crop',
            ],

            // Coffee (3)
            [
                'name' => 'Caramel Macchiato',
                'description' => 'Kopi espresso dengan campuran susu segar dan sirup karamel spesial.',
                'price' => 28000,
                'category_id' => $catCoffee->id,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'V60 Pour Over',
                'description' => 'Kopi hitam manual brew dengan biji kopi pilihan (Arabica Gayo/Toraja).',
                'price' => 25000,
                'category_id' => $catCoffee->id,
                'image' => 'https://images.unsplash.com/photo-1544425447-0638167f9d51?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Hazelnut Latte',
                'description' => 'Kopi latte lembut dengan tambahan sirup hazelnut yang harum.',
                'price' => 26000,
                'category_id' => $catCoffee->id,
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?q=80&w=600&auto=format&fit=crop',
            ],

            // Drink (2)
            [
                'name' => 'Lychee Mojito',
                'description' => 'Minuman segar dari sirup leci, daun mint, perasan jeruk nipis, dan soda.',
                'price' => 22000,
                'category_id' => $catDrink->id,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Strawberry Smoothies',
                'description' => 'Blended stroberi segar dengan susu dan yogurt yang sehat dan mengenyangkan.',
                'price' => 24000,
                'category_id' => $catDrink->id,
                'image' => 'https://images.unsplash.com/photo-1638176066666-ffb2f013c71c?q=80&w=600&auto=format&fit=crop',
            ],

            // Food (2)
            [
                'name' => 'Spaghetti Carbonara',
                'description' => 'Pasta al dente dengan saus keju creamy, smoked beef, dan taburan parsley.',
                'price' => 45000,
                'category_id' => $catFood->id,
                'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Chicken Cordon Bleu',
                'description' => 'Dada ayam fillet krispi yang diisi dengan keju mozarella lumer dan smoked beef.',
                'price' => 48000,
                'category_id' => $catFood->id,
                'image' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8d4?q=80&w=600&auto=format&fit=crop',
            ],
        ];

        foreach ($menus as $menu) {
            $menu['slug'] = Str::slug($menu['name']);
            Menu::firstOrCreate(['slug' => $menu['slug']], $menu);
        }
    }
}
