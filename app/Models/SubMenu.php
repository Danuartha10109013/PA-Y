<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMenu extends Model
{
    use HasFactory;

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
