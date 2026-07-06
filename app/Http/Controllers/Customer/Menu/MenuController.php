<?php

namespace App\Http\Controllers\Customer\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\Customer\Menu\MenuService;
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
        $filters = $request->only(['search', 'category', 'sort']);
        
        return Inertia::render('Customer/Menu/Index', [
            'menus' => $this->menuService->getFilteredMenus($filters),
            'categories' => $this->menuService->getAllCategories(),
            'filters' => $filters 
        ]);
    }

    public function show($slug)
    {
        $menu = Menu::with('category')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Customer/Menu/Show', [
            'menu' => $menu
        ]);
    }
}
