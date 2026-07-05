<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        Schema::disableForeignKeyConstraints();
        DB::table('ingredient_menu')->truncate();
        Menu::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // Kategori Baru Sesuai Menu Asli
        $catCoffeeMocktail = Category::create(['slug' => 'coffee-mocktail', 'name' => 'Coffee Mocktail']);
        $catSignatures = Category::create(['slug' => 'signatures', 'name' => 'Signatures']);
        $catEspresso = Category::create(['slug' => 'classic-espresso', 'name' => 'Classic Espresso']);
        $catTea = Category::create(['slug' => 'tea', 'name' => 'Tea']);
        $catNonCoffee = Category::create(['slug' => 'non-coffee', 'name' => 'Non Coffee']);
        $catNonCoffeeMocktail = Category::create(['slug' => 'non-coffee-mocktail', 'name' => 'Non Coffee Mocktail']);
        $catRotiBakar = Category::create(['slug' => 'roti-bakar', 'name' => 'Roti Bakar']);
        $catSnack = Category::create(['slug' => 'snack', 'name' => 'Snack']);
        $catMains = Category::create(['slug' => 'mains', 'name' => 'Mains']);

        $menus = [
            // CLASSIC ESPRESSO
            ['name' => 'Americano / Long Black', 'description' => 'Espresso dengan air mineral (Iced/Hot)', 'price' => 22000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Cafe Latte / Cappuccino', 'description' => 'Espresso dengan susu steamed', 'price' => 25000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1534687241285-b1a7d6541f71?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Mochaccino', 'description' => 'Espresso, susu, dan coklat', 'price' => 27000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'AvoCoffee', 'description' => 'Espresso dengan perpaduan alpukat', 'price' => 29000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1600262100650-717088b9a117?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Caramel Macchiato', 'description' => 'Espresso, susu, vanila, dan saus karamel', 'price' => 30000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Flavored Latte', 'description' => 'Pilihan rasa: Vanilla / Caramel / Hazelnut', 'price' => 28000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1551834289-408a2fcde524?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Mont Blanc', 'description' => 'Iced espresso signature dengan krim', 'price' => 28000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1557006021-b85faa2bc5e2?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Butterscotch Latte', 'description' => 'Latte dengan manisnya sirup butterscotch', 'price' => 30000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1599307775591-1ec812d7bdf9?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Cloud Caramel Macchiato', 'description' => 'Caramel Macchiato dengan cloud foam manis', 'price' => 30000, 'category_id' => $catEspresso->id, 'image' => 'https://images.unsplash.com/photo-1463797221720-6b07e6014a1d?q=80&w=600&auto=format&fit=crop'],

            // TEA
            ['name' => 'Mixed Fruit Tea', 'description' => 'Teh segar dengan campuran buah segar asli', 'price' => 25000, 'category_id' => $catTea->id, 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Rosella Lemonade', 'description' => 'Rosella segar dipadukan dengan lemonade', 'price' => 20000, 'category_id' => $catTea->id, 'image' => 'https://images.unsplash.com/photo-1620021674482-a0eb8cb11904?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Lychee Tea', 'description' => 'Teh klasik manis dengan buah leci asli', 'price' => 27000, 'category_id' => $catTea->id, 'image' => 'https://images.unsplash.com/photo-1625860633266-9dcaf775df58?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Lemon Tea', 'description' => 'Teh perasan lemon segar', 'price' => 20000, 'category_id' => $catTea->id, 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Black Tea', 'description' => 'Classic premium black tea', 'price' => 15000, 'category_id' => $catTea->id, 'image' => 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd1?q=80&w=600&auto=format&fit=crop'],

            // NON COFFEE
            ['name' => 'Matcha Latte', 'description' => 'Premium Matcha dari Jepang', 'price' => 27000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Chocolate Latte', 'description' => 'Susu coklat pekat yang lembut', 'price' => 26000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Red Velvet Latte', 'description' => 'Latte rasa Red Velvet yang creamy', 'price' => 26000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1611162458324-aae1eb4129a4?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Taro Latte', 'description' => 'Latte ungu manis khas rasa Taro', 'price' => 25000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Avocado Latte', 'description' => 'Susu segar dengan campuran alpukat', 'price' => 25000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1600262100650-717088b9a117?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Hazelnut Chocolate Latte', 'description' => 'Coklat latte dengan aroma hazelnut premium', 'price' => 28000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Cloud Matcha', 'description' => 'Matcha premium dengan tambahan cloud foam di atasnya', 'price' => 30000, 'category_id' => $catNonCoffee->id, 'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?q=80&w=600&auto=format&fit=crop'],

            // NON COFFEE MOCKTAIL
            ['name' => 'Peach Passion Fruit Soda', 'description' => 'Soda markisa dan peach super segar', 'price' => 25000, 'category_id' => $catNonCoffeeMocktail->id, 'image' => 'https://images.unsplash.com/photo-1497534547324-0ebb3f052e88?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Sodelima', 'description' => 'Soda delima segar dan manis', 'price' => 23000, 'category_id' => $catNonCoffeeMocktail->id, 'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Magic Purple Soda', 'description' => 'Soda ungu cantik yang menyegarkan dahaga', 'price' => 23000, 'category_id' => $catNonCoffeeMocktail->id, 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?q=80&w=600&auto=format&fit=crop'],

            // ROTI BAKAR
            ['name' => 'Roti Bakar Cokelat', 'description' => 'Roti panggang dengan isian meses cokelat', 'price' => 22000, 'category_id' => $catRotiBakar->id, 'image' => 'https://images.unsplash.com/photo-1588661601053-96b0dc12b596?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Roti Bakar Keju', 'description' => 'Roti panggang dengan isian keju parut gurih', 'price' => 23000, 'category_id' => $catRotiBakar->id, 'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Roti Bakar Cokelat Keju', 'description' => 'Perpaduan sempurna manisnya cokelat dan gurihnya keju', 'price' => 24000, 'category_id' => $catRotiBakar->id, 'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=600&auto=format&fit=crop'],

            // SNACK
            ['name' => 'Donat Gula', 'description' => 'Donat klasik empuk bertabur gula halus', 'price' => 7000, 'category_id' => $catSnack->id, 'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'French Fries', 'description' => 'Kentang goreng renyah', 'price' => 22000, 'category_id' => $catSnack->id, 'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Cheesy Fries', 'description' => 'Kentang goreng disiram saus keju spesial', 'price' => 24000, 'category_id' => $catSnack->id, 'image' => 'https://images.unsplash.com/photo-1627907228145-385077bf1cc7?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Siomay', 'description' => 'Siomay ikan hangat kukus dengan saus kacang', 'price' => 25000, 'category_id' => $catSnack->id, 'image' => 'https://images.unsplash.com/photo-1625938144755-652e08e359b7?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Sosis Fries', 'description' => 'Sosis goreng lezat dipadukan dengan french fries', 'price' => 26000, 'category_id' => $catSnack->id, 'image' => 'https://images.unsplash.com/photo-1627907228145-385077bf1cc7?q=80&w=600&auto=format&fit=crop'],

            // MAINS
            ['name' => 'Indomie Goreng Ebi', 'description' => 'Indomie goreng nikmat dengan tambahan cita rasa ebi', 'price' => 22000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Spaghetti Bolognese', 'description' => 'Pasta klasik dengan saus daging cincang khas Italia', 'price' => 30000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Cream Pasta', 'description' => 'Pasta al dente dengan saus cream cheese yang lembut', 'price' => 32000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Nasi Goreng Taraka', 'description' => 'Nasi goreng signature dari Taraka Cafe', 'price' => 30000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Chicken Katsu Cheese', 'description' => 'Ayam katsu krispi dengan keju lumer di dalamnya', 'price' => 32000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8d4?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Curry Rice Chicken Katsu', 'description' => 'Kari kental Jepang disajikan dengan Nasi hangat dan Chicken Katsu', 'price' => 38000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1610456108155-22d76f0c0598?q=80&w=600&auto=format&fit=crop'],
            ['name' => 'Curry Rice Sosis + Omelette', 'description' => 'Kari kental Jepang disajikan dengan Nasi hangat, Sosis, dan Omelette', 'price' => 32000, 'category_id' => $catMains->id, 'image' => 'https://images.unsplash.com/photo-1610456108155-22d76f0c0598?q=80&w=600&auto=format&fit=crop'],
        ];

        foreach ($menus as $menu) {
            $menu['slug'] = Str::slug($menu['name']);
            Menu::create($menu);
        }

        // Simple ingredient matching
        $this->matchIngredients('Matcha Latte', ['Susu Sapi']);
        $this->matchIngredients('Chocolate Latte', ['Susu Sapi']);
        $this->matchIngredients('Red Velvet Latte', ['Susu Sapi']);
        $this->matchIngredients('Taro Latte', ['Susu Sapi']);
        $this->matchIngredients('Avocado Latte', ['Susu Sapi']);
        $this->matchIngredients('Hazelnut Chocolate Latte', ['Susu Sapi', 'Kacang Pohon']);
        
        $this->matchIngredients('Roti Bakar Cokelat', ['Gandum', 'Susu Sapi']);
        $this->matchIngredients('Roti Bakar Keju', ['Gandum', 'Susu Sapi']);
        $this->matchIngredients('Roti Bakar Cokelat Keju', ['Gandum', 'Susu Sapi']);
        
        $this->matchIngredients('Donat Gula', ['Gandum', 'Telur', 'Susu Sapi']);
        $this->matchIngredients('Siomay', ['Ikan', 'Kacang Tanah']);
        
        $this->matchIngredients('Spaghetti Bolognese', ['Gandum']);
        $this->matchIngredients('Cream Pasta', ['Gandum', 'Susu Sapi', 'Telur']);
        $this->matchIngredients('Nasi Goreng Taraka', ['Telur']);
        $this->matchIngredients('Chicken Katsu Cheese', ['Gandum', 'Susu Sapi', 'Telur']);
        $this->matchIngredients('Curry Rice Chicken Katsu', ['Gandum', 'Telur']);
        $this->matchIngredients('Curry Rice Sosis + Omelette', ['Telur']);
    }

    private function matchIngredients(string $menuName, array $ingredientNames)
    {
        $menu = Menu::where('name', $menuName)->first();
        if ($menu) {
            $ingredientIds = \App\Models\Ingredient::whereIn('name', $ingredientNames)->pluck('id');
            $menu->ingredients()->sync($ingredientIds);
        }
    }
}
