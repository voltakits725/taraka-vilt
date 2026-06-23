<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function scanQr($table)
    {
        // Validasi table_number antara 1-15
        if (!is_numeric($table) || $table < 1 || $table > 15) {
            abort(404, 'Meja tidak ditemukan');
        }

        // Set table number ke session
        session(['table_number' => $table]);

        // Lempar ke halaman menu
        return redirect()->route('customer.menu')->with('message', "Kamu terhubung dengan Meja $table. Silakan pesan!");
    }

    /**
     * Halaman Home (Landing Page)
     */
    public function index()
    {
        // Ambil 4 menu terbaru buat di-preview di halaman Home
        $previewMenus = Menu::with('category')->latest()->take(4)->get();

        return Inertia::render('Customer/Home/Index', [
            'previewMenus' => $previewMenus
        ]);
    }

    /**
     * Halaman Katalog Menu (dengan Filter & Search)
     */
    public function menu(Request $request)
    {
        $query = Menu::with('category');

        // 1. Fitur Sorting (Diupdate pakai opsi 'normal')
        $sort = $request->sort ?? 'normal';
        
        if ($sort === 'cheapest') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'expensive') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'latest') {
            $query->latest();
        }
        // Kalau 'normal', biarin query-nya apa adanya tanpa di-orderBy

        // 2. Filter Pencarian
        $query->when($request->search, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });

        // 3. Filter Kategori
        $query->when($request->category, function ($q, $categorySlug) {
            $q->whereHas('category', function ($subQuery) use ($categorySlug) {
                $subQuery->where('slug', $categorySlug);
            });
        });

        $menus = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return Inertia::render('Customer/Menu/Index', [
            'menus' => $menus,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'sort']) 
        ]);
    }

    public function show($slug)
    {
        // Cari menu berdasarkan SLUG, bukan ID. Panggil relasi kategori sekalian.
        $menu = Menu::with('category')->where('slug', $slug)->firstOrFail();

        // Nanti kita arahkan ke file Vue yang baru
        return Inertia::render('Customer/Menu/Show', [
            'menu' => $menu
        ]);
    }

    /**
     * Halaman Pengaturan Tema
     */
    public function theme()
    {
        return Inertia::render('Customer/Theme/Index');
    }
}