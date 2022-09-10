<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Menu extends BaseModel
{
	use HasFactory;
	public function childs()
    {
	    return $this->hasMany(Menu::class, 'parent', 'id')->with('childs');
    }
    
    public function recursiveChilds()
    {
    	return $this->childs()->act()->ord()->with('recursiveChilds');
    }

    public function getParent(){
        return $this->belongsTo(static::class,'parent','id');
    }

    public function menuCategory(){
        return $this->belongsTo(MenuCategory::class);
    }

    public function menuCategories(){
        return $this->belongsToMany(MenuCategory::class);
    }
}