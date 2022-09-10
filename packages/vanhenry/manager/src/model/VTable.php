<?php
namespace vanhenry\manager\model;

use Cache;
use Illuminate\Database\Eloquent\Model;
class VTable extends Model
{
    public static function getTranslateTable($table){
        return Cache::rememberForever($table, function () use ($table) {
            return static::where('act', 1)->where('translation_of', $table)->first() ?? false;
        });
    }
}
