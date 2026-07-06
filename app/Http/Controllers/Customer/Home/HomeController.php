<?php

namespace App\Http\Controllers\Customer\Home;

use App\Http\Controllers\Controller;
use App\Services\Customer\Menu\MenuService;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function __invoke()
    {
        return Inertia::render('Customer/Home/Index', [
            'previewMenus' => $this->menuService->getPreviewMenus()
        ]);
    }
}
