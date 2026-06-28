<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\Customer\CustomerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function scanQr($table)
    {
        if (!is_numeric($table) || $table < 1 || $table > 15) {
            abort(404, 'Meja tidak ditemukan');
        }

        session(['table_number' => $table]);

        return redirect()->route('customer.menu')->with('message', "Kamu terhubung dengan Meja $table. Silakan pesan!");
    }

    public function index()
    {
        return Inertia::render('Customer/Home/Index', [
            'previewMenus' => $this->customerService->getPreviewMenus()
        ]);
    }

    public function menu(Request $request)
    {
        $filters = $request->only(['search', 'category', 'sort']);
        
        return Inertia::render('Customer/Menu/Index', [
            'menus' => $this->customerService->getFilteredMenus($filters),
            'categories' => $this->customerService->getAllCategories(),
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

    public function theme()
    {
        return Inertia::render('Customer/Theme/Index');
    }
}