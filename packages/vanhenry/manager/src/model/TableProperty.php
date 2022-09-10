<?php



namespace vanhenry\manager\model;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class TableProperty extends Model
{
    public static function cacheTableProperty($table_id){
        return Cache::rememberForever('table_property_'.$table_id,function() use($table_id){
            return static::where("act",1)->where("parent",$table_id)->orderBy("ord")->get()->toArray();
        });
    }
    //
}

