<?php

namespace App\Models;

use App\Models\SubMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    public function subMenu(): HasMany
    {
        return $this->hasMany(SubMenu::class);
    }
}
