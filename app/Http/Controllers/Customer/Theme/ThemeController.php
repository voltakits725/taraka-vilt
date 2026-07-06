<?php

namespace App\Http\Controllers\Customer\Theme;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ThemeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Customer/Theme/Index');
    }
}
