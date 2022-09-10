<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
class MenuCategory extends BaseModel
{
    use HasFactory;

    public function menus(){
        return $this->belongsToMany(Menu::class);
    }
}