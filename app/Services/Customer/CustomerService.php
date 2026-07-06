<?php

namespace App\Services\Customer;

use App\Models\Menu;
use App\Models\Category;

class CustomerService
{
    /**
     * Get preview menus for homepage
     */
    public function getPreviewMenus($limit = 4)
    {
        return Menu::with('category')
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get filtered and sorted menus for catalog
     */
    public function getFilteredMenus(array $filters)
    {
        $query = Menu::with('category');

        // Sorting
        $sort = $filters['sort'] ?? 'normal';
        if ($sort === 'cheapest') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'expensive') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'latest') {
            $query->latest();
        }

        // Search
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });

        // Category Filter
        $query->when($filters['category'] ?? null, function ($q, $categorySlug) {
            $q->whereHas('category', function ($subQuery) use ($categorySlug) {
                $subQuery->where('slug', $categorySlug);
            });
        });

        return $query->paginate(12)->withQueryString();
    }

    /**
     * Get all categories
     */
    public function getAllCategories()
    {
        return Category::orderBy('name', 'asc')->get();
    }
}
