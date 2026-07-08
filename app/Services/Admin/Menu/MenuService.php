<?php

namespace App\Services\Admin\Menu;

use App\Models\Menu;
use Illuminate\Support\Str;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;

class MenuService
{
    public function __construct()
    {
        Configuration::instance(env('CLOUDINARY_URL'));
    }

    /**
     * Create a new menu item
     */
    public function createMenu(array $data, ?UploadedFile $imageFile): Menu
    {
        $imageUrl = null;
        
        if ($imageFile) {
            $imageUrl = $this->uploadImage($imageFile);
        }

        $menu = Menu::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $imageUrl,
        ]);

        if (isset($data['ingredients'])) {
            $syncData = [];
            foreach ($data['ingredients'] as $ingredient) {
                $syncData[$ingredient['id']] = ['amount' => $ingredient['amount']];
            }
            $menu->ingredients()->sync($syncData);
        }

        return $menu;
    }

    /**
     * Update an existing menu item
     */
    public function updateMenu(Menu $menu, array $data, ?UploadedFile $imageFile): Menu
    {
        $imageUrl = $menu->image;

        if ($imageFile) {
            $this->deleteImage($menu->image);
            $imageUrl = $this->uploadImage($imageFile);
        }

        $menu->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $imageUrl,
        ]);

        if (isset($data['ingredients'])) {
            $syncData = [];
            foreach ($data['ingredients'] as $ingredient) {
                $syncData[$ingredient['id']] = ['amount' => $ingredient['amount']];
            }
            $menu->ingredients()->sync($syncData);
        } else {
            $menu->ingredients()->detach();
        }

        return $menu;
    }

    /**
     * Delete a menu item and its image
     */
    public function deleteMenu(Menu $menu): void
    {
        $this->deleteImage($menu->image);
        $menu->delete();
    }

    /**
     * Upload image to Cloudinary
     */
    private function uploadImage(UploadedFile $file): string
    {
        $uploadApi = new UploadApi();
        $response = $uploadApi->upload($file->getRealPath(), [
            'folder' => 'cafe_taraka/menus'
        ]);
        
        return $response['secure_url'];
    }

    /**
     * Delete image from Cloudinary
     */
    private function deleteImage(?string $url): void
    {
        if (!$url) return;

        $filename = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
        $publicId = 'cafe_taraka/menus/' . $filename;

        try {
            $uploadApi = new UploadApi();
            $uploadApi->destroy($publicId);
        } catch (\Exception $e) {
            // Ignore if image is already deleted manually in Cloudinary
        }
    }
}
