<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Profil/Index');
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if ($request->hasFile('avatar')) {
            try {
                \Cloudinary\Configuration\Configuration::instance(env('CLOUDINARY_URL'));
                $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
                
                // If user already has a cloudinary avatar, we could delete it, but since Google avatars might also be there, we just overwrite the DB field
                $response = $uploadApi->upload($request->file('avatar')->getRealPath(), [
                    'folder' => 'cafe_taraka/avatars'
                ]);
                $user->avatar = $response['secure_url'];
            } catch (\Exception $e) {
                // Return back with error if cloudinary fails
                return back()->withErrors(['avatar' => 'Gagal mengunggah foto profil. Silakan coba lagi.']);
            }
        }

        $user->save();

        return back()->with('message', 'Profil berhasil diperbarui!');
    }
}
