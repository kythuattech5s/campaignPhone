<?php
namespace vanhenry\manager\model;

use Cache;
use Illuminate\Database\Eloquent\Model;

class VConfigRegion extends Model
{
    public static function getConfigRegion($table, $dataItem = null, $isParent = true)
    {
        return $isParent ? Cache::rememberForever('config_region_' . $table, function () use ($table) {
            return static::where(array('act' => 1, 'parent' => '0'))->where("table", $table)->orderBy('ord', 'asc')->get();
        }) : Cache::rememberForever('config_region_' . $table.$dataItem->id, function () use ($dataItem) {
            return static::where(array('act' => 1, 'parent' => $dataItem->id))->orderBy('ord', 'asc')->get();
        });
    }
}
