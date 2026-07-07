<?php
namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Ingredient;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Services\Admin\Menu\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
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
            'ingredients' => Ingredient::orderBy('name')->get(),
            'filters' => $request->only(['category']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Menu/Create', [
            'categories' => Category::all(),
            'ingredients' => Ingredient::orderBy('name')->get(),
        ]);
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->createMenu(
            $request->validated(), 
            $request->file('image')
        );

        return redirect('/admin/menus')->with('message', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menu/Edit', [
            'menu' => $menu->load('ingredients'),
            'categories' => Category::all(),
            'ingredients' => Ingredient::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $this->menuService->updateMenu(
            $menu,
            $request->validated(), 
            $request->file('image')
        );

        return redirect('/admin/menus')->with('message', 'Menu berhasil diupdate!');
    }

    public function show(Menu $menu)
    {
        return Inertia::render('Admin/Menu/Show', [
            'menu' => $menu->load(['category', 'ingredients']) 
        ]);
    }

    public function destroy(Menu $menu)
    {
        $this->menuService->deleteMenu($menu);
        
        return back()->with('message', 'Menu beserta gambarnya berhasil dihapus!');
    }
}