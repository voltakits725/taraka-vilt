<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Customer/Profil/Index');
    }
}
