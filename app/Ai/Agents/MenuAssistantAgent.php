<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Laravel\Ai\Concerns\RemembersConversations;
use App\Models\Menu;
use App\Models\Ingredient;

class MenuAssistantAgent implements Agent, Conversational
{
    use Promptable;
    use RemembersConversations;

    public function instructions(): string
    {
        $menus = Menu::with(['ingredients', 'category'])->get();
        $ingredients = Ingredient::all();

        $menuDescriptions = $menus->map(function ($menu) {
            $ingredientList = $menu->ingredients->pluck('name')->join(', ') ?: 'Tidak ada informasi bahan';
            $categoryName = $menu->category ? $menu->category->name : 'Uncategorized';
            $desc = "- {$menu->name} (Rp " . number_format($menu->price, 0, ',', '.') . ")\n";
            $desc .= "  Kategori: {$categoryName}\n";
            $desc .= "  Bahan: {$ingredientList}\n";
            $desc .= "  Deskripsi: {$menu->description}\n";
            $desc .= "  Slug/URL: {$menu->slug}\n";
            if ($menu->image) {
                $imageUrl = str_starts_with($menu->image, 'http') ? $menu->image : "/storage/{$menu->image}";
                $desc .= "  Gambar: {$imageUrl}\n";
            }
            return $desc;
        })->join("\n");

        $ingredientDescriptions = $ingredients->map(function ($ingredient) {
            $allergen = $ingredient->is_allergen ? "Ya" : "Tidak";
            return "- {$ingredient->name} (Alergen: {$allergen})";
        })->join("\n");

        return "Kamu adalah asisten AI yang ramah, sopan, dan gaul untuk restoran Taraka.
Kamu bertugas membantu pelanggan restoran memilih menu, terutama terkait bahan alergen atau rekomendasi menu.

Berikut adalah daftar bahan-bahan yang digunakan di restoran beserta informasi apakah bahan tersebut memicu alergi:
{$ingredientDescriptions}

Berikut adalah daftar menu lengkap di restoran kami:
{$menuDescriptions}

Jika pelanggan menanyakan rekomendasi menu tanpa alergen tertentu, carilah menu yang TIDAK MENGANDUNG bahan alergen tersebut.
Jika kamu merekomendasikan menu, sertakan juga gambar dan berikan markdown link menuju detail menu tersebut menggunakan Slug/URL-nya dengan format [Lihat Detail](/menu/{slug}).
Gambar HARUS menggunakan format Markdown image persis sesuai dengan URL yang diberikan, misalnya: ![Gambar Menu](url_gambar_di_sini).

Gunakan bahasa yang santai tapi tetap profesional (misal: menggunakan kata 'Kak' atau sapaan ramah lainnya). Jawablah dalam bahasa Indonesia.";
    }
}
