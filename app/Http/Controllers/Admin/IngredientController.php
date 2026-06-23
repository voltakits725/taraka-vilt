<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        // Paginasi sederhana 10 per halaman
        $ingredients = Ingredient::latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Ingredient/Index', [
            'ingredients' => $ingredients
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'is_allergen' => 'boolean',
            'allergen_type' => 'nullable|string|max:255', // misal: Kacang, Susu, Seafood
        ]);

        Ingredient::create($validated);

        return back()->with('message', 'Bahan berhasil ditambahkan!');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'is_allergen' => 'boolean',
            'allergen_type' => 'nullable|string|max:255',
        ]);

        // Jika bukan alergen, pastikan tipe alergennya dikosongkan
        if (!$validated['is_allergen']) {
            $validated['allergen_type'] = null;
        }

        $ingredient->update($validated);

        return back()->with('message', 'Bahan berhasil diupdate!');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return back()->with('message', 'Bahan berhasil dihapus!');
    }
}