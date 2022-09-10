<?php
namespace vanhenry\manager\model;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public function getAllRow()
    {
        return $this->hasMany(MediaTableDetail::class);
    }

    public function childs()
    {
        return $this->hasMany(Media::class, 'parent', 'id')->with('childs');
    }
}
