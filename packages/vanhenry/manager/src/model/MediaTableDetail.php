<?php
namespace vanhenry\manager\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTableDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function deleteLinked($table, array $id)
    {
        return self::where('map_table', $table)->whereIn('map_id', $id)->delete();
    }

    public static function insertData($table, $id, $media_id, $field = 'img', $type_show = 'IMAGEV2')
    {
        $media = new MediaTableDetail();
        $media->type_show = $type_show;
        $media->media_id = $media_id;
        $media->field = $field;
        $media->map_table = $table;
        $media->map_id = $id;
        $media->save();
    }
}
