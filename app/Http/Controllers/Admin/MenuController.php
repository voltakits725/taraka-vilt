<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Ingredient;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class MenuController extends Controller
{
    public function __construct()
    {
        Configuration::instance(env('CLOUDINARY_URL'));
    }

    public function index(Request $request)
    {
        $query = Menu::with('category')->latest();

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        return Inertia::render('Admin/Menu/Index', [
            'menus' => $query->paginate(5)->withQueryString(),
            'categories' => Category::all(),
            // Kirim data bahan ke frontend
            'ingredients' => Ingredient::orderBy('name')->get(),
            'filters' => $request->only(['category']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Menu/Create', [
            'categories' => Category::all(),
            'ingredients' => \App\Models\Ingredient::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredient_ids' => 'nullable|array', // Validasi array ID bahan
            'ingredient_ids.*' => 'exists:ingredients,id',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $response = $uploadApi->upload($request->file('image')->getRealPath(), [
                'folder' => 'cafe_taraka/menus'
            ]);
            $imageUrl = $response['secure_url'];
        }

        $menu = Menu::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imageUrl,
        ]);

        // Simpan relasi ke tabel pivot
        if (isset($validated['ingredient_ids'])) {
            $menu->ingredients()->sync($validated['ingredient_ids']);
        }

        return redirect('/admin/menus')->with('message', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menu/Edit', [
            'menu' => $menu->load('ingredients'), // Load relasi bahan buat dicentang otomatis
            'categories' => Category::all(),
            'ingredients' => \App\Models\Ingredient::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredient_ids' => 'nullable|array',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ]);

        $imageUrl = $menu->image;

        if ($request->hasFile('image')) {
            $this->deleteCloudinaryImage($menu->image);
            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $response = $uploadApi->upload($request->file('image')->getRealPath(), [
                'folder' => 'cafe_taraka/menus'
            ]);
            $imageUrl = $response['secure_url'];
        }

        $menu->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imageUrl,
        ]);

        // Sync relasi ke tabel pivot (otomatis insert/delete ID yang sesuai)
        if (isset($validated['ingredient_ids'])) {
            $menu->ingredients()->sync($validated['ingredient_ids']);
        } else {
            $menu->ingredients()->detach(); // Kosongkan jika tidak ada yang dicentang
        }

        return redirect('/admin/menus')->with('message', 'Menu berhasil diupdate!');
    }

    public function show(Menu $menu)
    {
        return Inertia::render('Admin/Menu/Show', [
            // Load relasi kategori dan bahan sekaligus
            'menu' => $menu->load(['category', 'ingredients']) 
        ]);
    }

    public function destroy(Menu $menu)
    {
        // Hapus gambar di Cloudinary sebelum data di database dihapus
        $this->deleteCloudinaryImage($menu->image);
        
        $menu->delete();
        return back()->with('message', 'Menu beserta gambarnya berhasil dihapus!');
    }

    

    // --- Private Helper Method ---
    private function deleteCloudinaryImage($url)
    {
        if (!$url) return;

        // Cloudinary butuh "Public ID" buat ngehapus file.
        // Karena kita naruh di folder 'cafe_taraka/menus', kita harus ekstrak nama filenya
        // dari URL asli, lalu gabungin lagi sama nama foldernya.
        $filename = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
        $publicId = 'cafe_taraka/menus/' . $filename;

        try {
            $uploadApi = new UploadApi();
            $uploadApi->destroy($publicId);
        } catch (\Exception $e) {
            // Error di-catch agar aplikasi tidak crash jika gambar sudah terhapus manual
            // di dashboard Cloudinary.
        }
    }
}