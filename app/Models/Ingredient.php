<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

#[Fillable(['name', 'is_allergen', 'allergen_type', 'stock', 'unit'])]
#[Casts(['is_allergen' => 'boolean', 'stock' => 'integer'])]
class Ingredient extends Model
{
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class)->withPivot('amount');
    }
}