<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Laravel\Ai\Ai;

class MenuAIController extends Controller
{
    public function ask(Request $request, Menu $menu)
    {
        $request->validate([
            'question' => 'required|string|max:1000'
        ]);

        $menu->load('ingredients');
        $allMenus = Menu::with('ingredients')->get();

        $systemPrompt = "Anda adalah asisten AI dari cafe Taraka. Pengguna saat ini sedang melihat menu '{$menu->name}'.\n"
            . "Deskripsi menu ini: {$menu->description}\n"
            . "Bahan-bahannya: " . $menu->ingredients->pluck('name')->implode(', ') . "\n\n"
            . "Tugas Anda:\n"
            . "1. Jawab pertanyaan pengguna dengan ramah, informatif, dan jelas.\n"
            . "2. Jika pengguna bertanya tentang alergen (seperti kacang, susu, seafood, dll), berikan analisis apakah menu tersebut aman.\n"
            . "3. Anda juga HARUS memberikan rekomendasi menu lain yang AMAN dari alergen tersebut.\n\n"
            . "Daftar semua menu cafe yang tersedia (beserta bahan-bahannya) untuk referensi rekomendasi:\n";

        foreach ($allMenus as $m) {
            $ingredients = $m->ingredients->pluck('name')->implode(', ');
            $systemPrompt .= "- {$m->name} (Slug: {$m->slug}): {$m->description} | Bahan: {$ingredients}\n";
        }

        $systemPrompt .= "\n"
            . "PENTING: Jawab menggunakan format JSON yang ketat (strict). Anda tidak boleh merespons dengan teks biasa, hanya JSON.\n"
            . "Struktur JSON yang WAJIB digunakan:\n"
            . "{\n"
            . '  "answer": "Jawaban naratif yang ramah tentang menu saat ini dan informasinya.",' . "\n"
            . '  "safe_menus_slugs": ["slug-menu-1", "slug-menu-2"]' . "\n"
            . "}\n\n"
            . "Pastikan `safe_menus_slugs` berisi array dari slug menu yang direkomendasikan. Jika tidak ada rekomendasi, kosongkan array tersebut.\n";

        try {
            // Using Laravel AI Facade
            $response = \Laravel\Ai\AnonymousAgent::make($systemPrompt, [], [])
                ->prompt($request->question, provider: 'gemini');

            // Extract text from AI response
            $jsonResponse = $response->text;
            
            // Clean markdown JSON block if exists
            $jsonResponse = str_replace(['```json', '```'], '', $jsonResponse);
            $parsed = json_decode(trim($jsonResponse), true);

            if (!is_array($parsed)) {
                throw new \Exception("AI tidak mengembalikan JSON yang valid: " . $jsonResponse);
            }

            // Fetch the recommended menus full data
            $recommendedMenus = [];
            if (!empty($parsed['safe_menus_slugs'])) {
                $recommendedMenus = Menu::whereIn('slug', $parsed['safe_menus_slugs'])
                    ->select('id', 'name', 'slug', 'image', 'price', 'description')
                    ->get();
            }

            return response()->json([
                'answer' => $parsed['answer'] ?? 'Maaf, saya tidak bisa memberikan jawaban saat ini.',
                'recommendations' => $recommendedMenus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal terhubung ke AI: ' . $e->getMessage()
            ], 500);
        }
    }
}
